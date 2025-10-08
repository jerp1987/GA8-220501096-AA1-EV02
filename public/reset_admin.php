<?php
require_once(__DIR__ . '/../api/login.php'); // CORREGIDO

$nuevaClave = 'admin1234'; // Nueva clave
$hash = password_hash($nuevaClave, PASSWORD_DEFAULT);

$sql = "UPDATE usuarios SET password_hash='$hash' WHERE email='admin@secllica.com' OR rol='admin' LIMIT 1";

if($conn->query($sql)) {
    echo "Contraseña restablecida correctamente a: $nuevaClave";
} else {
    echo "Error al actualizar: " . $conn->error;
}
?>
