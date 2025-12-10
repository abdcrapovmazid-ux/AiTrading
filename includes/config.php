<?php
// Настройки базы данных
define('DB_HOST', 'localhost');
define('DB_NAME', 'aitrading');
define('DB_USER', 'root');
define('DB_PASS', '');

// Настройки приложения
define('SITE_NAME', 'AiTrading');
define('SITE_URL', 'http://localhost/aitrading/');

// Подключение к базе данных
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

// Включаем вывод ошибок (отключить на продакшене)
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
