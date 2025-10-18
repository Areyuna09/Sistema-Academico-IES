<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Carbon\CarbonImmutable;
use App\Models\User;

class JwtService
{
    public function issueToken(User $user, ?string $deviceName = 'api'): array
    {
        $secret = Config::get('jwt.secret');
        if (!$secret) {
            throw new \RuntimeException('JWT_SECRET no está configurado.');
        }

        $now = CarbonImmutable::now();
        $ttl = (int) Config::get('jwt.ttl', 60);
        $exp = $now->addMinutes($ttl);

        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT',
        ];

        $jti = (string) Str::uuid();

        $payload = [
            'iss' => Config::get('jwt.issuer'),
            'aud' => Config::get('jwt.audience'),
            'iat' => $now->timestamp,
            'nbf' => $now->timestamp,
            'exp' => $exp->timestamp,
            'jti' => $jti,
            'sub' => (int) $user->id,
            'dni' => $user->dni,
            'role' => $user->role,
            'tipo' => (int) $user->tipo,
            'alumno_id' => $user->alumno_id,
            'profesor_id' => $user->profesor_id,
            'dev' => $deviceName,
        ];

        $token = $this->encode($header, $payload, $secret);

        return [
            'token' => $token,
            'expires_at' => $exp->toIso8601String(),
            'jti' => $jti,
        ];
    }

    public function verify(string $token): ?array
    {
        $secret = Config::get('jwt.secret');
        if (!$secret) {
            return null;
        }

        [$headerB64, $payloadB64, $signatureB64] = array_pad(explode('.', $token), 3, null);
        if (!$headerB64 || !$payloadB64 || !$signatureB64) {
            return null;
        }

        $header = json_decode($this->b64urlDecode($headerB64), true);
        $payload = json_decode($this->b64urlDecode($payloadB64), true);

        if (!is_array($header) || !is_array($payload)) {
            return null;
        }

        $expectedSig = $this->sign("{$headerB64}.{$payloadB64}", $secret);
        if (!hash_equals($expectedSig, $signatureB64)) {
            return null;
        }

        $now = time();
        if (($payload['nbf'] ?? 0) > $now) return null;
        if (($payload['exp'] ?? 0) <= $now) return null;

        $jti = $payload['jti'] ?? null;
        if ($jti && Cache::get($this->blacklistKey($jti))) {
            return null; // token revocado
        }

        return $payload;
    }

    public function blacklist(string $jti, int $expTimestamp): void
    {
        $ttl = max(1, $expTimestamp - time());
        Cache::put($this->blacklistKey($jti), true, $ttl);
    }

    public function userFromPayload(array $payload): ?User
    {
        $sub = $payload['sub'] ?? null;
        if (!$sub) return null;
        $user = User::find((int) $sub);
        if (!$user || !$user->activo) return null;
        return $user;
    }

    private function encode(array $header, array $payload, string $secret): string
    {
        $h = $this->b64urlEncode(json_encode($header, JSON_UNESCAPED_SLASHES));
        $p = $this->b64urlEncode(json_encode($payload, JSON_UNESCAPED_SLASHES));
        $s = $this->sign("{$h}.{$p}", $secret);
        return "{$h}.{$p}.{$s}";
    }

    private function sign(string $message, string $secret): string
    {
        $raw = hash_hmac('sha256', $message, $secret, true);
        return $this->b64urlEncode($raw);
    }

    private function b64urlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function b64urlDecode(string $data): string
    {
        $r = strlen($data) % 4;
        if ($r) {
            $data .= str_repeat('=', 4 - $r);
        }
        return base64_decode(strtr($data, '-_', '+/')) ?: '';
    }

    private function blacklistKey(string $jti): string
    {
        return 'jwt:blacklist:'.$jti;
    }
}

