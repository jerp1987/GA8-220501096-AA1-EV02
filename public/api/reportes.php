<?php
// public/api/reportes.php
require_once __DIR__ . '/../../src/config/conexion.php';

// Parámetros
$tipo = $_GET['tipo'] ?? '';
$formato = $_GET['formato'] ?? 'excel';

header("Access-Control-Allow-Origin: *");

// Helper para descarga
function salidaDescarga($filename, $content, $mime) {
    header("Content-Type: $mime");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    echo $content;
    exit;
}

$pdo = db();
$rows = [];
$headers = [];

// === Reporte de USUARIOS ===
if ($tipo === "usuarios") {
    $query = $pdo->query("SELECT id, nombre, apellido, tipo_documento, identificacion, email, telefono, created_at, estado, rol FROM usuarios ORDER BY id ASC");
    $rows = $query->fetchAll(PDO::FETCH_ASSOC);
    $headers = ['ID', 'Nombre', 'Apellido', 'Tipo Documento', 'Identificación', 'Email', 'Teléfono', 'Fecha Registro', 'Estado', 'Rol'];

// === Reporte de CITAS ===
} elseif ($tipo === "citas") {
    $query = $pdo->query("SELECT id, nombre, apellido, tipo_documento, numero_documento, correo, telefono, fecha, placa_vehiculo, servicio, descripcion_adicional, estado, created_at FROM citas ORDER BY id ASC");
    $rows = $query->fetchAll(PDO::FETCH_ASSOC);
    $headers = ['ID', 'Nombre', 'Apellido', 'Tipo Documento', 'Número Documento', 'Correo', 'Teléfono', 'Fecha', 'Placa Vehículo', 'Servicio', 'Descripción Adicional', 'Estado', 'Fecha Registro'];

// === Reporte de FACTURAS ===
} elseif ($tipo === "facturas") {
    $query = $pdo->query("SELECT id, cliente_id, descripcion, subtotal, iva, total, estado FROM facturas ORDER BY id ASC");
    $rows = $query->fetchAll(PDO::FETCH_ASSOC);
    $headers = ['ID', 'ID Cliente', 'Descripción', 'Subtotal', 'IVA', 'Total', 'Estado'];

} else {
    http_response_code(400);
    echo "Tipo de reporte no soportado.";
    exit;
}

// ---- Generar Excel (CSV) ----
if ($formato === "excel") {
    $csv = implode(";", $headers) . "\n";
    foreach ($rows as $row) {
        $csv .= implode(";", array_map(
            fn($v) => is_numeric($v) ? $v : iconv("UTF-8", "ISO-8859-1//TRANSLIT", $v), 
            $row
        )) . "\n";
    }
    salidaDescarga("reporte_{$tipo}_" . date("Ymd_His") . ".csv", $csv, "text/csv");
    exit;
}

// ---- Vista previa para tabla (AJAX, sin descargar) ----
if ($formato === "vista" || $formato === "preview") {
    // Solo genera el CSV "virtual" para mostrarlo en la tabla (no descarga)
    $csv = implode(";", $headers) . "\n";
    foreach ($rows as $row) {
        $csv .= implode(";", array_map(
            fn($v) => is_numeric($v) ? $v : iconv("UTF-8", "ISO-8859-1//TRANSLIT", $v), 
            $row
        )) . "\n";
    }
    echo $csv;
    exit;
}

// --- Si alguien pide PDF ---
if ($formato === "pdf") {
    echo "⚠️ Función PDF deshabilitada temporalmente.<br>Por favor, descargue el reporte en formato Excel (CSV).";
    exit;
}

// Si no es ninguno de los dos formatos
http_response_code(400);
echo "Formato no soportado.";
exit;