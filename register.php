<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Валидация
    $errors = [];
    
    if(empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = 'empty_fields';
    }
    
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'invalid_email';
    }
    
    if(strlen($password) < 6) {
        $errors[] = 'short_password';
    }
    
    if($password !== $confirm_password) {
        $errors[] = 'password_mismatch';
    }
    
    // Проверка существования email
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if($stmt->fetch()) {
        $errors[] = 'email_exists';
    }
    
    if(empty($errors)) {
        // Хэширование пароля
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Создание API ключа
        $api_key = generateApiKey();
        
        // Вставка в базу данных
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, api_key, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$name, $email, $hashed_password, $api_key]);
        
        // Получаем ID нового пользователя
        $user_id = $pdo->lastInsertId();
        
        // Автоматический вход после регистрации
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        $_SESSION['api_key'] = $api_key;
        
        // Редирект на dashboard
        header("Location: dashboard.php?registration=success");
        exit();
    } else {
        // Возвращаем с ошибкой
        $error_string = implode('&', array_map(function($error) {
            return 'register_error[]=' . $error;
        }, $errors));
        header("Location: index.php?" . $error_string);
        exit();
    }
} else {
    // Если не POST запрос, перенаправляем на главную
    header("Location: index.php");
    exit();
}
?>
