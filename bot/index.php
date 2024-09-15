<?php
// Устанавливаем временную зону (замените на нужную)
date_default_timezone_set('Europe/Moscow');

// Получаем текущую дату и время
$current_time = date('H:i');
$current_date = date('d.m.Y');
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сайт Панченко Дарьи | Личная страница</title>
    <meta name="description" content="Личный сайт Панченко Дарьи. Узнайте больше о Дарье и ее деятельности.">
    <meta name="keywords" content="Панченко Дарья, личный сайт, портфолио">
    <meta name="author" content="Панченко Дарья">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://altyysha.com/">
    <meta property="og:title" content="Сайт Панченко Дарьи | Личная страница">
    <meta property="og:description" content="Личный сайт Панченко Дарьи. Узнайте больше о Дарье и ее деятельности.">
    <meta property="og:image" content="https://altyysha.com/image.jpg">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://altyysha.com/">
    <meta property="twitter:title" content="Сайт Панченко Дарьи | Личная страница">
    <meta property="twitter:description"
          content="Личный сайт Панченко Дарьи. Узнайте больше о Дарье и ее деятельности.">
    <meta property="twitter:image" content="https://altyysha.com/image.jpg">

    <link rel="canonical" href="https://altyysha.com/">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #ffe6f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            text-align: center;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #ff69b4;
            font-size: 28px;
            margin-bottom: 20px;
        }

        p {
            color: #8e8e8e;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .heart {
            color: #ff69b4;
            font-size: 24px;
            animation: pulse 1s infinite;
        }

        .time {
            font-size: 24px;
            font-weight: bold;
            margin-top: 20px;
            color: #8C5361;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Сайт Панченко Дарьи</h1>
    <p>Это будет сайт Дашеньки, но чуть позже <span class="heart">&hearts;</span></p>
    <p>Сейчас у Дарьи:</p>
    <p class="time"><?php echo $current_date; ?> <?php echo $current_time; ?></p>
</div>
</body>
<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
    (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();
        for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
        k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
    (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

    ym(98341115, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true
    });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/98341115" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
</html>
