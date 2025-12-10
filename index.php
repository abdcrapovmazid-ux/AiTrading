<?php
session_start();
// Если пользователь уже авторизован, перенаправляем в dashboard
if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AiTrading - Главная</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Экран загрузки -->
    <div class="loading-screen" id="loadingScreen">
        <div class="logo-container">
            <img src="images/logo.png" alt="AiTrading Logo" class="logo" id="appLogo">
            <h1 class="loading-title">AiTrading</h1>
            <p class="loading-subtitle">AI-Powered Trading Platform</p>
        </div>
        
        <div class="loading-bar-container">
            <div class="loading-bar" id="loadingBar"></div>
        </div>
        
        <div class="loading-text" id="loadingText">
            Инициализация системы...
        </div>
    </div>

    <!-- Основной контент -->
    <div class="app-content" id="appContent">
        <div class="container">
            <header class="header">
                <div class="logo-header">
                    <img src="images/logo.png" alt="AiTrading Logo" class="logo-small">
                    <h1 class="app-title">AiTrading</h1>
                </div>
                <p class="app-subtitle">Мощная AI-платформа для автоматической торговли на финансовых рынках</p>
            </header>

            <main class="main-content">
                <!-- Форма входа/регистрации -->
                <div class="auth-forms">
                    <div class="auth-tabs">
                        <button class="auth-tab active" id="loginTab">Вход</button>
                        <button class="auth-tab" id="registerTab">Регистрация</button>
                    </div>
                    
                    <!-- Форма входа -->
                    <div class="auth-form active" id="loginForm">
                        <h2 class="form-title">Вход в аккаунт</h2>
                        <?php if(isset($_GET['login_error'])): ?>
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php 
                            $error = $_GET['login_error'];
                            if($error == 'invalid_credentials') echo 'Неверный email или пароль';
                            elseif($error == 'empty_fields') echo 'Заполните все поля';
                            else echo 'Ошибка входа';
                            ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(isset($_GET['success'])): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            Аккаунт успешно создан! Теперь войдите в систему.
                        </div>
                        <?php endif; ?>
                        
                        <form action="login.php" method="POST" id="loginFormElement">
                            <div class="form-group">
                                <label for="loginEmail" class="form-label">Email адрес</label>
                                <input type="email" id="loginEmail" name="email" class="form-input" placeholder="example@mail.com" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="loginPassword" class="form-label">Пароль</label>
                                <div class="password-input-container">
                                    <input type="password" id="loginPassword" name="password" class="form-input" placeholder="Введите пароль" required>
                                    <button type="button" class="password-toggle">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="form-options">
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="remember"> Запомнить меня
                                    </label>
                                    <a href="forgot-password.php" class="forgot-password">Забыли пароль?</a>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-sign-in-alt"></i> Войти
                                </button>
                            </div>
                            
                            <div class="form-group">
                                <a href="telegram.php?action=auth" class="btn btn-telegram btn-block">
                                    <i class="fab fa-telegram"></i> Войти через Telegram
                                </a>
                            </div>
                        </form>
                        
                        <div class="form-footer">
                            <p>Нет аккаунта? <a href="#" id="switchToRegister">Зарегистрироваться</a></p>
                        </div>
                    </div>
                    
                    <!-- Форма регистрации -->
                    <div class="auth-form" id="registerForm">
                        <h2 class="form-title">Регистрация в AiTrading</h2>
                        
                        <?php if(isset($_GET['register_error'])): ?>
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php 
                            if(is_array($_GET['register_error'])) {
                                foreach($_GET['register_error'] as $error) {
                                    if($error == 'email_exists') echo 'Пользователь с таким email уже существует<br>';
                                    elseif($error == 'password_mismatch') echo 'Пароли не совпадают<br>';
                                    elseif($error == 'empty_fields') echo 'Заполните все поля<br>';
                                    elseif($error == 'invalid_email') echo 'Некорректный email адрес<br>';
                                    elseif($error == 'short_password') echo 'Пароль должен содержать минимум 6 символов<br>';
                                }
                            } else {
                                echo 'Ошибка регистрации';
                            }
                            ?>
                        </div>
                        <?php endif; ?>
                        
                        <form action="register.php" method="POST" id="registerFormElement">
                            <div class="form-group">
                                <label for="regName" class="form-label">Имя</label>
                                <input type="text" id="regName" name="name" class="form-input" placeholder="Введите ваше имя" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="regEmail" class="form-label">Email адрес</label>
                                <input type="email" id="regEmail" name="email" class="form-input" placeholder="example@mail.com" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="regPassword" class="form-label">Пароль</label>
                                <div class="password-input-container">
                                    <input type="password" id="regPassword" name="password" class="form-input" placeholder="Минимум 6 символов" required>
                                    <button type="button" class="password-toggle">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="password-strength" id="passwordStrength"></div>
                            </div>
                            
                            <div class="form-group">
                                <label for="regConfirmPassword" class="form-label">Подтвердите пароль</label>
                                <div class="password-input-container">
                                    <input type="password" id="regConfirmPassword" name="confirm_password" class="form-input" placeholder="Повторите пароль" required>
                                    <button type="button" class="password-toggle">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="terms" required>
                                    Я согласен с <a href="#" class="terms-link">условиями использования</a> и <a href="#" class="terms-link">политикой конфиденциальности</a>
                                </label>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-user-plus"></i> Зарегистрироваться
                                </button>
                            </div>
                            
                            <div class="form-group">
                                <a href="telegram.php?action=register" class="btn btn-telegram btn-block">
                                    <i class="fab fa-telegram"></i> Зарегистрироваться через Telegram
                                </a>
                            </div>
                        </form>
                        
                        <div class="form-footer">
                            <p>Уже есть аккаунт? <a href="#" id="switchToLogin">Войти</a></p>
                        </div>
                    </div>
                </div>
                
                <!-- Информация о платформе -->
                <div class="platform-info">
                    <h3>Преимущества AiTrading</h3>
                    <div class="features-grid">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-brain"></i>
                            </div>
                            <h4>AI Анализ</h4>
                            <p>Мощные алгоритмы искусственного интеллекта для анализа рынка</p>
                        </div>
                        
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <h4>Мгновенные сигналы</h4>
                            <p>Получайте торговые сигналы в реальном времени</p>
                        </div>
                        
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h4>Безопасность</h4>
                            <p>Защита данных и транзакций на высшем уровне</p>
                        </div>
                        
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fab fa-telegram"></i>
                            </div>
                            <h4>Telegram Интеграция</h4>
                            <p>Получайте сигналы прямо в Telegram</p>
                        </div>
                    </div>
                </div>
            </main>
            
            <footer class="footer">
                <p>© <?php echo date('Y'); ?> AiTrading Platform. Все права защищены.</p>
                <p class="footer-links">
                    <a href="#">Политика конфиденциальности</a> | 
                    <a href="#">Условия использования</a> | 
                    <a href="#">Поддержка</a>
                </p>
            </footer>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
