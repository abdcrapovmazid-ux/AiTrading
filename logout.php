<?php
session_start();

// Уничтожаем все данные сессии
session_unset();
session_destroy();

// Редирект на главную
header("Location: index.php?logout=success");
exit();
?>
