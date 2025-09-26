<?php
require_once "conexion.php";
header('Content-Type: application/json');

// --- Listar servicios ---
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT id, nombre, descripcion FROM servicios ORDER BY nombre";
    $stmt = db()->query($sql);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

// --- Crear servicio (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data || !$data['nombre']) {
        http_response_code(400);
        echo json_encode(['success'=>false, 'error'=>'Nombre es obligatorio']);
        exit;
    }
    $stmt = db()->prepare("INSERT INTO servicios (nombre, descripcion) VALUES (?, ?)");
    $stmt->execute([$data['nombre'], $data['descripcion'] ?? '']);
    echo json_encode(['success'=>true, 'message'=>'Servicio registrado']);
    exit;
}

echo json_encode(['success'=>false, 'error'=>'Método no permitido']);
