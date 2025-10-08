<?php
declare(strict_types=1);

session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../src/config/conexion.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(['success'=>false, 'error'=>'Método no soportado']);
        exit;
    }

    // Validar sesión y rol
    if (empty($_SESSION['usuario']) || empty($_SESSION['usuario']['rol'])) {
        http_response_code(401);
        echo json_encode(['success'=>false, 'error'=>'No autenticado']);
        exit;
    }

    $usuario = $_SESSION['usuario'];
    $pdo = db();

    // Siempre devolver los mismos campos y en el mismo orden para todos los roles
    $fields = "
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
    ";

    // ADMIN o EMPLEADO: pueden ver todas las citas
    if (in_array($usuario['rol'], ['admin', 'empleado'])) {
        $sql = "SELECT $fields FROM citas ORDER BY fecha DESC, id DESC";
        $stmt = $pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success'=>true, 'data'=>$rows], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // CLIENTE: solo puede ver sus propias citas
    if ($usuario['rol'] === 'cliente') {
        // OJO: aquí debe venir la identificación correcta según cómo se guarda en la sesión
        $identificacion = $usuario['identificacion'] ?? $usuario['numero_documento'] ?? null;
        if (!$identificacion) {
            http_response_code(400);
            echo json_encode(['success'=>false, 'error'=>'Identificación de usuario no encontrada']);
            exit;
        }
        $sql = "SELECT $fields FROM citas WHERE numero_documento = ? ORDER BY fecha DESC, id DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$identificacion]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success'=>true, 'data'=>$rows], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Rol no válido
    http_response_code(403);
    echo json_encode(['success'=>false, 'error'=>'No autorizado']);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success'=>false, 'error'=>$e->getMessage()]);
}