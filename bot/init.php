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

// URL для установки вебхука
$webhookUrl = 'https://altyysha.com/bot_script.php';

// URL для API Telegram
$apiUrl = "https://api.telegram.org/bot{$botToken}/setWebhook";

// Параметры запроса
$params = [
    'url' => $webhookUrl,
];

// Инициализация cURL сессии
$ch = curl_init($apiUrl);

// Настройка параметров cURL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

// Выполнение запроса
$response = curl_exec($ch);

// Проверка на ошибки
if (curl_errno($ch)) {
    echo 'Ошибка cURL: ' . curl_error($ch);
} else {
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($httpCode == 200) {
        $result = json_decode($response, true);
        if ($result['ok']) {
            echo "Вебхук успешно установлен на {$webhookUrl}";
        } else {
            echo "Ошибка при установке вебхука: " . $result['description'];
        }
    } else {
        echo "Ошибка HTTP: {$httpCode}";
    }
}

// Закрытие cURL сессии
curl_close($ch);
