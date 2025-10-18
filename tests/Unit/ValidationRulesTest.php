<?php

use Illuminate\Support\Facades\Validator;

// Tests UNIT - NO usan base de datos, solo prueban lógica de validación

test('DNI validation rule works correctly', function () {
    // Reglas que usamos en UsuariosController
    $rules = [
        'dni' => ['required', 'string', 'max:10', 'regex:/^[0-9]+$/'],
    ];

    // ✅ DNI válido
    $validator = Validator::make(['dni' => '12345678'], $rules);
    expect($validator->passes())->toBeTrue();

    // ❌ DNI con letras (debe fallar)
    $validator = Validator::make(['dni' => 'abc12345'], $rules);
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('dni'))->toBeTrue();

    // ❌ DNI mayor a 10 caracteres (debe fallar)
    $validator = Validator::make(['dni' => '12345678901'], $rules);
    expect($validator->fails())->toBeTrue();
});

test('nombre validation requires at least one letter', function () {
    $rules = [
        'nombre' => ['required', 'string', 'max:100', 'regex:/^.*[a-zA-ZáéíóúÁÉÍÓÚñÑ]+.*$/'],
    ];

    // ✅ Nombre válido
    $validator = Validator::make(['nombre' => 'Juan Pérez'], $rules);
    expect($validator->passes())->toBeTrue();

    // ✅ Nombre con números pero tiene letras
    $validator = Validator::make(['nombre' => 'Juan123'], $rules);
    expect($validator->passes())->toBeTrue();

    // ❌ Solo números (debe fallar)
    $validator = Validator::make(['nombre' => '12345678'], $rules);
    expect($validator->fails())->toBeTrue();

    // ❌ Mayor a 100 caracteres (debe fallar)
    $longName = str_repeat('a', 101);
    $validator = Validator::make(['nombre' => $longName], $rules);
    expect($validator->fails())->toBeTrue();
});

test('email validation enforces max 100 characters', function () {
    $rules = [
        'email' => 'required|email|max:100',
    ];

    // ✅ Email válido
    $validator = Validator::make(['email' => 'test@example.com'], $rules);
    expect($validator->passes())->toBeTrue();

    // ❌ Email mayor a 100 caracteres
    $longEmail = str_repeat('a', 90) . '@example.com'; // 102 caracteres
    $validator = Validator::make(['email' => $longEmail], $rules);
    expect($validator->fails())->toBeTrue();
});

test('telefono validation accepts only valid formats', function () {
    $rules = [
        'telefono' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\s\-\+\(\)]+$/'],
    ];

    // ✅ Teléfonos válidos
    $validPhones = [
        '381-4567890',
        '+54 381 4567890',
        '(381) 456-7890',
        '3814567890',
    ];

    foreach ($validPhones as $phone) {
        $validator = Validator::make(['telefono' => $phone], $rules);
        expect($validator->passes())->toBeTrue("Phone $phone should be valid");
    }

    // ❌ Teléfonos inválidos
    $invalidPhones = [
        '381-ABC-DEFG',
        'invalid-phone',
        str_repeat('1', 25), // Más de 20 caracteres
    ];

    foreach ($invalidPhones as $phone) {
        $validator = Validator::make(['telefono' => $phone], $rules);
        expect($validator->fails())->toBeTrue("Phone $phone should be invalid");
    }
});

test('carrera nombre validation max 55 characters', function () {
    $rules = [
        'nombre' => 'required|string|max:55',
    ];

    // ✅ Nombre válido (55 caracteres)
    $validName = str_repeat('a', 55);
    $validator = Validator::make(['nombre' => $validName], $rules);
    expect($validator->passes())->toBeTrue();

    // ❌ Nombre muy largo (56 caracteres)
    $longName = str_repeat('a', 56);
    $validator = Validator::make(['nombre' => $longName], $rules);
    expect($validator->fails())->toBeTrue();
});

test('periodo nombre validation max 100 characters', function () {
    $rules = [
        'nombre' => 'required|string|max:100',
    ];

    // ✅ Nombre válido
    $validator = Validator::make(['nombre' => 'Primer Cuatrimestre 2025'], $rules);
    expect($validator->passes())->toBeTrue();

    // ❌ Nombre muy largo (101 caracteres)
    $longName = str_repeat('a', 101);
    $validator = Validator::make(['nombre' => $longName], $rules);
    expect($validator->fails())->toBeTrue();
});

test('observaciones validation max 500 characters', function () {
    $rules = [
        'observaciones' => 'nullable|string|max:500',
    ];

    // ✅ Observaciones válidas (500 caracteres)
    $validObs = str_repeat('a', 500);
    $validator = Validator::make(['observaciones' => $validObs], $rules);
    expect($validator->passes())->toBeTrue();

    // ❌ Observaciones muy largas (501 caracteres)
    $longObs = str_repeat('a', 501);
    $validator = Validator::make(['observaciones' => $longObs], $rules);
    expect($validator->fails())->toBeTrue();
});

test('year validation for periodos allows 2020-2050', function () {
    $rules = [
        'anio' => 'required|integer|min:2020|max:2050',
    ];

    // ✅ Años válidos
    foreach ([2020, 2025, 2050] as $year) {
        $validator = Validator::make(['anio' => $year], $rules);
        expect($validator->passes())->toBeTrue("Year $year should be valid");
    }

    // ❌ Años inválidos
    foreach ([2019, 2051, 1999] as $year) {
        $validator = Validator::make(['anio' => $year], $rules);
        expect($validator->fails())->toBeTrue("Year $year should be invalid");
    }
});

test('cuatrimestre validation allows only 1 or 2', function () {
    $rules = [
        'cuatrimestre' => 'required|integer|min:1|max:2',
    ];

    // ✅ Valores válidos
    foreach ([1, 2] as $value) {
        $validator = Validator::make(['cuatrimestre' => $value], $rules);
        expect($validator->passes())->toBeTrue();
    }

    // ❌ Valores inválidos
    foreach ([0, 3, -1, 5] as $value) {
        $validator = Validator::make(['cuatrimestre' => $value], $rules);
        expect($validator->fails())->toBeTrue();
    }
});
