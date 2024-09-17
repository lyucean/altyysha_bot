<?php

// Подключаем файл с вспомогательными функциями
require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/dictionaries.php';

// Загрузка переменных окружения
loadEnv(__DIR__ . '/.env');

// Получение токена из переменных окружения
$token = getenv('YOUR_BOT_TOKEN');
$bot_name = getenv('BOT_NAME');
$allowed_user = 'lyucean';
$allowed_commands = ['/start', '/end'];

// Конфигурация
$use_webhook = getenv('USE_WEBHOOK') === 'true';// Установите true для использования вебхука, false для поллинга

// Загружаем текущее состояние игры
$gameState = json_decode(file_get_contents('game_state.json'), true);

logs('Старт бота');

// Основной код
if ($use_webhook) {
    logs('Режим WebHook');
    // Режим вебхука
    $update = json_decode(file_get_contents('php://input'), true);
    handleUpdate($update);
} else {
    logs('Режим Polling');

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

// Функция для отправки сообщений
function sendMessage($chat_id, $text): void
{
    global $token;
    file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=" . urlencode($text));
}

// Функция для обработки обновлений
function handleUpdate($update): void
{
    global $token, $bot_name, $allowed_commands;

    logs(json_encode($update, JSON_UNESCAPED_UNICODE));

    // Обработка текстовых сообщений
    $chat_id = $update['message']['chat']['id']; // ID чата

    if (!isset($update['message']['text'])) {
        sendMessage($chat_id, "Извини, но я не умею читать между строк... особенно когда строк нет! 🤓🤷‍♂️");
        return;
    }
    $message = $update['message']['text']; // Текст сообщения
    $username = $update['message']['from']['username'] ?? '';

    // Проверка на разрешенные команды
    if (in_array($message, $allowed_commands)) {
        // Обработка команд
        $response_text = command_processing($message, $username, $chat_id);
    }

    // Обработка только сообщений отправленных боту
    if (!empty($message) || str_starts_with($message, $bot_name)) {
        $message = trim(str_replace($bot_name, '', $message)); // Удаление имени бота
        // Обработка команд
        $response_text = message_processing($message, $username, $chat_id);
    }

    // Логирование
    logs("Получено сообщение: $message");
    logs("Отправлен ответ: $response_text");
}


function command_processing($message, $username, $chat_id): string
{
    global $allowed_user, $emojiFactsAboutDasha;
    $username = $username ?? '';
    $message = $message ?? '';

    if ($username === $allowed_user) { // Обработка команд для разрешенного пользователя

        // Команда для начала игры
        if ($message == '/start') {
            $gameState['active'] = true;
            $gameState['current_emoji'] = array_rand($emojiFactsAboutDasha);
            sendMessage($chat_id, "Игра началась! Вот первая загадка: " . $gameState['current_emoji']);
            file_put_contents('game_state.json', json_encode($gameState));
        }

        // Команда для завершения игры
        elseif ($message == '/end') {
            $gameState['active'] = false;
            sendMessage($chat_id, "Игра окончена. Спасибо за участие!");
            file_put_contents('game_state.json', json_encode($gameState));
        }

    } else {
        // Шутка для неразрешенных пользователей
        $response_text = "Эта команда только для VIP-персон. Твой статус пока что 'простой смертный'. 👑👨‍🦰";
    }

    $response_text = $response_text ?? $username . "У меня нет такой команды 😕";

    // Отправка ответа
    sendMessage($chat_id, $response_text);

    return $response_text;
}

function message_processing($message, $username, $chat_id): string
{

    $response_text = $response_text ?? $username . ' Я вас не понимаю 😕';

    // Отправка ответа
    sendMessage($chat_id, $response_text);

    return $response_text;
}
