<?php
// src/helpers/input.php
declare(strict_types=1);

/**
 * Devuelve el array de datos del cuerpo de la petición:
 * - Si es JSON, lo decodifica.
 * - Si es formulario, usa $_POST.
 * - Si es GET, usa $_GET.
 */
function body(): array {
    $type = $_SERVER['CONTENT_TYPE'] ?? '';
    if (stripos($type, 'application/json') !== false) {
        $raw = file_get_contents('php://input');
        $arr = json_decode($raw, true);
        return is_array($arr) ? $arr : [];
    }
    return $_POST ?: $_GET;
}

/**
 * Devuelve un parámetro desde el array fuente, o el valor por defecto si no existe.
 * @param array  $src   Fuente de datos (ej: body())
 * @param string $key   Clave a buscar
 * @param mixed  $default  Valor por defecto si no está presente
 */
function param(array $src, string $key, $default = null) {
    return isset($src[$key]) && $src[$key] !== '' ? $src[$key] : $default;
}

/**
 * Valida que existan TODOS los campos requeridos en el array fuente.
 * @param array $src  Array de datos (ej: body())
 * @param array $keys Lista de claves obligatorias
 */
function required(array $src, array $keys) {
    foreach($keys as $k) {
        if(!isset($src[$k]) || $src[$k] === '') fail("Falta el campo requerido: $k", 400);
    }
}