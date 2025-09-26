<?php
// src/helpers/response.php
declare(strict_types=1);

/**
 * Envía una respuesta JSON con éxito o error.
 * @param bool   $ok     true=éxito, false=error
 * @param mixed  $data   Datos o mensaje de error
 * @param int    $code   Código HTTP (por defecto 200)
 */
function json_response($ok, $data = null, int $code = 200){
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');

    echo json_encode([
        'ok'   => (bool)$ok,
        'data' => $data
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Envía una respuesta de error y termina la ejecución.
 * @param string $msg   Mensaje de error
 * @param int    $code  Código HTTP (por defecto 400)
 */
function fail($msg, int $code = 400){
    json_response(false, ['error' => $msg], $code);
}