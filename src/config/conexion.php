<?php
// src/config/conexion.php
declare(strict_types=1);

function db(): PDO {
  static $pdo = null;
  if ($pdo) return $pdo;

  $DB_HOST = '127.0.0.1';
  $DB_NAME = 'secilica_db'; // <-- Ajusta si tu BD tiene otro nombre
  $DB_USER = 'root';
  $DB_PASS = '';
  $charset = 'utf8mb4';

  $dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=$charset";
  $opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
  ];
  $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $opt);
  return $pdo;
}
?>