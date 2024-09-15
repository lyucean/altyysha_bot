<?php

// Подключаем файл с вспомогательными функциями
require_once __DIR__ . '/helper.php';

// Загрузка переменных окружения
loadEnv(__DIR__ . '/.env');

// Получение токена из переменных окружения
$token = getenv('YOUR_BOT_TOKEN');

// Конфигурация
$use_webhook = getenv('USE_WEBHOOK') === 'true';// Установите true для использования вебхука, false для поллинга

// Функция для отправки сообщений
function logs($text) {
    print_r('<br> '. date('H:i:s:u ') .$text . PHP_EOL);
}

logs('Старт бота');

// Функция для отправки сообщений
function sendMessage($chat_id, $text) {
    global $token;
    file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=" . urlencode($text));
}
// Основной код
if ($use_webhook) {
    logs('Режим вебхука');
    // Режим вебхука
    $update = json_decode(file_get_contents('php://input'), true);
    handleUpdate($update);
} else {
    logs('поллинга');

    // Режим поллинга
    $offset = 0;
    while (true) {
        $updates = file_get_contents("https://api.telegram.org/bot$token/getUpdates?offset=$offset&limit=1");
        $updates = json_decode($updates, true);

        print_r($updates);
        if (isset($updates['result'][0])) {
            $update = $updates['result'][0];
            $offset = $update['update_id'] + 1;
            handleUpdate($update);
        }

        sleep(2);
    }
}

// Функция для обработки обновлений
function handleUpdate($update) {
    global $token;

    if (isset($update['message'])) {
        $chat_id = $update['message']['chat']['id'];
        $message_text = $update['message']['text'];

        // Здесь можно добавить более сложную логику обработки сообщений
        $response_text = "Привет! Вы сказали: $message_text";

        // Отправка ответа
        sendMessage($chat_id, $response_text);

        // Логирование
        echo "Получено сообщение: $message_text\n";
        echo "Отправлен ответ: $response_text\n";
    }
}
