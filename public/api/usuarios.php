<?php
// /SECLICA/public/api/usuarios.php

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../src/config/conexion.php';
$pdo = db();

// Función para obtener datos de la petición, compatible con JSON y form-data
function getRequestData() {
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (stripos($contentType, 'application/json') !== false) {
        $data = json_decode(file_get_contents("php://input"), true);
        return is_array($data) ? $data : [];
    } else {
        // Para x-www-form-urlencoded o form-data (HTML forms)
        parse_str(file_get_contents("php://input"), $vars);
        return !empty($vars) ? $vars : $_POST;
    }
}

switch ($_SERVER['REQUEST_METHOD']) {

    // 1. LISTAR USUARIOS (con filtro por rol opcional)
    case 'GET':
        $rol = isset($_GET['rol']) ? trim($_GET['rol']) : '';
        $sql = "SELECT id, nombre, apellido, tipo_documento, identificacion, email, telefono, rol, estado, created_at FROM usuarios";
        $params = [];
        if ($rol && strtolower($rol) !== 'todos') {
            $sql .= " WHERE rol = ?";
            $params[] = $rol;
        }
        $sql .= " ORDER BY id DESC";
        $q = $pdo->prepare($sql);
        $q->execute($params);
        echo json_encode(['success' => true, 'data' => $q->fetchAll(PDO::FETCH_ASSOC)]);
        break;

    // 2. REGISTRAR USUARIO
    case 'POST':
        $data = getRequestData();

        // Validación de campos requeridos
        $required = ['nombre','apellido','tipo_documento','identificacion','telefono','email','password','rol'];
        foreach($required as $k) {
            if (!isset($data[$k]) || trim($data[$k]) === '') {
                http_response_code(400);
                echo json_encode(['success'=>false, 'error'=>"Falta el campo: $k"]);
                exit;
            }
        }

        // Validar email único
        $existeEmail = $pdo->prepare("SELECT id FROM usuarios WHERE email=?");
        $existeEmail->execute([$data['email']]);
        if ($existeEmail->fetch()) {
            http_response_code(409);
            echo json_encode(['success'=>false, 'error'=>'El correo electrónico ya está registrado']);
            exit;
        }

        // Validar documento único
        $docUnico = $pdo->prepare("SELECT id FROM usuarios WHERE tipo_documento=? AND identificacion=?");
        $docUnico->execute([$data['tipo_documento'], $data['identificacion']]);
        if ($docUnico->fetch()) {
            http_response_code(409);
            echo json_encode(['success'=>false, 'error'=>'El documento ya está registrado']);
            exit;
        }

        // Intentar insertar y capturar errores
        try {
            $hash = password_hash($data['password'], PASSWORD_DEFAULT);
            $s = $pdo->prepare("INSERT INTO usuarios 
                (nombre, apellido, tipo_documento, identificacion, telefono, email, password_hash, rol, estado, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'activo', NOW())");
            $s->execute([
                $data['nombre'],
                $data['apellido'],
                $data['tipo_documento'],
                $data['identificacion'],
                $data['telefono'],
                $data['email'],
                $hash,
                $data['rol']
            ]);
            echo json_encode(['success'=>true, 'message'=>'Usuario creado exitosamente']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['success'=>false, 'error'=>'No se pudo registrar el usuario. Intente más tarde.']);
        }
        break;

    // 3. ACTUALIZAR USUARIO
    case 'PUT':
        $data = getRequestData();
        if (
            !isset($data['id']) || !$data['id'] ||
            !isset($data['nombre']) || !$data['nombre'] ||
            !isset($data['apellido']) || !$data['apellido'] ||
            !isset($data['tipo_documento']) || !$data['tipo_documento'] ||
            !isset($data['identificacion']) || !$data['identificacion'] ||
            !isset($data['telefono']) || !$data['telefono'] ||
            !isset($data['email']) || !$data['email'] ||
            !isset($data['rol']) || !$data['rol']
        ) {
            http_response_code(400);
            echo json_encode(['success'=>false, 'error'=>'Faltan campos obligatorios']);
            exit;
        }
        // ¿Cambió el correo? Validar único
        $check = $pdo->prepare("SELECT id FROM usuarios WHERE email=? AND id<>?");
        $check->execute([$data['email'], $data['id']]);
        if ($check->fetch()) {
            http_response_code(409);
            echo json_encode(['success'=>false, 'error'=>'El correo electrónico ya está en uso']);
            exit;
        }
        // ¿Cambió el documento? Validar único
        $checkDoc = $pdo->prepare("SELECT id FROM usuarios WHERE tipo_documento=? AND identificacion=? AND id<>?");
        $checkDoc->execute([$data['tipo_documento'], $data['identificacion'], $data['id']]);
        if ($checkDoc->fetch()) {
            http_response_code(409);
            echo json_encode(['success'=>false, 'error'=>'El documento ya está en uso']);
            exit;
        }
        // Si viene password, actualiza, si no, déjala igual
        if (!empty($data['password'])) {
            $hash = password_hash($data['password'], PASSWORD_DEFAULT);
            $s = $pdo->prepare("UPDATE usuarios SET nombre=?, apellido=?, tipo_documento=?, identificacion=?, telefono=?, email=?, password_hash=?, rol=? WHERE id=?");
            $s->execute([
                $data['nombre'],
                $data['apellido'],
                $data['tipo_documento'],
                $data['identificacion'],
                $data['telefono'],
                $data['email'],
                $hash,
                $data['rol'],
                $data['id']
            ]);
        } else {
            $s = $pdo->prepare("UPDATE usuarios SET nombre=?, apellido=?, tipo_documento=?, identificacion=?, telefono=?, email=?, rol=? WHERE id=?");
            $s->execute([
                $data['nombre'],
                $data['apellido'],
                $data['tipo_documento'],
                $data['identificacion'],
                $data['telefono'],
                $data['email'],
                $data['rol'],
                $data['id']
            ]);
        }
        echo json_encode(['success'=>true, 'message'=>'Usuario actualizado']);
        break;

    // 4. ELIMINAR USUARIO
    case 'DELETE':
        $data = getRequestData();
        $id = $data['id'] ?? null;

        if (!$id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'ID no proporcionado']);
            exit;
        }

        // ¿El usuario existe?
        $userExists = $pdo->prepare("SELECT id FROM usuarios WHERE id=?");
        $userExists->execute([$id]);
        if (!$userExists->fetch()) {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Usuario no existe']);
            exit;
        }

        // --------- NO eliminar si tiene facturas asociadas ---------
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM facturas WHERE cliente_id=?");
        $stmt->execute([$id]);
        $facturas = $stmt->fetchColumn();
        if ($facturas > 0) {
            http_response_code(409);
            echo json_encode(['success'=>false, 'error'=>'No se puede eliminar el usuario porque tiene facturas asociadas.']);
            exit;
        }

        // Si no hay facturas, elimina el usuario
        $s = $pdo->prepare("DELETE FROM usuarios WHERE id=?");
        $s->execute([$id]);
        echo json_encode(['success'=>true, 'message'=>'Usuario eliminado']);
        break;

    default:
        http_response_code(405);
        echo json_encode(['success'=>false, 'error'=>'Método no soportado']);
        break;
}
?>