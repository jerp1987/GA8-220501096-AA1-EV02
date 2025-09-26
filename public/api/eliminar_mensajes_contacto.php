<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../src/config/conexion.php';

// Lee datos JSON
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
    if (empty($in['mensaje_id'])) {
        http_response_code(422);
        echo json_encode(['success'=>false, 'error'=>'Falta mensaje_id']);
        exit;
    }
    $mensaje_id = (int)$in['mensaje_id'];

    $pdo = db();

    // Eliminar mensaje (la respuesta se elimina en cascada)
    $stmt = $pdo->prepare("DELETE FROM mensajes_contacto WHERE id = ?");
    $stmt->execute([$mensaje_id]);

    echo json_encode(['success'=>true], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success'=>false, 'error'=>$e->getMessage()]);
}