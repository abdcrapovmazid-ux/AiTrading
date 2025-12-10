-- База данных AiTrading
CREATE DATABASE IF NOT EXISTS aitrading CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE aitrading;

-- Таблица пользователей
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    api_key VARCHAR(64) NOT NULL UNIQUE,
    telegram_id VARCHAR(100) NULL,
    telegram_username VARCHAR(100) NULL,
    telegram_connected BOOLEAN DEFAULT FALSE,
    balance DECIMAL(10,2) DEFAULT 0.00,
    subscription_plan ENUM('free', 'basic', 'pro', 'premium') DEFAULT 'free',
    subscription_ends DATE NULL,
    is_active BOOLEAN DEFAULT TRUE,
    email_verified BOOLEAN DEFAULT FALSE,
    verification_token VARCHAR(64) NULL,
    reset_token VARCHAR(64) NULL,
    reset_token_expires DATETIME NULL,
    last_login DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Таблица сделок
CREATE TABLE trades (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    pair VARCHAR(20) NOT NULL,
    type ENUM('buy', 'sell') NOT NULL,
    price DECIMAL(15,8) NOT NULL,
    volume DECIMAL(15,8) NOT NULL,
    profit_loss DECIMAL(10,2) DEFAULT 0.00,
    status ENUM('open', 'closed', 'canceled') DEFAULT 'closed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    closed_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Таблица торговых сигналов
CREATE TABLE signals (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    pair VARCHAR(20) NOT NULL,
    signal_type ENUM('buy', 'sell', 'strong_buy', 'strong_sell') NOT NULL,
    price DECIMAL(15,8) NOT NULL,
    target_price DECIMAL(15,8) NULL,
    stop_loss DECIMAL(15,8) NULL,
    confidence DECIMAL(5,2) NOT NULL,
    is_executed BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Таблица платежей
CREATE TABLE payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'USD',
    payment_method ENUM('card', 'crypto', 'bank') NOT NULL,
    transaction_id VARCHAR(100) NOT NULL UNIQUE,
    status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    description VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Таблица логов активности
CREATE TABLE activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    action VARCHAR(100) NOT NULL,
    details TEXT NULL,
    ip_address VARCHAR(45) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Таблица API запросов
CREATE TABLE api_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    endpoint VARCHAR(100) NOT NULL,
    method VARCHAR(10) NOT NULL,
    response_code INT NOT NULL,
    response_time FLOAT NOT NULL,
    ip_address VARCHAR(45) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Создание индексов
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_api_key ON users(api_key);
CREATE INDEX idx_trades_user_id ON trades(user_id);
CREATE INDEX idx_trades_created_at ON trades(created_at);
CREATE INDEX idx_signals_user_id ON signals(user_id);
CREATE INDEX idx_payments_user_id ON payments(user_id);
CREATE INDEX idx_payments_status ON payments(status);
CREATE INDEX idx_activity_logs_user_id ON activity_logs(user_id);

-- Вставка тестового пользователя (пароль: Test123456)
INSERT INTO users (name, email, password, api_key, balance, subscription_plan, is_active, email_verified) 
VALUES 
('Тестовый Пользователь', 'test@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'testapikey1234567890abcdefghijklmnop', 1000.00, 'pro', TRUE, TRUE);
