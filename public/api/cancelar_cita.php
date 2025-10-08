<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../src/config/conexion.php';
session_start();

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

    // Puedes obtener el usuario que cancela si usas sesión/login de empleado:
    $cancelado_por = $_SESSION['user_name'] ?? 'empleado';

    $pdo = db();
    $pdo->beginTransaction();

    // 1. Verifica que la cita exista y esté pendiente
    $stmt = $pdo->prepare("SELECT * FROM citas WHERE id=? AND estado='pendiente'");
    $stmt->execute([$cita_id]);
    $cita = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cita) {
        $pdo->rollBack();
        http_response_code(404);
        echo json_encode(['success'=>false, 'error'=>'La cita no existe o ya está cancelada.']);
        exit;
    }

    // 2. Verifica que NO exista factura asociada
    $stmt = $pdo->prepare("SELECT id FROM facturas WHERE cita_id=?");
    $stmt->execute([$cita_id]);
    if ($stmt->fetch()) {
        $pdo->rollBack();
        http_response_code(403);
        echo json_encode(['success'=>false, 'error'=>'No se puede cancelar: la cita ya tiene una factura asociada.']);
        exit;
    }

    // 3. Registra la cancelación con fecha y usuario que la realizó
    $stmt = $pdo->prepare("INSERT INTO cancelaciones (cita_id, motivo, cancelado_por, fecha_cancelacion) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$cita_id, $motivo, $cancelado_por]);

    // 4. Cambia el estado de la cita a 'cancelada'
    $stmt = $pdo->prepare("UPDATE citas SET estado='cancelada' WHERE id=?");
    $stmt->execute([$cita_id]);

    $pdo->commit();

    echo json_encode(['success'=>true, 'message'=>'Cita cancelada correctamente.']);

} catch (Throwable $e) {
    if (!empty($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    // Puedes registrar el error con: error_log($e->getMessage());
    http_response_code(500);
    echo json_encode(['success'=>false, 'error'=>'Error interno, inténtalo de nuevo más tarde.']);
}