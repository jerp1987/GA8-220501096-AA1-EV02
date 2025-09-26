<?php 
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../src/config/conexion.php';

// Función para leer el body JSON
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

    // Validar campos requeridos (mismos del HTML y la tabla)
    $requeridos = [
        'nombre', 'apellido', 'tipo_documento', 'numero_documento',
        'correo', 'telefono', 'fecha', 'placa_vehiculo', 'servicio'
    ];
    foreach ($requeridos as $campo) {
        if (empty($in[$campo])) {
            http_response_code(422);
            echo json_encode(['success'=>false, 'error'=>"Falta el campo: $campo"]);
            exit;
        }
    }

    // Limpieza de datos
    $nombre    = trim($in['nombre']);
    $apellido  = trim($in['apellido']);
    $tipo_doc  = trim($in['tipo_documento']);
    $num_doc   = trim($in['numero_documento']);
    $correo    = strtolower(trim($in['correo']));
    $telefono  = trim($in['telefono']);
    $fecha     = trim($in['fecha']);
    $placa     = strtoupper(trim($in['placa_vehiculo']));
    $servicio  = trim($in['servicio']); // Aquí es texto, ej: "Sincronización"
    $descripcion = isset($in['descripcion_adicional']) ? trim($in['descripcion_adicional']) : "";

    $pdo = db();

    // Evitar duplicados: misma persona, fecha y servicio
    $stmt = $pdo->prepare("SELECT id FROM citas WHERE numero_documento=? AND fecha=? AND servicio=?");
    $stmt->execute([$num_doc, $fecha, $servicio]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['success'=>false, 'error'=>'Ya existe una cita para esta persona, servicio y fecha']);
        exit;
    }

    // Guardar la cita
    $sql = "INSERT INTO citas
        (nombre, apellido, tipo_documento, numero_documento, correo, telefono, fecha, placa_vehiculo, servicio, descripcion_adicional, estado, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pendiente', NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $nombre, $apellido, $tipo_doc, $num_doc, $correo, $telefono, $fecha, $placa, $servicio, $descripcion
    ]);

    $cita_id = $pdo->lastInsertId();

    // --- CREAR LA FACTURA AUTOMÁTICAMENTE ---
    // Asigna los valores predeterminados (ajusta a tus reglas)
    $subtotal = 80000; // O asigna según tipo de servicio
    $iva      = intval($subtotal * 0.19); // 19% (ajusta el porcentaje si es diferente)
    $total    = $subtotal + $iva;
    $estado   = 'pendiente'; // O "pagada" si así lo requieres

    $sqlf = "INSERT INTO facturas
        (cliente_id, cita_id, descripcion, subtotal, iva, total, estado)
        VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmtf = $pdo->prepare($sqlf);
    $stmtf->execute([
        $cita_id,        // cliente_id ahora es el ID de la cita (relación 1:1)
        $cita_id,        // cita_id en la factura (relación directa)
        $servicio,       // descripción
        $subtotal,       // ajusta si tienes lógica para diferentes servicios
        $iva,
        $total,
        $estado
    ]);

    echo json_encode([
        'success'   => true,
        'cita_id'   => $cita_id,
        'factura_id'=> $pdo->lastInsertId(),
        'nombre'    => $nombre,
        'fecha'     => $fecha
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success'=>false, 'error'=>$e->getMessage()]);
}