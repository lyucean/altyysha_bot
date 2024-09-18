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
$allowed_commands = ['/start', '/end', '/stats'];

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
    global $bot_name, $allowed_commands;

    logs(json_encode($update, JSON_UNESCAPED_UNICODE));

    // Обработка текстовых сообщений
    $chat_id = $update['message']['chat']['id']; // ID чата

    if (!isset($update['message']['text'])) {
        sendMessage($chat_id, "Извини, но я не умею читать между строк... особенно когда строк нет! 🤓🤷‍♂️");
        return;
    }
    $message = $update['message']['text']; // Текст сообщения
    $username = $update['message']['from']['username'] ?? '';
    $user_id = $update['message']['from']['id'];

    // Проверка на разрешенные команды
    if (in_array($message, $allowed_commands)) {
        // Обработка команд
        $response_text = command_processing($message, $username, $chat_id);
    }
    // Обработка только сообщений отправленных боту
    elseif (!empty($message) || str_starts_with($message, $bot_name)) {
        $message = trim(str_replace($bot_name, '', $message)); // Удаление имени бота
        // Обработка команд
        $response_text = message_processing($message, $username, $chat_id, $user_id);
    }

    $response_text = $response_text ?? '';

    // Логирование
    logs("Получено сообщение: $message");
    logs("Отправлен ответ: $response_text");
}

// Функция для получения статистики игры
function getStats($gameState): string
{
    global $statsJokes;
    if (empty($gameState['score'])) {
        return "Счет пока 0:0:0. Даже футбольные матчи бывают интереснее! ⚽😅";
    }

    arsort($gameState['score']); // Сортируем игроков по очкам (по убыванию)
    $stats = $statsJokes[array_rand($statsJokes)];
    foreach ($gameState['score'] as $userId => $score) {
        $stats .= "@" . $gameState['usernames'][$userId] . ": $score очков". PHP_EOL;
    }
    return $stats;
}
function command_processing($message, $username, $chat_id): string
{
    global $allowed_user, $emojiFactsAboutDasha, $gameState;
    $username = $username ?? '';
    $message = $message ?? '';

    if ($username === $allowed_user) { // Обработка команд для разрешенного пользователя

        // Команда для начала игры
        if ($message == '/start') {
            $gameState['active'] = true;
            $gameState['current_emoji'] = array_rand($emojiFactsAboutDasha);
            $response_text = "Игра началась! Вот первая загадка: " . $gameState['current_emoji'];
            file_put_contents('game_state.json', json_encode($gameState));
        }

        // Команда для завершения игры
        elseif ($message == '/end') {
            $gameState['active'] = false;
            $response_text ="Игра окончена. Спасибо за участие!";
            file_put_contents('game_state.json', json_encode($gameState));
        }
    } else {
        // Шутка для неразрешенных пользователей
        $response_text = "Эта команда только для VIP-персон. Твой статус пока что 'простой смертный'. 👑👨‍🦰";
    }

    // Команда для просмотра статистики
    if ($message == '/stats') {
        $stats = getStats($gameState) . PHP_EOL . PHP_EOL;
        $response_text = $stats;
    }

    $response_text = $response_text ?? $username . "У меня нет такой команды 😕";

    // Отправка ответа
    sendMessage($chat_id, $response_text);

    return $response_text;
}

// Функция для обновления счета игрока
function updateScore(&$gameState, $userId, $points) {
    if (!isset($gameState['score'][$userId])) {
        $gameState['score'][$userId] = 0;
    }
    $gameState['score'][$userId] += $points;
    return $gameState['score'][$userId];
}

function message_processing($message, $username, $chat_id, $user_id): string
{
    // Обработка ответов игроков
    global $gameState, $emojiFactsAboutDasha, $correctGuessJokes, $partialGuessJokes, $wrongGuessJokes;
    $username = $username ?? '';
    $message = $message ?? '';
    if (!$gameState['active']) { // Если игра ещё не началась
        return 'Игра ещё не началась!🥲';
    }

    $correctAnswer = mb_strtolower($emojiFactsAboutDasha[$gameState['current_emoji']], 'UTF-8'); // Загаданное слово
    $userAnswer = mb_strtolower($message, 'UTF-8'); // Пользовательский ответ

    // Если ответ полностью правильный
    if ($userAnswer == $correctAnswer) {
        $currentScore = updateScore($gameState, $user_id, 5);
        $joke = $correctGuessJokes[array_rand($correctGuessJokes)];
        $response_text = "@$username, $joke Это действительно \"$correctAnswer\". Ты получаешь 5 баллов! Твой счет: $currentScore" . PHP_EOL;

        // Выбираем новую эмодзи-загадку
        $gameState['current_emoji'] = array_rand($emojiFactsAboutDasha);
        $response_text .= "Следующая загадка: " . $gameState['current_emoji'];

        file_put_contents('game_state.json', json_encode($gameState));
    } else {
        // Проверяем, угадал ли игрок хотя бы одно слово
        $words = explode(' ', $correctAnswer);
        $userWords = explode(' ', $userAnswer);
        $correctGuessedWords = array_intersect($words, $userWords);

        if (!empty($correctGuessedWords)) {
            $points = count($correctGuessedWords) * 2;
            $currentScore = updateScore($gameState, $user_id, $points);
            $guessedWordsStr = implode(', ', $correctGuessedWords);
            $joke = $partialGuessJokes[array_rand($partialGuessJokes)];

            $response_text = "@$username, $joke Ты угадал слово(а): $guessedWordsStr. Получаешь $points балла(ов)! Твой счет: $currentScore. Но полный ответ другой, попробуй еще!";
            file_put_contents('game_state.json', json_encode($gameState));
        } else {
            $joke = $wrongGuessJokes[array_rand($wrongGuessJokes)];
            $response_text = "@$username, $joke Попробуй еще раз!";
        }
    }

    // Отправка ответа
    sendMessage($chat_id, $response_text);

    return $response_text;
}
