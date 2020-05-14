<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL|E_STRICT);
$host           = 'localhost';
$db_name        = 'invoice';
$db_username    = 'root';
$db_password    = 'root';
$dsn            = 'mysql:host='. $host .';dbname='. $db_name;
$options        = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];
try {
    $pdo = new PDO($dsn, $db_username, $db_password);
} catch (PDOException $e) {
    exit($e->getMessage());
}
