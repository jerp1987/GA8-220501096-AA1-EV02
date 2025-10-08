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

// Si el logout se hace desde un fetch, puedes devolver JSON, pero si es por navegador, redirecciona
if (php_sapi_name() === 'cli' || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
    echo json_encode(['success'=>true, 'message'=>'Sesión cerrada']);
    exit;
} else {
    header("Location: /SECLICA/public/site/frontend/index.html");
    exit;
}
?>
