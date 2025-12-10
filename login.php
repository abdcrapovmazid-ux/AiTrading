<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Валидация
    $errors = [];
    
    if(empty($email) || empty($password)) {
        $errors[] = 'empty_fields';
    }
    
    if(empty($errors)) {
        // Поиск пользователя
        $stmt = $pdo->prepare("SELECT id, name, email, password, api_key FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if($user && password_verify($password, $user['password'])) {
            // Успешный вход
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['api_key'] = $user['api_key'];
            
            // Обновляем время последнего входа
            $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
            $stmt->execute([$user['id']]);
            
            // Редирект на dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            // Неверные данные
            header("Location: index.php?login_error=invalid_credentials");
            exit();
        }
    } else {
        header("Location: index.php?login_error=empty_fields");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>
