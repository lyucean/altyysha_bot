<?php

function loadEnv($path): void
{
    if(!file_exists($path)) {
        throw new Exception(".env file not found");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// Функция для отправки сообщений
function logs($text): void
{
//    print_r('<br> ' );
    print_r(date('H:i:s:u ') . $text . PHP_EOL);
}

// Функция для удаления вебхука
function deleteWebhook($token): bool
{
    $url = "https://api.telegram.org/bot$token/deleteWebhook";

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
}