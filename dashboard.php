<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Проверка авторизации
if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Получаем данные пользователя
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Получаем статистику пользователя
$stmt = $pdo->prepare("SELECT COUNT(*) as total_trades, 
                              SUM(CASE WHEN profit_loss > 0 THEN 1 ELSE 0 END) as winning_trades,
                              SUM(CASE WHEN profit_loss <= 0 THEN 1 ELSE 0 END) as losing_trades,
                              AVG(profit_loss) as avg_profit_loss
                       FROM trades WHERE user_id = ?");
$stmt->execute([$user_id]);
$stats = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AiTrading - Личный кабинет</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Верхнее меню -->
    <nav class="dashboard-nav">
        <div class="nav-container">
            <div class="nav-logo">
                <img src="images/logo.png" alt="AiTrading Logo" class="logo-small">
                <span class="nav-title">AiTrading</span>
            </div>
            
            <div class="nav-menu">
                <a href="dashboard.php" class="nav-link active"><i class="fas fa-home"></i> Главная</a>
                <a href="trading.php" class="nav-link"><i class="fas fa-chart-line"></i> Торговля</a>
                <a href="signals.php" class="nav-link"><i class="fas fa-bell"></i> Сигналы</a>
                <a href="telegram.php" class="nav-link"><i class="fab fa-telegram"></i> Telegram</a>
                <a href="settings.php" class="nav-link"><i class="fas fa-cog"></i> Настройки</a>
            </div>
            
            <div class="nav-user">
                <div class="user-dropdown">
                    <button class="user-btn">
                        <i class="fas fa-user-circle"></i>
                        <?php echo htmlspecialchars($user['name']); ?>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a href="profile.php"><i class="fas fa-user"></i> Профиль</a>
                        <a href="settings.php"><i class="fas fa-cog"></i> Настройки</a>
                        <div class="dropdown-divider"></div>
                        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Выйти</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="dashboard-container">
        <!-- Боковое меню -->
        <aside class="sidebar">
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <h3><?php echo htmlspecialchars($user['name']); ?></h3>
                <p class="user-email"><?php echo htmlspecialchars($user['email']); ?></p>
                <p class="user-plan">Базовый план</p>
            </div>
            
            <div class="sidebar-menu">
                <a href="dashboard.php" class="sidebar-link active">
                    <i class="fas fa-home"></i> Обзор
                </a>
                <a href="trading.php" class="sidebar-link">
                    <i class="fas fa-chart-line"></i> Торговля
                </a>
                <a href="signals.php" class="sidebar-link">
                    <i class="fas fa-bell"></i> Сигналы
                </a>
                <a href="history.php" class="sidebar-link">
                    <i class="fas fa-history"></i> История
                </a>
                <a href="telegram.php" class="sidebar-link">
                    <i class="fab fa-telegram"></i> Telegram
                </a>
                <a href="api.php" class="sidebar-link">
                    <i class="fas fa-key"></i> API Ключ
                </a>
                <a href="settings.php" class="sidebar-link">
                    <i class="fas fa-cog"></i> Настройки
                </a>
            </div>
            
            <div class="sidebar-footer">
                <div class="balance-card">
                    <h4>Баланс</h4>
                    <p class="balance-amount">$<?php echo number_format($user['balance'] ?? 0, 2); ?></p>
                    <a href="deposit.php" class="btn btn-small btn-primary">Пополнить</a>
                </div>
            </div>
        </aside>

        <!-- Основной контент -->
        <main class="dashboard-content">
            <div class="content-header">
                <h1>Добро пожаловать, <?php echo htmlspecialchars($user['name']); ?>!</h1>
                <p>Панель управления вашей торговой платформой</p>
            </div>
            
            <?php if(isset($_GET['registration']) && $_GET['registration'] == 'success'): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                Регистрация успешно завершена! Добро пожаловать в AiTrading.
            </div>
            <?php endif; ?>
            
            <!-- Статистика -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background-color: rgba(255, 102, 0, 0.1);">
                        <i class="fas fa-wallet" style="color: #ff6600;"></i>
                    </div>
                    <div class="stat-info">
                        <h3>$<?php echo number_format($user['balance'] ?? 0, 2); ?></h3>
                        <p>Текущий баланс</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background-color: rgba(0, 200, 83, 0.1);">
                        <i class="fas fa-chart-line" style="color: #00c853;"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $stats['total_trades'] ?? 0; ?></h3>
                        <p>Всего сделок</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background-color: rgba(33, 150, 243, 0.1);">
                        <i class="fas fa-percentage" style="color: #2196f3;"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $stats['winning_trades'] ?? 0; ?></h3>
                        <p>Успешные сделки</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background-color: rgba(244, 67, 54, 0.1);">
                        <i class="fas fa-chart-bar" style="color: #f44336;"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $stats['losing_trades'] ?? 0; ?></h3>
                        <p>Убыточные сделки</p>
                    </div>
                </div>
            </div>
            
            <!-- Быстрые действия -->
            <div class="quick-actions">
                <h2>Быстрые действия</h2>
                <div class="actions-grid">
                    <a href="trading.php" class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <h4>Начать торговлю</h4>
                        <p>Запустите автоматическую торговлю</p>
                    </a>
                    
                    <a href="telegram.php" class="action-card">
                        <div class="action-icon">
                            <i class="fab fa-telegram"></i>
                        </div>
                        <h4>Подключить Telegram</h4>
                        <p>Получайте сигналы в Telegram</p>
                    </a>
                    
                    <a href="deposit.php" class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <h4>Пополнить баланс</h4>
                        <p>Добавьте средства для торговли</p>
                    </a>
                    
                    <a href="api.php" class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-key"></i>
                        </div>
                        <h4>API Ключ</h4>
                        <p>Управление API доступом</p>
                    </a>
                </div>
            </div>
            
            <!-- Последние сделки -->
            <div class="recent-trades">
                <h2>Последние сделки</h2>
                <div class="trades-table-container">
                    <table class="trades-table">
                        <thead>
                            <tr>
                                <th>Пара</th>
                                <th>Тип</th>
                                <th>Цена</th>
                                <th>Объем</th>
                                <th>Прибыль/Убыток</th>
                                <th>Дата</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $pdo->prepare("SELECT * FROM trades WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
                            $stmt->execute([$user_id]);
                            $trades = $stmt->fetchAll();
                            
                            if(empty($trades)): ?>
                            <tr>
                                <td colspan="6" class="text-center">Нет сделок</td>
                            </tr>
                            <?php else: 
                            foreach($trades as $trade): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($trade['pair']); ?></td>
                                <td>
                                    <span class="trade-type <?php echo $trade['type'] == 'buy' ? 'type-buy' : 'type-sell'; ?>">
                                        <?php echo $trade['type'] == 'buy' ? 'Покупка' : 'Продажа'; ?>
                                    </span>
                                </td>
                                <td>$<?php echo number_format($trade['price'], 4); ?></td>
                                <td><?php echo number_format($trade['volume'], 2); ?></td>
                                <td class="<?php echo $trade['profit_loss'] > 0 ? 'profit' : 'loss'; ?>">
                                    $<?php echo number_format($trade['profit_loss'], 2); ?>
                                </td>
                                <td><?php echo date('d.m.Y H:i', strtotime($trade['created_at'])); ?></td>
                            </tr>
                            <?php endforeach; 
                            endif; ?>
                        </tbody>
                    </table>
                </div>
                <a href="history.php" class="btn btn-outline">Вся история</a>
            </div>
        </main>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
