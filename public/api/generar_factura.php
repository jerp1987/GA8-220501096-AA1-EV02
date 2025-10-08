<?php
require_once __DIR__ . '/../../src/config/conexion.php';
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success'=>false, 'error'=>'Método no permitido']);
        exit;
    }

    $in = json_decode(file_get_contents('php://input'), true);

    // Verifica que lleguen los campos requeridos
    $cita_id     = intval($in['cita_id'] ?? 0);
    $subtotal    = intval($in['subtotal'] ?? 0);
    $descripcion = trim($in['descripcion'] ?? '');
    $estado      = trim($in['estado'] ?? 'pendiente');

    if (!$cita_id || !$subtotal) {
        http_response_code(422);
        echo json_encode(['success'=>false, 'error'=>'Faltan datos requeridos (cita_id, subtotal)']);
        exit;
    }

    $iva   = intval($subtotal * 0.19);
    $total = $subtotal + $iva;
    $pdo = db();

    // 1. Verifica que la cita exista
    $stmt = $pdo->prepare("SELECT * FROM citas WHERE id=?");
    $stmt->execute([$cita_id]);
    $cita = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$cita) {
        http_response_code(404);
        echo json_encode(['success'=>false, 'error'=>'Cita no encontrada']);
        exit;
    }

    // 2. No permitir facturar citas canceladas
    if ($cita['estado'] === 'cancelada') {
        http_response_code(409);
        echo json_encode(['success'=>false, 'error'=>'No se puede facturar una cita cancelada.']);
        exit;
    }

    // 3. No permitir duplicados de factura por cita
    $stmt = $pdo->prepare("SELECT id FROM facturas WHERE cita_id=?");
    $stmt->execute([$cita_id]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['success'=>false, 'error'=>'Ya existe una factura para esta cita']);
        exit;
    }

    // 4. Buscar el usuario (cliente) por número de documento de la cita
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE identificacion = ?");
    $stmt->execute([$cita['numero_documento']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        http_response_code(409);
        echo json_encode(['success'=>false, 'error'=>'El cliente (usuario) no existe en usuarios, verifique la cita.']);
        exit;
    }
    $cliente_id = $usuario['id'];

    // 5. Crear la factura
    $stmt = $pdo->prepare("INSERT INTO facturas (cliente_id, cita_id, descripcion, subtotal, iva, total, estado) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$cliente_id, $cita_id, $descripcion, $subtotal, $iva, $total, $estado]);

    echo json_encode([
        'success' => true,
        'factura_id' => $pdo->lastInsertId(),
        'message' => 'Factura creada correctamente'
    ]);
    exit;

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success'=>false, 'error'=>$e->getMessage()]);
    exit;
}
?>