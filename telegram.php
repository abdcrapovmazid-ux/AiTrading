<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

requireLogin();

$user_id = $_SESSION['user_id'];

// Получаем данные пользователя
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Обработка запроса на подключение Telegram
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['connect_telegram'])) {
    // Генерация уникального токена для подключения
    $telegram_token = bin2hex(random_bytes(16));
    
    $stmt = $pdo->prepare("UPDATE users SET telegram_token = ?, telegram_token_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE id = ?");
    $stmt->execute([$telegram_token, $user_id]);
    
    $message = 'success';
}

// Проверка, был ли Telegram уже подключен
$telegram_connected = !empty($user['telegram_id']);

// Если пользователь вернулся с данными от Telegram
if (isset($_GET['telegram_id']) && isset($_GET['username']) && isset($_GET['token'])) {
    $telegram_id = $_GET['telegram_id'];
    $username = $_GET['username'];
    $token = $_GET['token'];
    
    // Проверяем токен
    $stmt = $pdo->prepare("SELECT id FROM users WHERE telegram_token = ? AND telegram_token_expires > NOW()");
    $stmt->execute([$token]);
    $valid_token = $stmt->fetch();
    
    if ($valid_token) {
        // Обновляем данные пользователя
        $stmt = $pdo->prepare("UPDATE users SET telegram_id = ?, telegram_username = ?, telegram_connected = TRUE, telegram_token = NULL, telegram_token_expires = NULL WHERE id = ?");
        $stmt->execute([$telegram_id, $username, $user_id]);
        
        $message = 'connected';
        $telegram_connected = true;
        
        // Обновляем данные пользователя
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
        
        // Логируем действие
        logActivity($user_id, 'telegram_connected', "Подключен Telegram: @$username");
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AiTrading - Подключение Telegram</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="dashboard-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="dashboard-content">
            <div class="content-header">
                <h1><i class="fab fa-telegram"></i> Подключение Telegram</h1>
                <p>Подключите Telegram для получения торговых сигналов</p>
            </div>
            
            <?php if ($message == 'success'): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                Токен успешно сгенерирован! Используйте его для подключения бота.
            </div>
            <?php elseif ($message == 'connected'): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                Telegram успешно подключен! Теперь вы будете получать торговые сигналы.
            </div>
            <?php endif; ?>
            
            <div class="telegram-connection-section">
                <div class="telegram-status-card">
                    <div class="status-icon">
                        <i class="fab fa-telegram"></i>
                    </div>
                    <div class="status-content">
                        <h3>Статус подключения</h3>
                        <p class="status <?php echo $telegram_connected ? 'connected' : 'disconnected'; ?>">
                            <?php echo $telegram_connected ? 'Подключено' : 'Не подключено'; ?>
                        </p>
                        
                        <?php if ($telegram_connected): ?>
                        <div class="telegram-info">
                            <p><strong>Telegram ID:</strong> <?php echo htmlspecialchars($user['telegram_id']); ?></p>
                            <p><strong>Имя пользователя:</strong> @<?php echo htmlspecialchars($user['telegram_username']); ?></p>
                            <p><strong>Подключено:</strong> <?php echo formatDate($user['updated_at']); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if (!$telegram_connected): ?>
                <div class="telegram-instructions">
                    <h3>Как подключить Telegram:</h3>
                    
                    <div class="instruction-steps">
                        <div class="step">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h4>Нажмите кнопку ниже</h4>
                                <p>Сгенерируйте уникальный токен для подключения</p>
                            </div>
                        </div>
                        
                        <div class="step">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h4>Перейдите в нашего бота</h4>
                                <p>Найдите в Telegram бота: <strong>@AiTradingSignalsBot</strong></p>
                            </div>
                        </div>
                        
                        <div class="step">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h4>Отправьте токен боту</h4>
                                <p>Используйте команду <code>/connect</code> и отправьте полученный токен</p>
                            </div>
                        </div>
                        
                        <div class="step">
                            <div class="step-number">4</div>
                            <div class="step-content">
                                <h4>Готово!</h4>
                                <p>Бот автоматически подключится к вашему аккаунту</p>
                            </div>
                        </div>
                    </div>
                    
                    <form method="POST" action="" class="telegram-connect-form">
                        <button type="submit" name="connect_telegram" class="btn btn-telegram btn-large">
                            <i class="fab fa-telegram"></i> Сгенерировать токен для подключения
                        </button>
                    </form>
                    
                    <?php if (isset($telegram_token)): ?>
                    <div class="telegram-token">
                        <h4>Ваш токен для подключения:</h4>
                        <div class="token-display">
                            <code><?php echo $telegram_token; ?></code>
                            <button class="btn btn-copy" onclick="copyToken('<?php echo $telegram_token; ?>')">
                                <i class="fas fa-copy"></i> Копировать
                            </button>
                        </div>
                        <p class="token-note"><i class="fas fa-exclamation-triangle"></i> Токен действителен 1 час. Никому не передавайте его!</p>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="telegram-bot-info">
                    <h3>О нашем боте</h3>
                    <p>После подключения вы будете получать:</p>
                    <ul>
                        <li>Торговые сигналы в реальном времени</li>
                        <li>Уведомления об открытии/закрытии позиций</li>
                        <li>Статистику вашей торговли</li>
                        <li>Важные обновления платформы</li>
                        <li>Поддержку и помощь</li>
                    </ul>
                    
                    <a href="https://t.me/AiTradingSignalsBot" target="_blank" class="btn btn-outline">
                        <i class="fab fa-telegram"></i> Перейти к боту
                    </a>
                </div>
                <?php else: ?>
                <div class="telegram-connected-info">
                    <div class="connected-features">
                        <h3>Преимущества подключенного Telegram</h3>
                        <div class="features-grid">
                            <div class="feature">
                                <div class="feature-icon">
                                    <i class="fas fa-bell"></i>
                                </div>
                                <h4>Мгновенные уведомления</h4>
                                <p>Получайте сигналы сразу же после их генерации</p>
                            </div>
                            
                            <div class="feature">
                                <div class="feature-icon">
                                    <i class="fas fa-mobile-alt"></i>
                                </div>
                                <h4>Торговля с мобильного</h4>
                                <p>Управляйте вашими позициями прямо из Telegram</p>
                            </div>
                            
                            <div class="feature">
                                <div class="feature-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <h4>Аналитика в реальном времени</h4>
                                <p>Получайте статистику и аналитику прямо в чате</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="telegram-actions">
                        <h3>Действия с ботом</h3>
                        <div class="bot-commands">
                            <p>Основные команды бота:</p>
                            <ul class="commands-list">
                                <li><code>/start</code> - Начать работу с ботом</li>
                                <li><code>/balance</code> - Проверить баланс</li>
                                <li><code>/signals</code> - Посмотреть последние сигналы</li>
                                <li><code>/status</code> - Статус текущих позиций</li>
                                <li><code>/help</code> - Получить помощь</li>
                            </ul>
                        </div>
                        
                        <div class="action-buttons">
                            <form method="POST" action="">
                                <button type="submit" name="disconnect_telegram" class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите отключить Telegram?')">
                                    <i class="fas fa-unlink"></i> Отключить Telegram
                                </button>
                            </form>
                            
                            <a href="https://t.me/AiTradingSignalsBot" target="_blank" class="btn btn-telegram">
                                <i class="fab fa-telegram"></i> Открыть бота
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
    
    <script>
    function copyToken(token) {
        navigator.clipboard.writeText(token).then(function() {
            alert('Токен скопирован в буфер обмена!');
        }, function(err) {
            console.error('Ошибка копирования: ', err);
        });
    }
    
    // Автоматическое обновление статуса при возврате с Telegram
    if (window.location.search.includes('telegram_id')) {
        setTimeout(function() {
            window.location.href = 'telegram.php?connected=true';
        }, 3000);
    }
    </script>
    
    <script src="js/script.js"></script>
</body>
</html>
