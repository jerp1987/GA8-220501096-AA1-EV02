<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../src/config/conexion.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(['success'=>false, 'error'=>'Método no soportado']);
        exit;
    }

    $pdo = db();

    // Traer todos los campos de la tabla citas
    $sql = "SELECT 
        id, 
        nombre,
        apellido,
        tipo_documento,
        numero_documento,
        correo,
        telefono,
        fecha,
        placa_vehiculo,
        servicio,
        descripcion_adicional,
        estado,
        created_at
      FROM citas
      ORDER BY fecha DESC, id DESC";
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data'    => $rows
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success'=>false, 'error'=>$e->getMessage()]);
}
