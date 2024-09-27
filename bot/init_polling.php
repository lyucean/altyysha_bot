<?php

// Подключаем файл с вспомогательными функциями
require_once __DIR__ . '/helper.php';

// Загрузка переменных окружения
loadEnv(__DIR__ . '/.env');

// Получаем токен бота из переменных окружения
$botToken = getenv('BOT_TOKEN');

if (!$botToken) {
    die("Ошибка: BOT_TOKEN не найден в файле .env");
}

// Функция для удаления вебхука
$url = "https://api.telegram.org/bot$botToken/deleteWebhook";

$result = file_get_contents($url);

if ($result === FALSE) {
    logs("Ошибка при удалении вебхука");
    return false;
}

$response = json_decode($result, true);
if ($response['ok']) {
    logs("Вебхук удален успешно");
    return true;
} else {
    logs("Ошибка: " . $response['description']);
    return false;
}
