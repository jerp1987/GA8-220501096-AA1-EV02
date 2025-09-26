<?php
require_once "conexion.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data || !$data['factura_id'] || !$data['monto'] || !$data['metodo']) {
        http_response_code(400);
        echo json_encode(['success'=>false, 'error'=>'Faltan datos del pago']);
        exit;
    }
    // Registrar pago (tabla pagos opcional)
    $stmt = db()->prepare("INSERT INTO pagos (factura_id, monto, metodo, fecha)
                           VALUES (?, ?, ?, NOW())");
    $stmt->execute([$data['factura_id'], $data['monto'], $data['metodo']]);

    // Marcar factura como pagada
    $update = db()->prepare("UPDATE facturas SET estado='Pagado' WHERE id=?");
    $update->execute([$data['factura_id']]);

    echo json_encode(['success'=>true, 'message'=>'Pago registrado']);
    exit;
}

echo json_encode(['success'=>false, 'error'=>'Método no permitido']);
