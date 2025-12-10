<?php
// Генерация API ключа
function generateApiKey($length = 32) {
    return bin2hex(random_bytes($length));
}

// Проверка авторизации
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Редирект если не авторизован
function requireLogin() {
    if(!isLoggedIn()) {
        header("Location: index.php");
        exit();
    }
}

// Редирект если авторизован
function requireGuest() {
    if(isLoggedIn()) {
        header("Location: dashboard.php");
        exit();
    }
}

// Защита от XSS
function sanitize($input) {
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

// Форматирование даты
function formatDate($date) {
    return date('d.m.Y H:i', strtotime($date));
}

// Генерация CSRF токена
function generateCsrfToken() {
    if(empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Проверка CSRF токена
function verifyCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Отправка email
function sendEmail($to, $subject, $message) {
    $headers = "From: noreply@" . parse_url(SITE_URL, PHP_URL_HOST) . "\r\n";
    $headers .= "Reply-To: support@" . parse_url(SITE_URL, PHP_URL_HOST) . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    return mail($to, $subject, $message, $headers);
}

// Получение IP пользователя
function getUserIP() {
    if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

// Логирование действий
function logActivity($user_id, $action, $details = '') {
    global $pdo;
    
    $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, details, ip_address, created_at) 
                          VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$user_id, $action, $details, getUserIP()]);
}
?>
