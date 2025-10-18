<?php
require_once __DIR__ . '/../../src/config/conexion.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$pdo = db();

// Trae los datos de la factura + cita asociada
$stmt = $pdo->prepare(
  "SELECT 
      f.id as factura_id, f.descripcion, f.subtotal, f.iva, f.total, f.estado as estado_factura,
      c.nombre, c.apellido, c.tipo_documento, c.numero_documento, c.correo, c.telefono, 
      c.placa_vehiculo, c.servicio, c.descripcion_adicional, c.fecha
   FROM facturas f
   LEFT JOIN citas c ON f.cita_id = c.id
   WHERE f.id = ?"
);
$stmt->execute([$id]);
$f = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$f) {
    // Responde igual en JSON o HTML si la factura no existe
    if (
        isset($_SERVER['HTTP_ACCEPT']) &&
        strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false
    ) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            "success" => false,
            "error" => "Factura no encontrada"
        ]);
        exit;
    } else {
        echo "Factura no encontrada";
        exit;
    }
}

// --- RESPUESTA EN JSON SI SE SOLICITA ---
if (
    isset($_SERVER['HTTP_ACCEPT']) &&
    strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false
) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        "success" => true,
        "factura" => [
            "id" => $f['factura_id'],
            "cliente" => $f['nombre'] . " " . $f['apellido'],
            "tipo_documento" => $f['tipo_documento'],
            "numero_documento" => $f['numero_documento'],
            "correo" => $f['correo'],
            "telefono" => $f['telefono'],
            "placa" => $f['placa_vehiculo'],
            "fecha_cita" => $f['fecha'],
            "servicio" => $f['servicio'],
            "detalle" => $f['descripcion_adicional'] ?: $f['descripcion'],
            "subtotal" => $f['subtotal'],
            "iva" => $f['iva'],
            "total" => $f['total'],
            "estado" => $f['estado_factura']
        ]
    ]);
    exit;
}

// --- RESPUESTA EN HTML PARA NAVEGADOR ---
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Factura #<?= $f['factura_id'] ?> - SECLICA</title>
  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      margin: 0;
      background: #f8f8f8;
    }
    .factura {
      border: 2.5px solid #14632D;
      padding: 32px 38px 22px 38px;
      max-width: 570px;
      margin: 32px auto;
      background: #fff;
      border-radius: 13px;
      box-shadow: 0 2px 16px #88b99144;
    }
    .factura-header {
      border-bottom: 1.5px solid #14632D;
      margin-bottom: 22px;
      padding-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 18px;
    }
    .seclica-logo {
      font-size: 2.2em;
      font-weight: 700;
      color: #14632D;
      letter-spacing: 2px;
    }
    .factura-title {
      font-size: 1.35em;
      font-weight: 600;
      color: #444;
      margin-top: 10px;
      margin-bottom: 20px;
    }
    .datos, .servicio {
      margin-bottom: 16px;
      font-size: 1.05em;
      width: 100%;
    }
    .datos td, .servicio td {
      padding: 3px 7px 3px 0;
    }
    .totales table {
      width: 100%;
      font-size: 1.1em;
      margin-top: 14px;
    }
    .right { text-align: right; }
    .estado {
      font-weight: bold;
      color: #14632D;
      text-transform: capitalize;
    }
    .total-final {
      font-size: 1.2em;
      font-weight: 700;
      color: #d63031;
    }
    .footer {
      margin-top: 28px;
      text-align: center;
      color: #888;
      font-size: 1em;
      border-top: 1px dashed #b5bab9;
      padding-top: 10px;
      letter-spacing: 1px;
    }
    @media print {
      .btn, .footer { display: none !important; }
      .factura { box-shadow: none !important; }
      body { background: #fff; }
    }
    .btn {
      display: inline-block;
      padding: 9px 30px;
      font-size: 1.07em;
      background: #14632D;
      color: #fff;
      border: none;
      border-radius: 7px;
      cursor: pointer;
      margin: 24px 0 0 0;
    }
  </style>
</head>
<body>
  <div class="factura">
    <div class="factura-header">
      <span class="seclica-logo">SECLICA</span>
      <span style="margin-left:auto;font-weight:600;color:#444;">
        Fecha: <?= date('Y-m-d') ?>
      </span>
    </div>
    <div class="factura-title">Factura #<?= $f['factura_id'] ?></div>
    
    <table class="datos">
      <tr><td><b>Cliente:</b></td>   <td><?= $f['nombre'] . " " . $f['apellido'] ?></td></tr>
      <tr><td><b>Doc.:</b></td>      <td><?= $f['tipo_documento'] . " " . $f['numero_documento'] ?></td></tr>
      <tr><td><b>Correo:</b></td>    <td><?= $f['correo'] ?></td></tr>
      <tr><td><b>Teléfono:</b></td>  <td><?= $f['telefono'] ?></td></tr>
      <tr><td><b>Placa:</b></td>     <td><?= $f['placa_vehiculo'] ?></td></tr>
      <tr><td><b>Fecha Cita:</b></td>     <td><?= $f['fecha'] ?></td></tr>
    </table>

    <table class="servicio">
      <tr><td><b>Servicio:</b></td>   <td><?= $f['servicio'] ?></td></tr>
      <tr><td><b>Detalle:</b></td>    <td><?= $f['descripcion_adicional'] ?: $f['descripcion'] ?></td></tr>
    </table>

    <div class="totales">
      <table>
        <tr>
          <td class="right"><b>Subtotal:</b></td>
          <td class="right">$<?= number_format($f['subtotal'],0,',','.') ?></td>
        </tr>
        <tr>
          <td class="right"><b>IVA:</b></td>
          <td class="right">$<?= number_format($f['iva'],0,',','.') ?></td>
        </tr>
        <tr>
          <td class="right"><b>Total:</b></td>
          <td class="right total-final">$<?= number_format($f['total'],0,',','.') ?></td>
        </tr>
        <tr>
          <td class="right"><b>Estado:</b></td>
          <td class="estado"><?= ucfirst($f['estado_factura']) ?></td>
        </tr>
      </table>
    </div>
    <button class="btn" onclick="window.print()">Imprimir</button>
    <div class="footer">
      Gracias por confiar en SECLICA - Centro de Servicio de Motocicletas
    </div>
  </div>
  <script>
    window.onload = () => window.print();
  </script>
</body>
</html>