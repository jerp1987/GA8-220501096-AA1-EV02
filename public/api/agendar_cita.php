<?php
declare(strict_types=1);

session_start();
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

    // Solo usuarios logueados pueden agendar
    if (empty($_SESSION['usuario']) || empty($_SESSION['usuario']['identificacion'])) {
        http_response_code(401);
        echo json_encode(['success'=>false, 'error'=>'Debes iniciar sesión para agendar una cita.']);
        exit;
    }

    // Cargamos datos desde sesión de usuario logueado
    $usuario = $_SESSION['usuario'];
    $nombre    = $usuario['nombre'];
    $apellido  = $usuario['apellido'];
    $tipo_doc  = $usuario['tipo_documento'];
    $num_doc   = $usuario['identificacion'];  // este es el número de documento real
    $correo    = $usuario['email'];
    $telefono  = $usuario['telefono'] ?? "";

    // Solo se toman del form los campos de la cita
    $in = body_json();
    $fecha       = isset($in['fecha']) ? trim($in['fecha']) : "";
    $placa       = isset($in['placa_vehiculo']) ? strtoupper(trim($in['placa_vehiculo'])) : "";
    $servicio    = isset($in['servicio']) ? trim($in['servicio']) : "";
    $descripcion = isset($in['descripcion_adicional']) ? trim($in['descripcion_adicional']) : "";

    // Validar campos obligatorios
    if (!$fecha || !$placa || !$servicio) {
        http_response_code(422);
        echo json_encode(['success'=>false, 'error'=>'Faltan campos obligatorios para agendar la cita.']);
        exit;
    }
    // Validar formato de fecha (YYYY-MM-DD)
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
        http_response_code(422);
        echo json_encode(['success'=>false, 'error'=>'El formato de fecha debe ser YYYY-MM-DD']);
        exit;
    }

    $pdo = db();
    $pdo->beginTransaction();

    // 1. Validar que el usuario exista en la tabla usuarios (por identificacion)
    $sqlUser = "SELECT id FROM usuarios WHERE identificacion=? LIMIT 1";
    $stmt = $pdo->prepare($sqlUser);
    $stmt->execute([$num_doc]);
    $userDb = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$userDb) {
        $pdo->rollBack();
        http_response_code(401);
        echo json_encode([
            'success'=>false,
            'error'=>'Tu usuario no existe en la base de datos. Regístrate nuevamente.'
        ]);
        exit;
    }
    $usuario_id = $userDb['id'];

    // 2. Verificar si ya existe una cita con esos datos
    $sqlCheck = "SELECT id FROM citas WHERE numero_documento=? AND fecha=? AND servicio=?";
    $stmt = $pdo->prepare($sqlCheck);
    $stmt->execute([$num_doc, $fecha, $servicio]);
    if ($stmt->fetch()) {
        $pdo->rollBack();
        http_response_code(409);
        echo json_encode(['success'=>false, 'error'=>'Ya existe una cita para este servicio y fecha.']);
        exit;
    }

    // 3. Guardar la cita
    $sqlCita = "INSERT INTO citas (
        nombre, apellido, tipo_documento, numero_documento, correo, telefono, fecha, placa_vehiculo, servicio, descripcion_adicional, estado, created_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pendiente', NOW())";
    $stmt = $pdo->prepare($sqlCita);
    $stmt->execute([
        $nombre, $apellido, $tipo_doc, $num_doc, $correo, $telefono, $fecha, $placa, $servicio, $descripcion
    ]);
    $cita_id = $pdo->lastInsertId();

    $pdo->commit();

    echo json_encode([
        'success'   => true,
        'msg'       => 'Cita agendada correctamente.',
        'usuario_id'=> $usuario_id,
        'cita_id'   => $cita_id,
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    if (!empty($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success'=>false, 'error'=>'Error interno, inténtalo de nuevo más tarde.']);
}