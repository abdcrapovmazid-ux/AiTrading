// Инициализация приложения
document.addEventListener('DOMContentLoaded', function() {
    // Элементы DOM
    const loadingScreen = document.getElementById('loadingScreen');
    const appContent = document.getElementById('appContent');
    const loadingBar = document.getElementById('loadingBar');
    const loadingText = document.getElementById('loadingText');
    const loginTab = document.getElementById('loginTab');
    const registerTab = document.getElementById('registerTab');
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const switchToRegister = document.getElementById('switchToRegister');
    const switchToLogin = document.getElementById('switchToLogin');
    const passwordToggles = document.querySelectorAll('.password-toggle');
    const loginFormElement = document.getElementById('loginFormElement');
    const registerFormElement = document.getElementById('registerFormElement');

    // Имитация загрузки приложения
    function simulateLoading() {
        let progress = 0;
        const loadingMessages = [
            "Инициализация системы...",
            "Загрузка торговых алгоритмов...",
            "Подключение к биржам...",
            "Анализ рынка...",
            "Готово!"
        ];
        
        const interval = setInterval(() => {
            progress += Math.random() * 15;
            if (progress > 100) progress = 100;
            
            loadingBar.style.width = `${progress}%`;
            
            // Обновляем текст загрузки
            if (progress < 20) {
                loadingText.textContent = loadingMessages[0];
            } else if (progress < 40) {
                loadingText.textContent = loadingMessages[1];
            } else if (progress < 60) {
                loadingText.textContent = loadingMessages[2];
            } else if (progress < 80) {
                loadingText.textContent = loadingMessages[3];
            } else {
                loadingText.textContent = loadingMessages[4];
            }
            
            // Когда загрузка завершена
            if (progress >= 100) {
                clearInterval(interval);
                
                // Задержка перед показом основного контента
                setTimeout(() => {
                    loadingScreen.style.opacity = '0';
                    
                    setTimeout(() => {
                        loadingScreen.style.display = 'none';
                        appContent.style.display = 'flex';
                        
                        // Добавляем логотип по умолчанию, если пользовательский не загружен
                        const logo = document.getElementById('appLogo');
                        if (logo && logo.naturalWidth === 0) {
                            logo.src = 'https://via.placeholder.com/280x180/ff6600/000?text=AiTrading';
                            console.log('Используется логотип по умолчанию. Загрузите свой файл logo.png в папку images/');
                        }
                    }, 500);
                }, 800);
            }
        }, 300);
    }

    // Переключение между вкладками входа и регистрации
    function initAuthTabs() {
        if (!loginTab) return;
        
        loginTab.addEventListener('click', () => {
            loginTab.classList.add('active');
            registerTab.classList.remove('active');
            loginForm.classList.add('active');
            registerForm.classList.remove('active');
        });
        
        registerTab.addEventListener('click', () => {
            registerTab.classList.add('active');
            loginTab.classList.remove('active');
            registerForm.classList.add('active');
            loginForm.classList.remove('active');
        });
        
        if (switchToRegister) {
            switchToRegister.addEventListener('click', (e) => {
                e.preventDefault();
                registerTab.click();
            });
        }
        
        if (switchToLogin) {
            switchToLogin.addEventListener('click', (e) => {
                e.preventDefault();
                loginTab.click();
            });
        }
    }

    // Переключение видимости пароля
    function initPasswordToggles() {
        passwordToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    }

    // Валидация формы регистрации
    function initFormValidation() {
        if (registerFormElement) {
            registerFormElement.addEventListener('submit', function(e) {
                const password = document.getElementById('regPassword').value;
                const confirmPassword = document.getElementById('regConfirmPassword').value;
                const terms = document.querySelector('input[name="terms"]');
                
                if (password !== confirmPassword) {
                    e.preventDefault();
                    alert('Пароли не совпадают!');
                    return false;
                }
                
                if (password.length < 6) {
                    e.preventDefault();
                    alert('Пароль должен содержать минимум 6 символов!');
                    return false;
                }
                
                if (!terms.checked) {
                    e.preventDefault();
                    alert('Вы должны принять условия использования!');
                    return false;
                }
                
                return true;
            });
        }
    }

    // Проверка силы пароля
    function initPasswordStrengthChecker() {
        const passwordInput = document.getElementById('regPassword');
        if (!passwordInput) return;
        
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strengthIndicator = document.getElementById('passwordStrength');
            
            if (!strengthIndicator) {
                // Создаем индикатор силы пароля
                const indicator = document.createElement('div');
                indicator.id = 'passwordStrength';
                indicator.className = 'password-strength';
                this.parentElement.appendChild(indicator);
            }
            
            let strength = 0;
            if (password.length >= 6) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/\d/)) strength++;
            if (password.match(/[^a-zA-Z\d]/)) strength++;
            
            const strengthText = ['Очень слабый', 'Слабый', 'Средний', 'Сильный', 'Очень сильный'];
            const strengthColors = ['#f44336', '#ff9800', '#ffc107', '#4caf50', '#2e7d32'];
            
            if (strengthIndicator) {
                strengthIndicator.textContent = `Надежность: ${strengthText[strength]}`;
                strengthIndicator.style.color = strengthColors[strength];
            }
        });
    }

    // Восстановление пароля
    function initPasswordRecovery() {
        const forgotPasswordLinks = document.querySelectorAll('.forgot-password');
        forgotPasswordLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const email = prompt('Введите ваш email для восстановления пароля:');
                if (email) {
                    // Здесь будет AJAX запрос для восстановления пароля
                    alert(`Запрос на восстановление пароля отправлен на ${email}. Проверьте вашу почту.`);
                }
            });
        });
    }

    // Проверяем URL параметры для автопереключения на регистрацию
    function checkUrlParams() {
        const urlParams = new URLSearchParams(window.location.search);
        const hasRegisterError = urlParams.has('register_error');
        
        if (hasRegisterError && registerTab) {
            registerTab.click();
        }
    }

    // Инициализация приложения
    function initApp() {
        simulateLoading();
        initAuthTabs();
        initPasswordToggles();
        initFormValidation();
        initPasswordStrengthChecker();
        initPasswordRecovery();
        checkUrlParams();
        
        // Проверяем логотип
        const logo = document.getElementById('appLogo');
        if (logo) {
            logo.onerror = function() {
                this.src = 'https://via.placeholder.com/280x180/ff6600/000?text=AiTrading';
                console.warn('Логотип не найден. Используется изображение по умолчанию. Загрузите свой logo.png в папку images/');
            };
        }
        
        // Показываем сообщение о выходе
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('logout') && urlParams.get('logout') === 'success') {
            showNotification('Вы успешно вышли из системы', 'success');
        }
    }

    // Функция для показа уведомлений
    function showNotification(message, type = 'info') {
        // Удаляем предыдущие уведомления
        const existingNotifications = document.querySelectorAll('.notification');
        existingNotifications.forEach(notification => {
            notification.remove();
        });
        
        // Создаем новое уведомление
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'}"></i>
                <span>${message}</span>
            </div>
            <button class="notification-close">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        // Добавляем уведомление в body
        document.body.appendChild(notification);
        
        // Анимация появления
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);
        
        // Закрытие по кнопке
        const closeBtn = notification.querySelector('.notification-close');
        closeBtn.addEventListener('click', () => {
            notification.classList.remove('show');
            setTimeout(() => {
                notification.remove();
            }, 300);
        });
        
        // Автоматическое закрытие через 5 секунд
        setTimeout(() => {
            if (document.body.contains(notification)) {
                notification.classList.remove('show');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }
        }, 5000);
    }

    // Стили для уведомлений
    const notificationStyles = document.createElement('style');
    notificationStyles.textContent = `
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #111;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            min-width: 300px;
            max-width: 400px;
            transform: translateX(150%);
            transition: transform 0.3s ease;
            z-index: 9999;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .notification.show {
            transform: translateX(0);
        }
        
        .notification-success {
            border-left: 4px solid #4CAF50;
        }
        
        .notification-error {
            border-left: 4px solid #f44336;
        }
        
        .notification-info {
            border-left: 4px solid #2196F3;
        }
        
        .notification-content {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
        }
        
        .notification-content i {
            font-size: 1.2rem;
        }
        
        .notification-success .notification-content i {
            color: #4CAF50;
        }
        
        .notification-error .notification-content i {
            color: #f44336;
        }
        
        .notification-info .notification-content i {
            color: #2196F3;
        }
        
        .notification-close {
            background: none;
            border: none;
            color: #aaa;
            cursor: pointer;
            padding: 5px;
            font-size: 1rem;
        }
        
        .notification-close:hover {
            color: #fff;
        }
        
        .password-strength {
            margin-top: 5px;
            font-size: 0.85rem;
            padding: 5px 10px;
            border-radius: 5px;
            background-color: rgba(255, 102, 0, 0.1);
        }
    `;
    document.head.appendChild(notificationStyles);

    // Запускаем приложение
    initApp();
});
