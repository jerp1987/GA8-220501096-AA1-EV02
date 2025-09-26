<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../src/config/conexion.php';

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

    // Validación básica
    if (empty($in['cita_id']) || empty($in['motivo'])) {
        http_response_code(422);
        echo json_encode(['success'=>false, 'error'=>'Falta el ID de cita o el motivo.']);
        exit;
    }

    $cita_id = (int) $in['cita_id'];
    $motivo = trim($in['motivo']);

    $pdo = db();

    // Verifica que la cita exista y esté activa (opcional: puedes chequear que no esté ya cancelada)
    $stmt = $pdo->prepare("SELECT * FROM citas WHERE id=? AND estado='pendiente'");
    $stmt->execute([$cita_id]);
    $cita = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cita) {
        http_response_code(404);
        echo json_encode(['success'=>false, 'error'=>'La cita no existe o ya está cancelada.']);
        exit;
    }

    // Registra la cancelación
    $stmt = $pdo->prepare("INSERT INTO cancelaciones (cita_id, motivo) VALUES (?, ?)");
    $stmt->execute([$cita_id, $motivo]);

    // Cambia el estado de la cita a 'cancelada'
    $stmt = $pdo->prepare("UPDATE citas SET estado='cancelada' WHERE id=?");
    $stmt->execute([$cita_id]);

    echo json_encode(['success'=>true, 'message'=>'Cita cancelada correctamente.']);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success'=>false, 'error'=>$e->getMessage()]);
}