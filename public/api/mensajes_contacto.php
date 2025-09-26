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

    // Consulta con LEFT JOIN para traer también la respuesta (si existe)
    $sql = "SELECT 
                m.id,
                m.nombre,
                m.correo,
                m.telefono,
                m.mensaje,
                m.fecha_envio,
                r.respuesta,
                r.fecha_respuesta,
                r.respondido_por
            FROM mensajes_contacto m
            LEFT JOIN respuestas_contacto r ON m.id = r.mensaje_id
            ORDER BY m.fecha_envio DESC, m.id DESC";
    $stmt = $pdo->query($sql);
    $mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $mensajes
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success'=>false, 'error'=>$e->getMessage()]);
}