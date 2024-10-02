<?php

// Подключаем файл с вспомогательными функциями
require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/dictionaries.php';

// Загрузка переменных окружения
loadEnv(__DIR__ . '/.env');

// Проверяем, находимся ли мы в окружении разработки
if (getenv('ENVIRONMENT') === 'development') {
    // Включаем вывод всех ошибок
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Дополнительно, можно включить логирование ошибок
    ini_set('log_errors', 1);
    ini_set('error_log', '/path/to/error.log'); // Укажите путь, куда сохранять лог ошибок
}

// Получение токена из переменных окружения
$token = getenv('BOT_TOKEN');
$bot_name = getenv('BOT_NAME');
$allowed_user_id = getenv('ADMIN_USER_ID'); // USER_ID админа
$game_state_file = __DIR__ . '/storage_game_state.json'; // файл для хранения состояния игры
$riddles_file = __DIR__ . '/storage_riddles.json'; // фай для хранения загадок
$allowed_commands = ['/stats', '/hint']; // Массив разрешенных команд
$admin_commands = ['/start', '/end', '/add', '/del', '/list'];  // Массив разрешенных команд для админа
$riddles = loadRiddles(); // Загрузка загадок из JSON-файла
// Конфигурация
$use_webhook = getenv('USE_WEBHOOK') === 'true';// Установите true для использования вебхука, false для поллинга

logs('Старт бота');
error_log("Текущий рабочий каталог: " . getcwd());

// Обработка, каким методом будет работать наш бот
// Обработка, каким методом будет работать наш бот
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

        if (isset($updates['result'][0])) {
            $update = $updates['result'][0];
            $offset = $update['update_id'] + 1;
            handleUpdate($update);
        }

        sleep(2);
    }
}

// Функция инициализации состояния игры
function initializeGameState(): void
{
    global $gameState, $game_state_file;

    if (!file_exists($game_state_file)) {
        $gameState = [
            'active' => false,
            'current_emoji' => '',
            'solved_riddles' => [],
            'guessed_words' => [],
            'score' => [],
            'usernames' => []
        ];
        if (file_put_contents($game_state_file, json_encode($gameState)) === false) {
            error_log("Ошибка при записи в файл: $game_state_file");
        }
    } else { // Загружаем текущее состояние игры
        $gameState = json_decode(file_get_contents($game_state_file), true);
    }
}

// Инициализация состояния игры
initializeGameState();

// Функция для отправки сообщений
function sendMessage($chat_id, $text): void
{
    global $token;
    file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=" . urlencode($text));
}

// Функция для обработки обновлений
function handleUpdate($update): void
{
    global $bot_name;

    logs(print_r($update, JSON_UNESCAPED_UNICODE),);

    if (!isset($update['message']['chat']['id'])) { // Если сообщение не содержит ID чата
        logs('Hello bot!');
        return;
    }

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

    if (isAllowedCommand($message, $user_id, $chat_id)) { // Проверка на разрешенные команды
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
    global $statsJokes, $riddles;

    $totalRiddles = count($riddles);
    $solvedRiddles = isset($gameState['solved_riddles']) ? count($gameState['solved_riddles']) : 0;

    $stats = $statsJokes[array_rand($statsJokes)] . PHP_EOL . PHP_EOL;
    $stats .= "Отгадано загадок: $solvedRiddles из $totalRiddles" . PHP_EOL . PHP_EOL;

    if (empty($gameState['score'])) {
        return $stats . "Счет пока 0:0. Даже футбольные матчи бывают интереснее! ⚽😅";
    }

    arsort($gameState['score']); // Сортируем игроков по очкам (по убыванию)
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

// функция проверки разрешенных команд
function isAllowedCommand($message, $user_id, $chat_id): bool
{
    global $allowed_commands, $admin_commands, $allowed_user_id;

    // Удаляем @username_bot из сообщения, если оно есть
    $command = preg_replace('/@\w+bot$/', '', $message);

    // Разделяем сообщение на команду и аргументы
    $parts = explode(' ', $command, 2);
    $command = strtolower($parts[0]);

    // Проверяем, является ли команда разрешенной для всех пользователей
    if (in_array($command, $allowed_commands)) {
        return true;
    }

    // Проверяем команды, доступные только для администратора
    if (in_array($command, $admin_commands)) {
        if ($user_id === (int)$allowed_user_id) {
            return true;
        }else{
            sendMessage($chat_id, "Зря стараешься. Такие переговоры не твой уровень, не твой ранг. Мне нечего тебе предложить, молодой человек. Только соль и перец.");
        }
    }

    return false;
}


// Функция извлечения команды
function extractCommand($message): string
{
    // Удаляем @username_bot из сообщения, если оно есть
    $command = preg_replace('/@\w+bot$/', '', $message);

    // Извлекаем первое слово (команду) из сообщения
    $parts = explode(' ', $command, 2);
    return strtolower($parts[0]);
}

// Обработка команд
function command_processing($message, $username, $chat_id, $user_id): string
{
    global $riddles, $gameState, $hintJokes, $game_state_file;
    $username = $username ?? '';
    $message = $message ?? '';

    $command = extractCommand($message);

    // Команда для просмотра статистики
    if ($command == '/stats') {
        $stats = getStats($gameState) . PHP_EOL . PHP_EOL;
        $response_text = $stats;
    }

    // Команда для получения подсказки
    elseif ($command == '/hint') {
        // Проверка, активна ли игра
        if (!isset($gameState['active']) || !$gameState['active']) {
            $response_text = "Игра ещё не началась!🥲";
        } else {
            $currentScore = updateScore($gameState, $user_id, -1, $username);
            if ($currentScore >= 0) { // если у пользователя есть баллы на подсказку
                $hint = getHint($riddles[$gameState['current_emoji']]);
                $joke = $hintJokes[array_rand($hintJokes)];
                $response_text = "@$username, $joke\nПодсказка: слово на букву '$hint'\nТвой текущий счет: $currentScore";
                file_put_contents($game_state_file, json_encode($gameState));
            } else {
                updateScore($gameState, $user_id, 1, $username); // Возвращаем балл обратно
                $response_text = "@$username, у тебя недостаточно баллов для подсказки. Продолжай угадывать!";
            }
        }
    }

    // Команда для начала игры
    elseif ($command == '/start') {

        if (empty($riddles)) {
            return "Извините, но список загадок пуст. Игру невозможно начать. Пожалуйста, добавьте загадки с помощью команды /add";
        }

        if ($gameState['active'] === 'active') {
            return "Игра уже идет! Используйте /end, чтобы закончить текущую игру.";
        }

        $gameState = [
            'active' => true,
            'current_emoji' => array_rand($riddles),
            'solved_riddles' => [],
            'guessed_words' => [],
            'score' => [],
            'usernames' => []
        ];

        $response_text = "Игра началась! " . PHP_EOL . "Вот первая загадка: " . $gameState['current_emoji'];
        file_put_contents($game_state_file, json_encode($gameState));
    }

    // Команда для завершения игры
    elseif ($command == '/end') {
        $gameState['active'] = false;
        $response_text = "Игра окончена. Спасибо за участие!";
        file_put_contents($game_state_file, json_encode($gameState));
    }

    // Команда для добавления загадки /add [эмодзи] [факт]
    elseif (str_starts_with($message, '/add')) {
        $parts = preg_split('/\s+/', $message, 3);
        if (count($parts) === 3) {
            $emoji = trim($parts[1]);
            $fact = trim($parts[2]);

            // Улучшенная проверка на эмодзи
            if (preg_match('/^[\x{1F000}-\x{1FFFF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]+$/u', $emoji)) {
                addRiddle($emoji, $fact);
                $response_text = "Загадка успешно добавлена!";
            } else {
                $response_text = "Ошибка: ключ должен состоять только из эмодзи.";
            }
        } else {
            $response_text = "Неверный формат. Используйте: /add [эмодзи] [факт]" . PHP_EOL .
                "Например: /add 🍎 Этот фрукт часто ассоциируется с компанией, основанной Стивом Джобсом" . PHP_EOL .
                "Вы также можете использовать несколько эмодзи: /add 🌞🌡️ Это явление часто наблюдается в пустынях.";
        }
    }

    // Команда для удаления
    elseif (str_starts_with($message, '/del')) {
        $parts = explode(' ', $message, 2);
        if (count($parts) === 2) {
            if (deleteRiddle($parts[1])) {
                $response_text = "Загадка удалена!";
            } else {
                $response_text = "Загадка не найдена.";
            }
        } else {
            $response_text = "Использование: /del [эмодзи]";
        }

    }

    // Команда для вывода списка загадок
    elseif ($message === '/list') {
        $response_text = listRiddles();
    }

    $response_text = $response_text ?? $username  . PHP_EOL . "У меня нет такой команды 😕";

    // Отправка ответа
    sendMessage($chat_id, $response_text);

    return $response_text;
}

// Функция для обновления счета игрока
function updateScore(&$gameState, $userId, $points, $username) {
    global $game_state_file;

    if (!isset($gameState['score'][$userId])) {
        $gameState['score'][$userId] = 0;
        $gameState['usernames'][$userId] = $username;
    }
    $gameState['score'][$userId] += $points;

    // Сохраняем обновленное состояние игры
    file_put_contents($game_state_file, json_encode($gameState));

    return $gameState['score'][$userId];
}

// Обработка обычных сообщений
function message_processing($message, $username, $chat_id, $user_id): string
{
    global $gameState, $riddles, $correctGuessJokes, $partialGuessJokes, $wrongGuessJokes, $game_state_file;
    $username = $username ?? '';
    $message = $message ?? '';

    // Инициализация массива отгаданных загадок, если его еще нет
    if (!isset($gameState['solved_riddles'])) {
        $gameState['solved_riddles'] = [];
    }

    // Инициализация массива угаданных слов, если его еще нет
    if (!isset($gameState['guessed_words'])) {
        $gameState['guessed_words'] = [];
    }

    // Проверка, активна ли игра
    if (!$gameState['active']) {
        sendMessage($chat_id, 'Игра ещё не началась!🥲');
        return 'Игра ещё не началась!🥲';
    }

    // Получение правильного ответа и преобразование введенного пользователем ответа в нижний регистр
    $correctAnswer = mb_strtolower($riddles[$gameState['current_emoji']], 'UTF-8');
    $userAnswer = mb_strtolower($message, 'UTF-8');

    if ($userAnswer == $correctAnswer) { // Проверка на полное совпадение ответа
        // Обновление счета и выбор случайной шутки
        $currentScore = updateScore($gameState, $user_id, 5, $username);
        $joke = $correctGuessJokes[array_rand($correctGuessJokes)];
        $response_text = "@$username, $joke Это действительно \"$correctAnswer\". "
            . PHP_EOL . " Ты получаешь 5 баллов! Твой счет: $currentScore" . PHP_EOL;

        // Добавляем текущую загадку в список решенных
        $gameState['solved_riddles'][] = $gameState['current_emoji'];

        // Выбираем новую загадку из нерешенных
        $unsolved_riddles = array_diff(array_keys($riddles), $gameState['solved_riddles']);

        // Если все загадки решены, заканчиваем игру
        if (empty($unsolved_riddles)) {
            return endGame($chat_id);
        }

        $gameState['current_emoji'] = $unsolved_riddles[array_rand($unsolved_riddles)];
        $gameState['guessed_words'] = []; // Сброс угаданных слов для новой загадки
        $response_text .= PHP_EOL . "Следующая загадка: " . $gameState['current_emoji'];
    }
    else { // Проверка на частичное совпадение
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
            $response_text = "@$username, $joke " . PHP_EOL . "Ты угадал(а) слова: $guessedWordsStr. "
                . PHP_EOL . "Получаешь $points балла(ов)! "
                . PHP_EOL . "Твой счет: $currentScore" . PHP_EOL;

            $gameState['guessed_words'] = array_merge($gameState['guessed_words'], $newGuessedWords);

            // Проверяем, все ли слова отгаданы
            if (count($gameState['guessed_words']) == count($words)) {
                // Все слова отгаданы, считаем загадку полностью разгаданной
                $gameState['solved_riddles'][] = $gameState['current_emoji'];
                $response_text .= "Поздравляю! Ты полностью разгадал(а) загадку: Даша \"$correctAnswer\"." . PHP_EOL;

                // Выбираем новую загадку из нерешенных
                $unsolved_riddles = array_diff(array_keys($riddles), $gameState['solved_riddles']);

                // Если все загадки решены, заканчиваем игру
                if (empty($unsolved_riddles)) {
                    return endGame($chat_id);
                }

                $gameState['current_emoji'] = $unsolved_riddles[array_rand($unsolved_riddles)];
                $gameState['guessed_words'] = []; // Сброс угаданных слов для новой загадки
                $response_text .= "Следующая загадка: " . $gameState['current_emoji'];
            } else {
                $response_text .= "Продолжай угадывать!💪🏻"
                    . PHP_EOL . "Еще есть не отгаданные слова 😜";
            }
        } else {
            // Если новых угаданных слов нет
            $joke = $wrongGuessJokes[array_rand($wrongGuessJokes)];
            $response_text = "@$username, $joke " . PHP_EOL . "Попробуй еще раз!";
        }
    }

    // Сохранение обновленного состояния игры
    file_put_contents($game_state_file, json_encode($gameState));

    // Отправка ответа пользователю
    sendMessage($chat_id, $response_text);

    return $response_text;
}

// Метод вывода информации при завершении игры
function endGame($chat_id): string
{
    global $gameState;

    $gameState['active'] = false;

    $response_text = "Поздравляем! Все загадки разгаданы! 🎉" . PHP_EOL . PHP_EOL;
    $response_text .= "Финальный рейтинг игроков:" . PHP_EOL;

    arsort($gameState['score']); // Сортируем игроков по очкам (по убыванию)
    foreach ($gameState['score'] as $userId => $score) {
        $username = $gameState['usernames'][$userId] ?? 'Аноним';
        $response_text .= "$username: $score очков" . PHP_EOL;
    }

    $response_text .= PHP_EOL . "Спасибо за участие! Игра окончена.";

    sendMessage($chat_id, $response_text);

    return $response_text;
}

// Функция для загрузки загадок из JSON-файла
function loadRiddles() {
    global $riddles_file;
    if (file_exists($riddles_file)) {
        $content = file_get_contents($riddles_file);
        return json_decode($content, true);
    }
    return [];
}

// Функция для сохранения загадок в JSON-файл
function saveRiddles($riddles): void
{
    global $riddles_file;
    if (file_put_contents($riddles_file, json_encode($riddles, JSON_PRETTY_PRINT)) === false) {
        error_log("Ошибка при записи в файл: $riddles_file");
    }
}

// Функция для добавления новой загадки
function addRiddle($emoji, $fact): void
{
    $riddles = loadRiddles();
    $riddles[$emoji] = $fact;
    saveRiddles($riddles);
}

// Функция для удаления загадки
function deleteRiddle($emoji): bool
{
    $riddles = loadRiddles();
    if (isset($riddles[$emoji])) {
        unset($riddles[$emoji]);
        saveRiddles($riddles);
        return true;
    }
    return false;
}

// Функция для получения списка всех загадок
function listRiddles(): string
{
    $riddles = loadRiddles();
    $list = "Список загадок:\n\n";
    foreach ($riddles as $emoji => $fact) {
        $list .= "$emoji - $fact\n";
    }
    return $list;
}
