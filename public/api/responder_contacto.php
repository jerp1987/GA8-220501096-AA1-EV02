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
    if (empty($in['mensaje_id']) || empty($in['respuesta']) || empty($in['respondido_por'])) {
        http_response_code(422);
        echo json_encode(['success'=>false, 'error'=>'Faltan datos obligatorios']);
        exit;
    }
    $mensaje_id = (int)$in['mensaje_id'];
    $respuesta = trim($in['respuesta']);
    $respondido_por = trim($in['respondido_por']);

    $pdo = db();

    // Revisar si ya existe una respuesta para este mensaje
    $stmt = $pdo->prepare("SELECT id FROM respuestas_contacto WHERE mensaje_id = ?");
    $stmt->execute([$mensaje_id]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['success'=>false, 'error'=>'El mensaje ya fue respondido']);
        exit;
    }

    // Insertar respuesta
    $stmt = $pdo->prepare("INSERT INTO respuestas_contacto (mensaje_id, respuesta, respondido_por, fecha_respuesta) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$mensaje_id, $respuesta, $respondido_por]);

    echo json_encode(['success'=>true], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success'=>false, 'error'=>$e->getMessage()]);
}