<aside class="sidebar">
    <div class="user-info">
        <div class="user-avatar">
            <i class="fas fa-user-circle"></i>
        </div>
        <h3><?php echo htmlspecialchars($_SESSION['user_name']); ?></h3>
        <p class="user-email"><?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
        <?php if(isset($user['telegram_connected']) && $user['telegram_connected']): ?>
        <p class="user-telegram">
            <i class="fab fa-telegram"></i> Telegram подключен
        </p>
        <?php else: ?>
        <p class="user-telegram disconnected">
            <i class="fab fa-telegram"></i> Telegram не подключен
        </p>
        <?php endif; ?>
    </div>
    
    <div class="sidebar-menu">
        <a href="dashboard.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
            <i class="fas fa-home"></i> Обзор
        </a>
        <a href="trading.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'trading.php' ? 'active' : ''; ?>">
            <i class="fas fa-chart-line"></i> Торговля
        </a>
        <a href="signals.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'signals.php' ? 'active' : ''; ?>">
            <i class="fas fa-bell"></i> Сигналы
        </a>
        <a href="history.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'history.php' ? 'active' : ''; ?>">
            <i class="fas fa-history"></i> История
        </a>
        <a href="telegram.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'telegram.php' ? 'active' : ''; ?>">
            <i class="fab fa-telegram"></i> Telegram
        </a>
        <a href="api.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'api.php' ? 'active' : ''; ?>">
            <i class="fas fa-key"></i> API Ключ
        </a>
        <a href="settings.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
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
