<?php

use Illuminate\Support\Facades\Validator;

// 🔴 DEMO: Este test demuestra cómo detectar validaciones ROTAS

test('DEMO - DNI validation BROKEN (missing regex)', function () {
    // ❌ Esta regla está ROTA - falta el regex que valida solo números
    $brokenRules = [
        'dni' => ['required', 'string', 'max:10'], // ¡FALTA regex:/^[0-9]+$/!
    ];

    // Este DNI tiene LETRAS - debería FALLAR pero con reglas rotas, PASA ❌
    $validator = Validator::make(['dni' => 'abc12345'], $brokenRules);

    // El test ESPERA que falle (porque tiene letras)
    // Si las reglas están bien, esto pasa ✅
    // Si falta el regex, esto FALLA 🔴 y nos alerta del problema
    expect($validator->fails())->toBeTrue();
})->skip('Test de demostración - quitar ->skip() para ver cómo detecta validaciones rotas');

test('DEMO - Comparación con validación CORRECTA', function () {
    // ✅ Regla CORRECTA - con regex
    $correctRules = [
        'dni' => ['required', 'string', 'max:10', 'regex:/^[0-9]+$/'],
    ];

    // Este DNI con letras SÍ falla como debe ser ✅
    $validator = Validator::make(['dni' => 'abc12345'], $correctRules);
    expect($validator->fails())->toBeTrue();

    // Y este DNI solo números SÍ pasa ✅
    $validator = Validator::make(['dni' => '12345678'], $correctRules);
    expect($validator->passes())->toBeTrue();
});
