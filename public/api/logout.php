<?php
// SECLICA/public/api/logout.php
session_start();
session_unset();
session_destroy();

// Redireccionar a la página de inicio del sistema (ajusta la ruta si tu index.html cambia de lugar)
header("Location: /SECLICA/public/site/frontend/index.html");
exit;
?>