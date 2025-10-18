<?php
// SECLICA/public/api/logout.php
session_start();
$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

// Detecta si espera JSON por el header Accept (Postman, fetch, etc)
$accept = $_SERVER['HTTP_ACCEPT'] ?? '';
$is_json = stripos($accept, 'application/json') !== false;

if ($is_json) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success'=>true, 'message'=>'Sesión cerrada correctamente.']);
    exit;
} else {
    // Redirección para navegación normal (desde un enlace/botón de la web)
    header("Location: /SECLICA/public/site/frontend/index.html");
    exit;
}
?>