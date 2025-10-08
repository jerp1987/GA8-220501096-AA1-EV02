<?php
declare(strict_types=1);
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../src/config/conexion.php';

function json_error($msg, $code = 400) {
  http_response_code($code);
  echo json_encode(['success'=>false, 'error'=>$msg]);
  exit;
}

try {
  if ($_SERVER['REQUEST_METHOD'] !== 'POST')
    json_error('Método no permitido', 405);

  $in = json_decode(file_get_contents("php://input"), true);
  if (!$in) json_error('Datos no recibidos o mal formato JSON.');

  $email = strtolower(trim($in['email'] ?? ''));
  $password = $in['password'] ?? '';
  $rol = strtolower(trim($in['rol'] ?? ''));

  if (!$email || !$password) json_error('Correo y contraseña requeridos.');

  $pdo = db();
  $q = $pdo->prepare(
    "SELECT id, nombre, apellido, tipo_documento, identificacion, email, telefono, password_hash, rol, estado 
     FROM usuarios WHERE email=? LIMIT 1"
  );
  $q->execute([$email]);
  $user = $q->fetch(PDO::FETCH_ASSOC);

  if (!$user || !password_verify($password, $user['password_hash'])) {
    json_error('Credenciales inválidas.', 401);
  }

  if ($user['estado'] !== 'activo') {
    json_error('Usuario inactivo.', 403);
  }

  if ($rol && $user['rol'] !== $rol) {
    json_error('Sin permisos para este módulo.', 403);
  }

  // ===== CORRECTO: GUARDAR TODO EN $_SESSION['usuario'] =====
  $_SESSION['usuario'] = [
      'id'              => $user['id'],
      'nombre'          => $user['nombre'],
      'apellido'        => $user['apellido'],
      'tipo_documento'  => $user['tipo_documento'] ?? '',
      'identificacion'  => $user['identificacion'] ?? '',
      'numero_documento'=> $user['identificacion'] ?? '',
      'email'           => $user['email'],
      'telefono'        => $user['telefono'],
      'rol'             => $user['rol'],
      'estado'          => $user['estado'],
  ];

  unset($user['password_hash']);

  echo json_encode([
    'success'=>true,
    'usuario'=>$_SESSION['usuario'],
    'rol'=>$_SESSION['usuario']['rol'],
    'id'=>$_SESSION['usuario']['id'],
  ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['success'=>false, 'error'=>$e->getMessage()]);
}