<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Actualizar la tabla tbl_usuarios para soportar autenticación moderna
        Schema::table('tbl_usuarios', function (Blueprint $table) {
            // Cambiar clave de varchar(10) a varchar(255) para soportar hashes
            $table->string('clave', 255)->change();

            // Agregar campos necesarios para Laravel Auth si no existen
            if (!Schema::hasColumn('tbl_usuarios', 'remember_token')) {
                $table->rememberToken();
            }

            if (!Schema::hasColumn('tbl_usuarios', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }

            if (!Schema::hasColumn('tbl_usuarios', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }

            if (!Schema::hasColumn('tbl_usuarios', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable();
            }

            // Campos adicionales útiles para relacionar con alumnos/profesores
            if (!Schema::hasColumn('tbl_usuarios', 'alumno_id')) {
                $table->integer('alumno_id')->nullable();
            }

            if (!Schema::hasColumn('tbl_usuarios', 'profesor_id')) {
                $table->integer('profesor_id')->nullable();
            }
        });

        // Hashear las contraseñas existentes que están en texto plano
        $usuarios = DB::table('tbl_usuarios')->get();

        foreach ($usuarios as $usuario) {
            // Solo hashear si la contraseña parece estar en texto plano (longitud < 60)
            if (strlen($usuario->clave) < 60) {
                DB::table('tbl_usuarios')
                    ->where('id', $usuario->id)
                    ->update([
                        'clave' => Hash::make($usuario->clave),
                        'updated_at' => now(),
                    ]);
            }
        }

        // Crear índices para mejorar el performance
        Schema::table('tbl_usuarios', function (Blueprint $table) {
            // Índice en usuario para login rápido
            if (!Schema::hasColumn('tbl_usuarios', 'usuario')) {
                $table->index('usuario');
            }

            // Índice en email
            if (!Schema::hasColumn('tbl_usuarios', 'email')) {
                $table->index('email');
            }

            // Índices para relaciones
            if (!Schema::hasColumn('tbl_usuarios', 'alumno_id')) {
                $table->index('alumno_id');
            }

            if (!Schema::hasColumn('tbl_usuarios', 'profesor_id')) {
                $table->index('profesor_id');
            }
        });

        // Intentar crear foreign keys si las tablas existen
        if (Schema::hasTable('tbl_alumnos')) {
            try {
                Schema::table('tbl_usuarios', function (Blueprint $table) {
                    $table->foreign('alumno_id')
                        ->references('id')
                        ->on('tbl_alumnos')
                        ->onDelete('set null');
                });
            } catch (\Exception $e) {
                // La foreign key ya existe o hay un problema, continuar
            }
        }

        if (Schema::hasTable('tbl_profesores')) {
            try {
                Schema::table('tbl_usuarios', function (Blueprint $table) {
                    $table->foreign('profesor_id')
                        ->references('id')
                        ->on('tbl_profesores')
                        ->onDelete('set null');
                });
            } catch (\Exception $e) {
                // La foreign key ya existe o hay un problema, continuar
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_usuarios', function (Blueprint $table) {
            // Eliminar foreign keys si existen
            try {
                $table->dropForeign(['alumno_id']);
            } catch (\Exception $e) {}

            try {
                $table->dropForeign(['profesor_id']);
            } catch (\Exception $e) {}

            // Eliminar columnas agregadas
            $table->dropColumn([
                'remember_token',
                'created_at',
                'updated_at',
                'email_verified_at',
                'alumno_id',
                'profesor_id',
            ]);

            // Revertir clave a varchar(10) (esto truncará datos!)
            $table->string('clave', 10)->change();
        });
    }
};
