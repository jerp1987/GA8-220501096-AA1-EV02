<?php
require_once __DIR__ . '/../../src/config/conexion.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success'=>false, 'error'=>'Método no permitido']);
    exit;
}

$in = json_decode(file_get_contents('php://input'), true);

$cita_id     = intval($in['cita_id'] ?? 0);
$subtotal    = intval($in['subtotal'] ?? 0);
$descripcion = trim($in['descripcion'] ?? '');
$estado      = trim($in['estado'] ?? 'pendiente');
$iva         = intval($subtotal * 0.19);
$total       = $subtotal + $iva;

if (!$cita_id || !$subtotal) {
    echo json_encode(['success'=>false, 'error'=>'Faltan datos requeridos']);
    exit;
}

$pdo = db();

// Verifica que la cita exista
$stmt = $pdo->prepare("SELECT * FROM citas WHERE id=?");
$stmt->execute([$cita_id]);
$cita = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$cita) {
    echo json_encode(['success'=>false, 'error'=>'Cita no encontrada']);
    exit;
}

// Crear la factura
$stmt = $pdo->prepare("INSERT INTO facturas (cliente_id, cita_id, descripcion, subtotal, iva, total, estado) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([$cita_id, $cita_id, $descripcion, $subtotal, $iva, $total, $estado]);

echo json_encode(['success'=>true, 'factura_id'=>$pdo->lastInsertId()]);
exit;
?>