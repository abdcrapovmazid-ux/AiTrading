<?php if (!isset($no_header)): ?>
<nav class="dashboard-nav">
    <div class="nav-container">
        <div class="nav-logo">
            <img src="images/logo.png" alt="AiTrading Logo" class="logo-small">
            <span class="nav-title">AiTrading</span>
        </div>
        
        <div class="nav-menu">
            <a href="dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fas fa-home"></i> Главная
            </a>
            <a href="trading.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'trading.php' ? 'active' : ''; ?>">
                <i class="fas fa-chart-line"></i> Торговля
            </a>
            <a href="signals.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'signals.php' ? 'active' : ''; ?>">
                <i class="fas fa-bell"></i> Сигналы
            </a>
            <a href="telegram.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'telegram.php' ? 'active' : ''; ?>">
                <i class="fab fa-telegram"></i> Telegram
            </a>
            <a href="settings.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
                <i class="fas fa-cog"></i> Настройки
            </a>
        </div>
        
        <div class="nav-user">
            <div class="user-dropdown">
                <button class="user-btn">
                    <i class="fas fa-user-circle"></i>
                    <?php echo htmlspecialchars($_SESSION['user_name']); ?>
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
<?php endif; ?>
