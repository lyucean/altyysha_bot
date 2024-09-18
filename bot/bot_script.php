<?php

// Подключаем файл с вспомогательными функциями
require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/dictionaries.php';

// Загрузка переменных окружения
loadEnv(__DIR__ . '/.env');

// Получение токена из переменных окружения
$token = getenv('YOUR_BOT_TOKEN');
$bot_name = getenv('BOT_NAME');
$allowed_user_id = getenv('ADMIN_USER_ID');
$allowed_commands = ['/start', '/end', '/stats', '/hint'];

// Конфигурация
$use_webhook = getenv('USE_WEBHOOK') === 'true';// Установите true для использования вебхука, false для поллинга

// Загружаем текущее состояние игры
$gameState = json_decode(file_get_contents('game_state.json'), true);
// В начале скрипта, где инициализируется $gameState
if (!isset($gameState['current_sentence'])) {
    $gameState['current_sentence'] = ''; // Текущая загадка
    $gameState['guessed_words'] = []; // Угаданные слова
}

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

    logs(print_r($update, JSON_UNESCAPED_UNICODE),);
//    print_r($update);

    // Обработка текстовых сообщений
    $chat_id = $update['message']['chat']['id']; // ID чата

    if (!isset($update['message']['text'])) {
        sendMessage($chat_id, "Извини, но я не умею читать между строк... особенно когда строк нет! 🤓🤷‍♂️");
        return;
    }
    $message = $update['message']['text']; // Текст сообщения
    $user_id = $update['message']['from']['id'];
    $first_name = $update['message']['from']['first_name'] ?? '';
    $last_name = $update['message']['from']['last_name'] ?? '';
    $username = $first_name . ($last_name ? ' ' . $last_name : '');
    if (empty($username)) {
        $username = $update['message']['from']['username'] ?? 'Аноним ';
    }

    // Проверка на разрешенные команды
    if (in_array($message, $allowed_commands)) {
        // Обработка команд
        $response_text = command_processing($message, $username, $chat_id, $user_id);
    } // Обработка сообщений, отправленных боту или являющихся ответом на сообщение бота
    elseif (!empty($message) &&
        (str_starts_with($message, $bot_name) ||
            isset($update['message']['reply_to_message']['from']['username'])
            && $update['message']['reply_to_message']['from']['username'] === strtolower(trim($bot_name, '@')))) {
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
        return "Счет пока 0:0. Даже футбольные матчи бывают интереснее! ⚽😅";
    }

    arsort($gameState['score']); // Сортируем игроков по очкам (по убыванию)
    $stats = $statsJokes[array_rand($statsJokes)] . PHP_EOL. PHP_EOL;
    foreach ($gameState['score'] as $userId => $score) {
        $username = $gameState['usernames'][$userId] ?? 'Аноним';
        $stats .= "$username: $score очков" . PHP_EOL;
    }
    return $stats;
}

// Функция для получения подсказки
function getHint($answer): string
{
    $words = explode(' ', $answer);
    $randomWord = $words[array_rand($words)];
    return mb_strtoupper(mb_substr($randomWord, 0, 1, 'UTF-8'), 'UTF-8');
}

// Обработка команд
function command_processing($message, $username, $chat_id, $user_id): string
{
    global $allowed_user_id, $emojiFactsAboutDasha, $gameState, $hintJokes;
    $username = $username ?? '';
    $message = $message ?? '';

    // Команда для начала игры
    if ($message == '/start') {
        if ($user_id === (int)$allowed_user_id) { // Шутка для неразрешенных пользователей
            $gameState['active'] = true;
            $gameState['current_emoji'] = array_rand($emojiFactsAboutDasha);
            $response_text = "Игра началась! Вот первая загадка: " . $gameState['current_emoji'];
            file_put_contents('game_state.json', json_encode($gameState));
        } else {
            $response_text = "Эта команда только для VIP-персон. Твой статус пока что 'простой смертный'. 👑👨‍🦰";
        }
    } // Команда для завершения игры
    elseif ($message == '/end') {
        if ($user_id === (int)$allowed_user_id) { // Шутка для неразрешенных пользователей
            $gameState['active'] = false;
            $response_text = "Игра окончена. Спасибо за участие!";
            file_put_contents('game_state.json', json_encode($gameState));
        } else {
            $response_text = "Извини, но твой уровень доступа слишком низкий. Попробуй подрасти! 📏😄";
        }
    } // Команда для просмотра статистики
    elseif ($message == '/stats') {
        $stats = getStats($gameState) . PHP_EOL . PHP_EOL;
        $response_text = $stats;
    } // Команда для получения подсказки
    elseif ($message == '/hint' && $gameState['active']) {
        $currentScore = updateScore($gameState, $user_id, -1, $username);
        if ($currentScore >= 0) { // если у пользователя есть баллы на подсказку
            $hint = getHint($emojiFactsAboutDasha[$gameState['current_emoji']]);
            $joke = $hintJokes[array_rand($hintJokes)];
            $response_text = "@$username, $joke\nПодсказка: слово на букву '$hint'\nТвой текущий счет: $currentScore";
            file_put_contents('game_state.json', json_encode($gameState));
        } else {
            updateScore($gameState, $user_id, 1, $username); // Возвращаем балл обратно
            $response_text = "@$username, у тебя недостаточно баллов для подсказки. Продолжай угадывать!";
        }
    }

    $response_text = $response_text ?? $username . "У меня нет такой команды 😕";

    // Отправка ответа
    sendMessage($chat_id, $response_text);

    return $response_text;
}

// Функция для обновления счета игрока
function updateScore(&$gameState, $userId, $points, $username) {
    if (!isset($gameState['score'][$userId])) {
        $gameState['score'][$userId] = 0;
        $gameState['usernames'][$userId] = $username;
    }
    $gameState['score'][$userId] += $points;

    // Сохраняем обновленное состояние игры
    file_put_contents('game_state.json', json_encode($gameState));

    return $gameState['score'][$userId];
}

// Обработка обычных сообщений
function message_processing($message, $username, $chat_id, $user_id): string
{
    global $gameState, $emojiFactsAboutDasha, $correctGuessJokes, $partialGuessJokes, $wrongGuessJokes;
    $username = $username ?? '';
    $message = $message ?? '';

    // Проверка, активна ли игра
    if (!$gameState['active']) {
        return 'Игра ещё не началась!🥲';
    }

    // Получение правильного ответа и преобразование введенного пользователем ответа в нижний регистр
    $correctAnswer = mb_strtolower($emojiFactsAboutDasha[$gameState['current_emoji']], 'UTF-8');
    $userAnswer = mb_strtolower($message, 'UTF-8');

    // Инициализация массива угаданных слов, если его еще нет
    if (!isset($gameState['guessed_words'])) {
        $gameState['guessed_words'] = [];
    }

    // Проверка на полное совпадение ответа
    if ($userAnswer == $correctAnswer) {
        // Обновление счета и выбор случайной шутки
        $currentScore = updateScore($gameState, $user_id, 5, $username);
        $joke = $correctGuessJokes[array_rand($correctGuessJokes)];
        $response_text = "@$username, $joke Это действительно \"$correctAnswer\". Ты получаешь 5 баллов! Твой счет: $currentScore" . PHP_EOL . PHP_EOL;

        // Выбор новой эмодзи-загадки и сброс угаданных слов
        $gameState['current_emoji'] = array_rand($emojiFactsAboutDasha);
        $gameState['guessed_words'] = []; // Сброс угаданных слов для новой загадки
        $response_text .= "Следующая загадка: " . $gameState['current_emoji'];
    } else {
        // Разбиение ответов на слова
        $words = explode(' ', $correctAnswer);
        $userWords = explode(' ', $userAnswer);

        // Нахождение правильно угаданных слов
        $correctGuessedWords = array_intersect($words, $userWords);

        // Определение новых угаданных слов
        $newGuessedWords = array_diff($correctGuessedWords, $gameState['guessed_words']);

        if (!empty($newGuessedWords)) {
            // Подсчет очков за новые угаданные слова
            $points = count($newGuessedWords) * 2;
            $currentScore = updateScore($gameState, $user_id, $points, $username);
            $guessedWordsStr = implode(', ', $newGuessedWords);
            $joke = $partialGuessJokes[array_rand($partialGuessJokes)];

            // Добавление новых угаданных слов в список
            $gameState['guessed_words'] = array_merge($gameState['guessed_words'], $newGuessedWords);
            $response_text = "@$username, $joke Ты угадал новое слово(а): $guessedWordsStr. Получаешь $points балла(ов)! Твой счет: $currentScore. Но полный ответ другой, попробуй еще!";
        } else {
            // Если новых угаданных слов нет
            $joke = $wrongGuessJokes[array_rand($wrongGuessJokes)];
            $response_text = "@$username, $joke " . PHP_EOL . "Попробуй еще раз!";
        }
    }

    // Сохранение обновленного состояния игры
    file_put_contents('game_state.json', json_encode($gameState));

    // Отправка ответа пользователю
    sendMessage($chat_id, $response_text);

    return $response_text;
}


