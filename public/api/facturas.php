<?php
require_once __DIR__ . '/../../src/config/conexion.php';
header('Content-Type: application/json');
session_start();

$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // AHORA también traemos el campo cita_id
    $sql = "SELECT 
                f.id,
                f.cliente_id,
                f.cita_id,           -- <- Nuevo campo aquí
                f.descripcion,
                f.subtotal,
                f.iva,
                f.total,
                f.estado
            FROM facturas f
            ORDER BY f.id DESC";
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode([
        'success' => true,
        'data' => $rows
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode(['success'=>false, 'error'=>'Método no permitido']);
exit;