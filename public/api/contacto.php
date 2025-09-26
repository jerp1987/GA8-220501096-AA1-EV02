<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../src/config/conexion.php';

// Función para leer el JSON del cuerpo
function body_json() {
    $raw = file_get_contents('php://input');
    return $raw ? json_decode($raw, true) : [];
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success'=>false, 'error'=>'Método no soportado']);
        exit;
    }

    $in = body_json();

    // Validar campos requeridos
    $requeridos = ['nombre', 'correo', 'telefono', 'mensaje'];
    foreach ($requeridos as $campo) {
        if (empty($in[$campo])) {
            http_response_code(422);
            echo json_encode(['success'=>false, 'error'=>"Falta el campo: $campo"]);
            exit;
        }
    }

    // Limpiar datos
    $nombre = trim($in['nombre']);
    $correo = strtolower(trim($in['correo']));
    $telefono = trim($in['telefono']);
    $mensaje = trim($in['mensaje']);

    // Insertar en la base de datos
    $pdo = db();
    $sql = "INSERT INTO mensajes_contacto (nombre, correo, telefono, mensaje, fecha_envio) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nombre, $correo, $telefono, $mensaje]);

    echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success'=>false, 'error'=>$e->getMessage()]);
}