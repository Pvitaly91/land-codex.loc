<?php
declare(strict_types=1);

session_start();

$loadEnv = static function (string $file): array {
    if (!is_file($file)) {
        return [];
    }

    $lines = @file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return [];
    }

    $stripQuotes = static function (string $value): string {
        $length = strlen($value);
        if ($length >= 2) {
            $first = $value[0];
            $last = $value[$length - 1];
            if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                return substr($value, 1, -1);
            }
        }

        return $value;
    };

    $variables = [];

    foreach ($lines as $line) {
        $trimmed = trim($line);

        if ($trimmed === '') {
            continue;
        }

        $firstCharacter = $trimmed[0];
        if ($firstCharacter === '#' || $firstCharacter === ';') {
            continue;
        }

        $parts = explode('=', $line, 2);
        if (count($parts) !== 2) {
            continue;
        }

        $name = trim($parts[0]);
        if ($name === '') {
            continue;
        }

        $value = $stripQuotes(trim($parts[1]));
        $variables[$name] = $value;
    }

    return $variables;
};

$envFile = __DIR__ . '/.env';
$envValues = $loadEnv($envFile);

foreach ($envValues as $name => $value) {
    if (!array_key_exists($name, $_ENV)) {
        $_ENV[$name] = $value;
    }

    if (!array_key_exists($name, $_SERVER)) {
        $_SERVER[$name] = $value;
    }

    putenv($name . '=' . $value);
}

$getEnvValue = static function (string $key, $default = null) {
    if (array_key_exists($key, $_ENV)) {
        return $_ENV[$key];
    }

    if (array_key_exists($key, $_SERVER)) {
        return $_SERVER[$key];
    }

    $value = getenv($key);
    if ($value === false) {
        return $default;
    }

    return $value;
};

$credentials = [
    'username' => 'admin',
    'password' => 'StrongPass123',
];

$rememberCookieName = 'remember_auth';
$rememberDuration = 30 * 24 * 60 * 60; // 30 days
$secretKey = 'pRjFf3SxKZq8T0vL2nMhY1wB5cD9eG4u';
$css = "style";
$cssFile = __DIR__ . "/{$css}.css";
$cssVersion = file_exists($cssFile) ? (string) filemtime($cssFile) : (string) time();
$script = 'script';
$scriptFile = __DIR__ . "/{$script}.js";
$scriptVersion = file_exists($scriptFile) ? (string) filemtime($scriptFile) : (string) time();

$instagramLinks = [
    'https://www.instagram.com/dasha__english_',
    'https://www.instagram.com/dasha__pechenyuk',
];
$getRandomInstagramLink = static function () use ($instagramLinks): string {
    return $instagramLinks[array_rand($instagramLinks)];
};

$instagramHref1 = htmlspecialchars($getRandomInstagramLink(), ENT_QUOTES, 'UTF-8');
$instagramHref2 = htmlspecialchars($getRandomInstagramLink(), ENT_QUOTES, 'UTF-8');
$instagramHref3 = htmlspecialchars($getRandomInstagramLink(), ENT_QUOTES, 'UTF-8');
$instagramHref4 = htmlspecialchars($getRandomInstagramLink(), ENT_QUOTES, 'UTF-8');

$telegramLink = 'https://t.me/dasha_pechenyuk';
$telegramHref = htmlspecialchars($telegramLink, ENT_QUOTES, 'UTF-8');

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'example.com';
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$canonicalUrl = $scheme . $host . $requestUri;
$canonicalHref = htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8');
$logoUrl = $scheme . $host . '/img/logo-3.png';
$logoUrlEscaped = htmlspecialchars($logoUrl, ENT_QUOTES, 'UTF-8');

$metaTitle = 'Репетитор англійської мови онлайн — Dasha Tutor';
$metaDescription = 'Персональний репетитор англійської мови для дорослих і підлітків. Індивідуальні онлайн-уроки з розмовною практикою, підготовкою до іспитів та гнучким графіком.';
$metaKeywords = 'репетитор англійської мови, онлайн репетитор англійської, уроки англійської онлайн, індивідуальні заняття англійська';
$metaTitleEscaped = htmlspecialchars($metaTitle, ENT_QUOTES, 'UTF-8');
$metaDescriptionEscaped = htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8');
$metaKeywordsEscaped = htmlspecialchars($metaKeywords, ENT_QUOTES, 'UTF-8');

$structuredData = [
    [
        '@context' => 'https://schema.org',
        '@type' => 'Person',
        'name' => 'Dasha Pechenyuk',
        'jobTitle' => 'Репетитор англійської мови',
        'description' => 'Персональний репетитор англійської мови для дорослих та підлітків з індивідуальними онлайн-заняттями.',
        'url' => $canonicalUrl,
        'image' => $logoUrl,
        'sameAs' => [
            'https://www.instagram.com/dasha__english_',
            'https://www.instagram.com/dasha__pechenyuk',
            $telegramLink,
        ],
        'areaServed' => 'Online',
        'inLanguage' => 'uk',
        'makesOffer' => [
            '@type' => 'Offer',
            'availability' => 'https://schema.org/InStock',
            'url' => $canonicalUrl,
            'description' => 'Індивідуальне онлайн-заняття з репетитором англійської мови тривалістю 60 хвилин.',
        ],
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'contactType' => 'customer support',
            'availableLanguage' => ['uk', 'en'],
            'url' => $telegramLink,
        ],
    ],
    [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => [
            [
                '@type' => 'Question',
                'name' => 'Чому варто обрати онлайн-репетитора англійської мови?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => 'Онлайн-репетитор англійської мови адаптує заняття під ваш графік та цілі, що допомагає швидше заговорити впевнено.',
                ],
            ],
            [
                '@type' => 'Question',
                'name' => 'Скільки триває індивідуальний урок англійської?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => 'Стандартне онлайн-заняття триває 60 хвилин із балансом граматики, практики мовлення та інтерактивних завдань.',
                ],
            ],
            [
                '@type' => 'Question',
                'name' => 'Чи підходить репетитор для підготовки до іспитів?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => 'Так, програма включає підготовку до НМТ, IELTS та співбесід, з фокусом на ваші реальні цілі.',
                ],
            ],
        ],
    ],
];

$structuredDataJson = json_encode($structuredData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?: '';

$contactRecipient = (string) $getEnvValue('CONTACT_RECIPIENT', 'pvitaly91@gmail.com');
$contactSender = (string) $getEnvValue('CONTACT_SENDER', 'no-reply@dashatutor.com.ua');
$smtpConfig = [
    'host' => (string) $getEnvValue('SMTP_HOST', 'smtp.dashatutor.com.ua'),
    'port' => (int) $getEnvValue('SMTP_PORT', 587),
    'username' => (string) $getEnvValue('SMTP_USERNAME', 'no-reply@dashatutor.com.ua'),
    'password' => (string) $getEnvValue('SMTP_PASSWORD', 'rV5uH8rI8k'),
    'encryption' => $getEnvValue('SMTP_ENCRYPTION', 'tls'), // Supported values: 'tls', 'ssl', null
    'timeout' => (int) $getEnvValue('SMTP_TIMEOUT', 30),
];

/**
 * @param array<string, mixed> $config
 * @param array<int, string>   $headers
 */
$sendSmtpMail = static function (array $config, string $from, string $to, string $subject, string $body, array $headers = []): bool {
    $host = (string)($config['host'] ?? '');
    $port = (int)($config['port'] ?? 587);
    $username = (string)($config['username'] ?? '');
    $password = (string)($config['password'] ?? '');
    $encryption = $config['encryption'] ?? null;
    $timeout = (int)($config['timeout'] ?? 30);

    if ($host === '' || $username === '' || $password === '') {
        return false;
    }

    $transport = '';
    if ($encryption === 'ssl') {
        $transport = 'ssl://';
    } elseif ($encryption === 'tls') {
        $transport = 'tcp://';
    }

    $contextOptions = [];
    if ($encryption === 'tls') {
        $contextOptions['ssl'] = [
            'crypto_method' => STREAM_CRYPTO_METHOD_TLS_CLIENT,
            'verify_peer' => false,
            'verify_peer_name' => false,
        ];
    }

    $context = stream_context_create($contextOptions);
    $client = @stream_socket_client(
        $transport . $host . ':' . $port,
        $errno,
        $errstr,
        $timeout,
        STREAM_CLIENT_CONNECT,
        $context
    );

    if (!is_resource($client)) {
        return false;
    }

    stream_set_timeout($client, $timeout);

    $read = static function ($socket): string {
        $response = '';
        while (($line = fgets($socket, 515)) !== false) {
            $response .= $line;
            if (isset($line[3]) && $line[3] === ' ') {
                break;
            }
        }
        return $response;
    };

    $send = static function ($socket, string $command) use ($read): bool {
        $written = fwrite($socket, $command . "\r\n");
        if ($written === false) {
            return false;
        }
        $response = $read($socket);
        return (bool) preg_match('/^[23]\d{2}/', $response);
    };

    $initialResponse = $read($client);
    if (!preg_match('/^220/', $initialResponse)) {
        fclose($client);
        return false;
    }

    $domain = $_SERVER['SERVER_NAME'] ?? 'localhost';
    if (!$send($client, 'EHLO ' . $domain)) {
        fclose($client);
        return false;
    }

    if ($encryption === 'tls') {
        fwrite($client, "STARTTLS\r\n");
        $startTlsResponse = $read($client);
        if (!preg_match('/^220/', $startTlsResponse)) {
            fclose($client);
            return false;
        }
        $cryptoEnabled = stream_socket_enable_crypto($client, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
        if ($cryptoEnabled !== true) {
            fclose($client);
            return false;
        }
        if (!$send($client, 'EHLO ' . $domain)) {
            fclose($client);
            return false;
        }
    }

    if (!$send($client, 'AUTH LOGIN')) {
        fclose($client);
        return false;
    }

    if (!$send($client, base64_encode($username))) {
        fclose($client);
        return false;
    }

    if (!$send($client, base64_encode($password))) {
        fclose($client);
        return false;
    }

    if (!$send($client, 'MAIL FROM:<' . $from . '>')) {
        fclose($client);
        return false;
    }

    if (!$send($client, 'RCPT TO:<' . $to . '>')) {
        fclose($client);
        return false;
    }

    if (fwrite($client, "DATA\r\n") === false) {
        fclose($client);
        return false;
    }

    $dataResponse = $read($client);
    if (!preg_match('/^354/', $dataResponse)) {
        fclose($client);
        return false;
    }

    $headers = array_merge([
        'Date: ' . gmdate('D, d M Y H:i:s') . ' +0000',
        'From: ' . $from,
        'To: ' . $to,
        'Subject: ' . $subject,
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8',
    ], $headers);

    $bodyNormalized = preg_replace("/(\r\n|\r|\n)/", "\r\n", $body) ?? $body;
    $bodySafe = preg_replace('/^\./m', '..', $bodyNormalized) ?? $bodyNormalized;
    $headersString = implode("\r\n", $headers);
    $message = $headersString . "\r\n\r\n" . $bodySafe . "\r\n.";

    if (fwrite($client, $message . "\r\n") === false) {
        fclose($client);
        return false;
    }

    $finalResponse = $read($client);
    $success = preg_match('/^250/', $finalResponse) === 1;
    $send($client, 'QUIT');
    fclose($client);

    return $success;
};

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_form'])) {
    $name = trim((string)($_POST['name'] ?? ''));
    $contact = trim((string)($_POST['contact'] ?? ''));
    $message = trim((string)($_POST['message'] ?? ''));

    $sanitize = static function (string $value): string {
        return preg_replace('/[\r\n]+/', ' ', $value);
    };

    $length = static function (string $value): int {
        return function_exists('mb_strlen') ? mb_strlen($value) : strlen($value);
    };

    $errors = [];
    $spamWindowSeconds = 5 * 60;
    $now = time();
    $lastSubmission = isset($_SESSION['contact_last_submit']) ? (int) $_SESSION['contact_last_submit'] : 0;

    if ($name === '' || $length($name) < 2) {
        $errors['name'] = "Будь ласка, вкажіть ім'я (мінімум 2 символи).";
    }

    if ($contact === '' || $length($contact) < 5) {
        $errors['contact'] = 'Будь ласка, залиште номер телефону, посилання чи нік.';
    }

    if ($message !== '' && $length($message) > 1500) {
        $errors['message'] = 'Повідомлення занадто довге. Скоротіть його до 1500 символів.';
    }

    header('Content-Type: application/json; charset=UTF-8');

    if ($errors !== []) {
        echo json_encode([
            'success' => false,
            'errors' => $errors,
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($lastSubmission !== 0 && ($now - $lastSubmission) < $spamWindowSeconds) {
        $retryAfter = $spamWindowSeconds - ($now - $lastSubmission);
        http_response_code(429);
        echo json_encode([
            'success' => false,
            'message' => 'Ви вже надсилали заявку нещодавно. Будь ласка, спробуйте ще раз через 5 хвилин.',
            'retry_after' => $retryAfter,
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $name = $sanitize($name);
    $contact = $sanitize($contact);
    $message = $sanitize($message);

    $subject = 'Нова заявка з сайту Dasha Tutor';
    $emailBody = "Ім'я: {$name}\nКонтакт: {$contact}\nПовідомлення: " . ($message !== '' ? $message : '—');
    $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
    $headers = [
        'Reply-To: ' . $contactSender,
    ];

    $mailSent = $sendSmtpMail($smtpConfig, $contactSender, $contactRecipient, $encodedSubject, $emailBody, $headers);

    if ($mailSent) {
        $_SESSION['contact_last_submit'] = $now;
        echo json_encode([
            'success' => true,
            'message' => 'Дякуємо! Я звʼяжуся з вами найближчим часом.',
        ], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Не вдалося відправити повідомлення. Спробуйте знову пізніше.',
        ], JSON_UNESCAPED_UNICODE);
    }

    exit;
}

$landingPageHtml = <<<HTML
<!DOCTYPE html>
<html lang="uk">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{$metaTitleEscaped}</title>
  <meta name="description" content="{$metaDescriptionEscaped}" />
  <meta name="keywords" content="{$metaKeywordsEscaped}" />
  <meta name="author" content="Dasha Pechenyuk" />
  <meta name="robots" content="index, follow" />
  <link rel="canonical" href="{$canonicalHref}" />
  <link rel="alternate" hreflang="uk" href="{$canonicalHref}" />
  <meta property="og:title" content="{$metaTitleEscaped}" />
  <meta property="og:description" content="{$metaDescriptionEscaped}" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="{$canonicalHref}" />
  <meta property="og:image" content="{$logoUrlEscaped}" />
  <meta property="og:locale" content="uk_UA" />
  <meta property="og:site_name" content="Dasha Tutor" />
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="{$metaTitleEscaped}" />
  <meta name="twitter:description" content="{$metaDescriptionEscaped}" />
  <meta name="twitter:image" content="{$logoUrlEscaped}" />
  <script type="application/ld+json">{$structuredDataJson}</script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

  <link href="{$css}.css?v={$cssVersion}" rel="stylesheet">
  <script src="script.js?v={$scriptVersion}" defer></script>
</head>
<body class="font-sans text-stone-800 antialiased">

  <!-- Wrapper: fluid on mobile/tablet, exact width on desktop -->
  <main class="w-full xl:w-[1443px] mx-auto">
    <!-- Header: fluid on mobile/tablet, exact width on desktop -->
    <header class="sticky top-0 z-30 bg-white/22  border-b border-stone-200 mx-auto w-full  xl:w-[1100px] lg:h-[110px]" data-scroll>
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-[80px] sm:h-[80px] lg:h-[110px]" >
           <button id="menu-button-burger" type="button" class="hidden max-[930px]:block inline-flex items-center justify-center rounded-xl border px-3 py-2" aria-label="Menu" aria-controls="mnav" aria-expanded="false">
              <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h16M3 11h16M3 16h16"/></svg>
            </button>
          <a href="#" class="flex items-center gap-2 group select-none w-[192px] h-[43px]" aria-label="Dasha Tutor">
            <img src="img/logo-3.png" alt="Dasha Tutor — репетитор англійської мови" loading="lazy">
          </a>

          <!-- Desktop Nav -->
          <nav class="hidden md:flex items-center gap-6 lg:gap-8 text-[20px] ">
            <a class="hover:text-stone-900 " href="#english-tutor">Про мене</a>
            <a class="hover:text-stone-900 " href="#services">Послуги</a>
            <a class="hover:text-stone-900 " href="#faq">FAQ</a>
            <a class="hover:text-stone-900 " href="#travels">Подорожі</a>
            <a class="hover:text-stone-900 " href="#approach">Мій підхід</a>
          </nav>

          <!-- CTA + Burger -->
          <div class="flex items-center gap-3">
            <a href="#signup" class="order-lesson" aria-label="Записатись на урок">
              <span class="order-lesson__icon" aria-hidden="true">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M4 19.5V5.75A1.75 1.75 0 0 1 5.75 4h12.5A1.75 1.75 0 0 1 20 5.75V19.5l-8-3.5-8 3.5Z" />
                </svg>
              </span>
              <span class="order-lesson__text">Записатись на урок</span>
            </a>

          </div>
        </div>
      </div>

      <!-- Mobile Nav -->
      <div id="mnav" class=" border-t border-stone-200 top-0" style="position: absolute; width: 250px;  top:80px; left: 0px;;" >
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-3 grid gap-2 text-[19px]">
          <a class="py-2" href="#english-tutor">Про мене</a>
          <a class="py-2" href="#services">Послуги</a>
          <a class="py-2" href="#faq">FAQ</a>
          <a class="py-2" href="#travels">Подорожі</a>
          <a class="py-2" href="#approach">Мій підхід</a>

          <div class="mt-4 border-t border-stone-200 pt-4 text-[15px] leading-[1.5] text-stone-700">
           
            <div class="flex flex-col gap-2">
              <a
                href="{$instagramHref1}"
                class="social-link hover:text-[#7a555b]"
                target="_blank"
                rel="noopener noreferrer"
              >
                <img src="img/icon-instagram.svg" alt="" class="social-link__icon" aria-hidden="true" />
                <span>Instagram</span>
              </a>
              <a
                href="{$telegramHref}"
                class="social-link hover:text-[#7a555b]"
                target="_blank"
                rel="noopener noreferrer"
              >
                <img src="img/icon-telegram.svg" alt="" class="social-link__icon" aria-hidden="true" />
                <span>Telegram</span>
              </a>
            </div>
          </div>

        </div>
      </div>
    </header>

    <!-- Hero -->
    <!-- Keep the original negative offset ONLY on desktop to match exact layout -->
    <section id="main-left-block" class="relative -mt-0 -mt-[110px] h-[815px]" style="background-color: #ebdcd6;" data-scroll>
      <div class="mx-auto max-w-7xl grid lg:grid-cols-2" >
      
        <div class=" lg:mt-[170px]" id="main-block-text" data-scroll>
          <div class="inner_text_block">
            <h1 class="main reveal-child-left" data-scroll-child>
              <strong>Відкрийте світ англійської</strong>
              <span>— легко та з задоволенням</span>
            </h1>

            <p class="text-block" data-scroll-child>
              Привіт, я — Даша! Пропоную персоналізовані онлайн‑уроки, що допоможуть вам впевнено заговорити англійською.
              Давайте досягати ваших мовних цілей разом!
            </p>

            <a href="#signup" id="try-first-leson" class="reveal-child-left" data-scroll-child>
              Спробувати перший урок безкоштовно
            </a>

            <div class="hidden md:flex items-center gap-3 mt-8 text-[16px] text-[#4b5563]" data-scroll-child>
          
              <div class="flex items-center gap-4" id="social_block">
                <a
                  href="{$instagramHref2}"
                  class="social-link hover:text-[#7a555b] underline-offset-4 hover:underline"
                  target="_blank"
                  rel="noopener noreferrer"
                >
                  <img src="img/icon-instagram.svg" alt="" class="social-link__icon" aria-hidden="true" />
                  <span>Instagram</span>
                </a>
                <a
                  href="{$telegramHref}"
                  class="social-link hover:text-[#7a555b] underline-offset-4 hover:underline"
                  target="_blank"
                  rel="noopener noreferrer"
                >
                  <img src="img/icon-telegram.svg" alt="" class="social-link__icon" aria-hidden="true" />
                  <span>Telegram</span>
                </a>
              </div>
            </div>

          </div>
        </div>
        <div class="relative order-first lg:order-none" id="main-photo" data-scroll>
        
        </div>
        <!-- Right (photo) -->
       
      </div>
    </section>
    <section id="english-tutor" class="bg-white py-12 md:py-16" data-scroll>
      <div class="mx-auto max-w-7xl px-6 mt-8">
        <h2 class="title" data-scroll-child>
          Репетитор англійської мови онлайн для вашого впевненого прогресу
        </h2>
    
        <p class="section-body  mt-8" data-scroll-child>
          Працюю з тими, хто хоче говорити англійською без пауз і страху. Індивідуальні плани та підтримка між уроками допомагають
          швидко побачити результат, незалежно від стартового рівня.
        </p>
        <div class="mt-8 grid gap-6 md:grid-cols-2 " data-scroll-child>
          <div class="section-card rounded-3xl border border-[#f1e3dd] bg-[#fdfaf8] p-6 shadow-sm">
            <h3 class="section-card__title">Для дорослих</h3>
            <p class="section-card__text">
              Допомагаю прокачати бізнес-англійську, підготуватися до співбесіди та впевнено презентувати себе міжнародним партнерам.
            </p>
          </div>
          <div class="section-card rounded-3xl border border-[#e3f0f6] bg-[#f7fbff] p-6 shadow-sm">
            <h3 class="section-card__title">Для підлітків</h3>
            <p class="section-card__text">
              Підтягнемо шкільну програму, розберемо складні теми та підготуємося до іспитів із підтримкою репетитора англійської мови.
            </p>
          </div>
        </div>
        <div class="section-list-slider slider-container mt-8" data-slider data-scroll-child>
          <ul class="section-list slider-track grid gap-4 md:grid-cols-2">
            <li class="flex items-start gap-3 rounded-3xl bg-[#f3f0ef] p-4">
              <span aria-hidden="true" class="section-list__icon mt-1">★</span>
              <span class="section-list__text">Аналізую ваші цілі та створюю персональну траєкторію навчання.</span>
            </li>
            <li class="flex items-start gap-3 rounded-3xl bg-[#eef6f3] p-4">
              <span aria-hidden="true" class="section-list__icon mt-1">★</span>
              <span class="section-list__text">Поєдную розмовну практику, граматику та лексику в кожному уроці.</span>
            </li>
            <li class="flex items-start gap-3 rounded-3xl bg-[#f5f8ff] p-4">
              <span aria-hidden="true" class="section-list__icon mt-1">★</span>
              <span class="section-list__text">Надаю інтерактивні матеріали й записи занять для повторення.</span>
            </li>
            <li class="flex items-start gap-3 rounded-3xl bg-[#fff6f8] p-4">
              <span aria-hidden="true" class="section-list__icon mt-1">★</span>
              <span class="section-list__text">Супроводжую вас до досягнення результату як особистий репетитор англійської мови.</span>
            </li>
          </ul>
          <button class="slider-arrow left" type="button" aria-label="Попередній">&#10094;</button>
          <button class="slider-arrow right" type="button" aria-label="Наступний">&#10095;</button>
          <div class="slider-dots" role="tablist" aria-label="Перемикання ключових переваг"></div>
        </div>
        <div class="mt-10 text-center" data-scroll-child>
          <a href="#signup" class="inline-flex items-center justify-center rounded-md  bg-[#7a555b] px-8 py-3 text-lg font-medium text-white shadow-md transition hover:bg-[#6b4950]">
            Записатися до репетитора
          </a>
        </div>
      </div>
    </section>
  
    <section id="services" class="features" data-scroll>
      <h2 class="title" data-scroll-child>Що чекає на вас на наших заняттях?</h2>
      <p class="text" data-scroll-child>Комплексний підхід до ваших цілей від досвідченого репетитора англійської мови.</p>
      <div class="slider-container" data-slider>
      <div class="blocks slider-track">
        <div class="card reveal-child-zoom" data-scroll-child>
        
<h3> <svg xmlns="http://www.w3.org/2000/svg" style="display:inline-block; margin-right: 14px" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 31 31" width="31" height="31" fill="none">
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="31" height="31" fill="#ef7e8f" x="0" y="0" opacity="100%">
    <path d="M416 176C416 78.8 322.9 0 208 0S0 78.8 0 176c0 39.57 15.62 75.96 41.67 105.4c-16.39 32.76-39.23 57.32-39.59 57.68c-2.1 2.205-2.67 5.475-1.441 8.354C1.9 350.3 4.602 352 7.66 352c38.35 0 70.76-11.12 95.74-24.04C134.2 343.1 169.8 352 208 352C322.9 352 416 273.2 416 176zM599.6 443.7C624.8 413.9 640 376.6 640 336C640 238.8 554 160 448 160c-.3145 0-.6191 .041-.9336 .043C447.5 165.3 448 170.6 448 176c0 98.62-79.68 181.2-186.1 202.5C282.7 455.1 357.1 512 448 512c33.69 0 65.32-8.008 92.85-21.98C565.2 502 596.1 512 632.3 512c3.059 0 5.76-1.725 7.02-4.605c1.229-2.879 .6582-6.148-1.441-8.354C637.6 498.7 615.9 475.3 599.6 443.7z"></path>
  </svg>
  <defs>
    <filter id="filter_dshadow_10_0_2_0000001a" color-interpolation-filters="sRGB" filterUnits="userSpaceOnUse">
      <feFlood flood-opacity="0" result="bg-fix"></feFlood>
      <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="alpha"></feColorMatrix>
      <feOffset dx="0" dy="2"></feOffset>
      <feGaussianBlur stdDeviation="5"></feGaussianBlur>
      <feComposite in2="alpha" operator="out"></feComposite>
      <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.1 0"></feColorMatrix>
      <feBlend mode="normal" in2="bg-fix" result="bg-fix-filter_dshadow_10_0_2_0000001a"></feBlend>
      <feBlend in="SourceGraphic" in2="bg-fix-filter_dshadow_10_0_2_0000001a" result="shape"></feBlend>
    </filter>
  </defs>
</svg> Покращення навичок спілкування</h3>
          <p>Акцент на розмовній практиці для подолання мовного бар'єру.</p>
        </div>
         <div class="card reveal-child-zoom" style="background-color: #e5e7eb;" data-scroll-child>
          <h3>
<svg xmlns="http://www.w3.org/2000/svg" style="display:inline-block; margin-right: 14px" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 34 36" width="34" height="36" fill="none">
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="34" height="34" fill="#b0d0f1" x="0" y="1" opacity="100%">
    <path d="M542.22 32.05c-54.8 3.11-163.72 14.43-230.96 55.59-4.64 2.84-7.27 7.89-7.27 13.17v363.87c0 11.55 12.63 18.85 23.28 13.49 69.18-34.82 169.23-44.32 218.7-46.92 16.89-.89 30.02-14.43 30.02-30.66V62.75c.01-17.71-15.35-31.74-33.77-30.7zM264.73 87.64C197.5 46.48 88.58 35.17 33.78 32.05 15.36 31.01 0 45.04 0 62.75V400.6c0 16.24 13.13 29.78 30.02 30.66 49.49 2.6 149.59 12.11 218.77 46.95 10.62 5.35 23.21-1.94 23.21-13.46V100.63c0-5.29-2.62-10.14-7.27-12.99z"></path>
  </svg>
  <defs>
    <filter id="filter_dshadow_0_0_0_00000014" color-interpolation-filters="sRGB" filterUnits="userSpaceOnUse">
      <feFlood flood-opacity="0" result="bg-fix"></feFlood>
      <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="alpha"></feColorMatrix>
      <feOffset dx="0" dy="0"></feOffset>
      <feGaussianBlur stdDeviation="0"></feGaussianBlur>
      <feComposite in2="alpha" operator="out"></feComposite>
      <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.08 0"></feColorMatrix>
      <feBlend mode="normal" in2="bg-fix" result="bg-fix-filter_dshadow_0_0_0_00000014"></feBlend>
      <feBlend in="SourceGraphic" in2="bg-fix-filter_dshadow_0_0_0_00000014" result="shape"></feBlend>
    </filter>
  </defs>
</svg>Глибокий розбір граматики</h3>
          <p>Просте пояснення складних правил із закріпленням на практиці.</p>
        </div>
         <div class="card reveal-child-zoom" style="background-color: #E6F5F2;" data-scroll-child>
          <h3>
<svg xmlns="http://www.w3.org/2000/svg"  style="display:inline-block; margin-right: 14px" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 38 36" width="38" height="36" fill="none">
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="36" height="36" fill="#f9e6a4" x="1" y="0" opacity="100%">
    <path d="M152.1 236.2c-3.5-12.1-7.8-33.2-7.8-33.2h-.5s-4.3 21.1-7.8 33.2l-11.1 37.5H163zM616 96H336v320h280c13.3 0 24-10.7 24-24V120c0-13.3-10.7-24-24-24zm-24 120c0 6.6-5.4 12-12 12h-11.4c-6.9 23.6-21.7 47.4-42.7 69.9 8.4 6.4 17.1 12.5 26.1 18 5.5 3.4 7.3 10.5 4.1 16.2l-7.9 13.9c-3.4 5.9-10.9 7.8-16.7 4.3-12.6-7.8-24.5-16.1-35.4-24.9-10.9 8.7-22.7 17.1-35.4 24.9-5.8 3.5-13.3 1.6-16.7-4.3l-7.9-13.9c-3.2-5.6-1.4-12.8 4.2-16.2 9.3-5.7 18-11.7 26.1-18-7.9-8.4-14.9-17-21-25.7-4-5.7-2.2-13.6 3.7-17.1l6.5-3.9 7.3-4.3c5.4-3.2 12.4-1.7 16 3.4 5 7 10.8 14 17.4 20.9 13.5-14.2 23.8-28.9 30-43.2H412c-6.6 0-12-5.4-12-12v-16c0-6.6 5.4-12 12-12h64v-16c0-6.6 5.4-12 12-12h16c6.6 0 12 5.4 12 12v16h64c6.6 0 12 5.4 12 12zM0 120v272c0 13.3 10.7 24 24 24h280V96H24c-13.3 0-24 10.7-24 24zm58.9 216.1L116.4 167c1.7-4.9 6.2-8.1 11.4-8.1h32.5c5.1 0 9.7 3.3 11.4 8.1l57.5 169.1c2.6 7.8-3.1 15.9-11.4 15.9h-22.9a12 12 0 0 1-11.5-8.6l-9.4-31.9h-60.2l-9.1 31.8c-1.5 5.1-6.2 8.7-11.5 8.7H70.3c-8.2 0-14-8.1-11.4-15.9z"></path>
  </svg>
  <defs>
    <filter id="filter_dshadow_0_0_0_00000014" color-interpolation-filters="sRGB" filterUnits="userSpaceOnUse">
      <feFlood flood-opacity="0" result="bg-fix"></feFlood>
      <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="alpha"></feColorMatrix>
      <feOffset dx="0" dy="0"></feOffset>
      <feGaussianBlur stdDeviation="0"></feGaussianBlur>
      <feComposite in2="alpha" operator="out"></feComposite>
      <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.08 0"></feColorMatrix>
      <feBlend mode="normal" in2="bg-fix" result="bg-fix-filter_dshadow_0_0_0_00000014"></feBlend>
      <feBlend in="SourceGraphic" in2="bg-fix-filter_dshadow_0_0_0_00000014" result="shape"></feBlend>
    </filter>
  </defs>
</svg>Розширення словникового запасу</h3>
          <p>Вивчення актуальної лексики у сферах, що цікавлять саме вас.</p>
        </div>

             <div class="card reveal-child-zoom" style="background-color: #D6EBE4;" data-scroll-child>
          <h3>
<svg xmlns="http://www.w3.org/2000/svg" style="display:inline-block; margin-right: 14px" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 34 34" width="34" height="34" fill="none">
  <svg xmlns="http://www.w3.org/2000/svg" height="34" width="34" viewBox="0 0 24 24" fill="#a2d9c9" x="0" y="0" opacity="100%">
    <path fill="none" d="M0 0h24v24H0z"></path>
    <path d="M12 3a9 9 0 0 0-9 9v7c0 1.1.9 2 2 2h4v-8H5v-1c0-3.87 3.13-7 7-7s7 3.13 7 7v1h-4v8h4c1.1 0 2-.9 2-2v-7a9 9 0 0 0-9-9z"></path>
  </svg>
  <defs>
    <filter id="filter_dshadow_10_0_2_0000001a" color-interpolation-filters="sRGB" filterUnits="userSpaceOnUse">
      <feFlood flood-opacity="0" result="bg-fix"></feFlood>
      <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="alpha"></feColorMatrix>
      <feOffset dx="0" dy="2"></feOffset>
      <feGaussianBlur stdDeviation="5"></feGaussianBlur>
      <feComposite in2="alpha" operator="out"></feComposite>
      <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.1 0"></feColorMatrix>
      <feBlend mode="normal" in2="bg-fix" result="bg-fix-filter_dshadow_10_0_2_0000001a"></feBlend>
      <feBlend in="SourceGraphic" in2="bg-fix-filter_dshadow_10_0_2_0000001a" result="shape"></feBlend>
    </filter>
  </defs>
</svg>Сприйняття на слух (Auditory)</h3>
          <p>Робота з аудіо- та відеоматеріалами для кращого розуміння мови.</p>
        </div>
         <div class="card reveal-child-zoom" style="background-color: #F5FBE6;" data-scroll-child>
          <h3>
<svg xmlns="http://www.w3.org/2000/svg" style="display:inline-block; margin-right: 14px" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 38 36" width="38" height="36" fill="none">
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="36" height="36" fill="#f9e6a4" x="1" y="0" opacity="100%">
    <path d="M208 352c-2.39 0-4.78.35-7.06 1.09C187.98 357.3 174.35 360 160 360c-14.35 0-27.98-2.7-40.95-6.91-2.28-.74-4.66-1.09-7.05-1.09C49.94 352-.33 402.48 0 464.62.14 490.88 21.73 512 48 512h224c26.27 0 47.86-21.12 48-47.38.33-62.14-49.94-112.62-112-112.62zm-48-32c53.02 0 96-42.98 96-96s-42.98-96-96-96-96 42.98-96 96 42.98 96 96 96zM592 0H208c-26.47 0-48 22.25-48 49.59V96c23.42 0 45.1 6.78 64 17.8V64h352v288h-64v-64H384v64h-76.24c19.1 16.69 33.12 38.73 39.69 64H592c26.47 0 48-22.25 48-49.59V49.59C640 22.25 618.47 0 592 0z"></path>
  </svg>
  <defs>
    <filter id="filter_dshadow_0_0_0_00000014" color-interpolation-filters="sRGB" filterUnits="userSpaceOnUse">
      <feFlood flood-opacity="0" result="bg-fix"></feFlood>
      <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="alpha"></feColorMatrix>
      <feOffset dx="0" dy="0"></feOffset>
      <feGaussianBlur stdDeviation="0"></feGaussianBlur>
      <feComposite in2="alpha" operator="out"></feComposite>
      <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.08 0"></feColorMatrix>
      <feBlend mode="normal" in2="bg-fix" result="bg-fix-filter_dshadow_0_0_0_00000014"></feBlend>
      <feBlend in="SourceGraphic" in2="bg-fix-filter_dshadow_0_0_0_00000014" result="shape"></feBlend>
    </filter>
  </defs>
</svg>Підготовка до НМТ та іспитів</h3>
          <p>Просте пояснення складних правил із закріпленням на практиці.</p>
        </div>
         <div class="card reveal-child-zoom" style="background-color: #F3DFE0;" data-scroll-child>
          <h3>
<svg xmlns="http://www.w3.org/2000/svg" style="display:inline-block; margin-right: 14px" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 38 36" width="38" height="36" fill="none">
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="36" height="36" fill="#f9b8d1" x="1" y="0" opacity="100%">
    <path d="M192 256c61.9 0 112-50.1 112-112S253.9 32 192 32 80 82.1 80 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C51.6 288 0 339.6 0 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zM480 256c53 0 96-43 96-96s-43-96-96-96-96 43-96 96 43 96 96 96zm48 32h-3.8c-13.9 4.8-28.6 8-44.2 8s-30.3-3.2-44.2-8H432c-20.4 0-39.2 5.9-55.7 15.4 24.4 26.3 39.7 61.2 39.7 99.8v38.4c0 2.2-.5 4.3-.6 6.4H592c26.5 0 48-21.5 48-48 0-61.9-50.1-112-112-112z"></path>
  </svg>
  <defs>
    <filter id="filter_dshadow_0_0_0_00000014" color-interpolation-filters="sRGB" filterUnits="userSpaceOnUse">
      <feFlood flood-opacity="0" result="bg-fix"></feFlood>
      <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="alpha"></feColorMatrix>
      <feOffset dx="0" dy="0"></feOffset>
      <feGaussianBlur stdDeviation="0"></feGaussianBlur>
      <feComposite in2="alpha" operator="out"></feComposite>
      <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.08 0"></feColorMatrix>
      <feBlend mode="normal" in2="bg-fix" result="bg-fix-filter_dshadow_0_0_0_00000014"></feBlend>
      <feBlend in="SourceGraphic" in2="bg-fix-filter_dshadow_0_0_0_00000014" result="shape"></feBlend>
    </filter>
  </defs>
</svg>Індивідуальні та парні заняття</h3>
          <p>Можливість займатися індивідуально або в парі з другом.</p>
        </div>
      </div>
      <button class="slider-arrow left" type="button" aria-label="Попередній">&#10094;</button>
      <button class="slider-arrow right" type="button" aria-label="Наступний">&#10095;</button>
      <div class="slider-dots" role="tablist" aria-label="Перемикання послуг"></div>
      </div>
    </section>




    <section id="faq" class="bg-[#f8f4f2] py-12 md:py-16" data-scroll>
      <div class="mx-auto max-w-7xl px-6">
        <h2 class="title" data-scroll-child>
          Часті питання про репетитора англійської мови
        </h2>
        <p class="section-body text-center"  data-scroll-child>
          Зібрала відповіді на питання, які найчастіше отримую від майбутніх студентів. Якщо чогось бракує — напишіть мені, і я
          з радістю підкажу.
        </p>
        <div class="mt-8 space-y-4" data-scroll-child>
          <details class="group rounded-3xl bg-white p-6 shadow-sm">
            <summary class="faq__summary">
              <span class="faq__summary-text">Які результати дають заняття з репетитором англійської мови?</span>
              <span class="faq__icon" aria-hidden="true"></span>
            </summary>
            <div class="faq__content">
              <p class="faq__answer text-[19px]">
                Уже за кілька тижнів ви відчуєте, що говорите вільніше, краще розумієте носіїв та будуєте фрази без перекладу. Ми
                працюємо над вимовою, словником і впевненістю.
              </p>
            </div>
          </details>
          <details class="group rounded-3xl bg-white p-6 shadow-sm">
            <summary class="faq__summary">
              <span class="faq__summary-text">Як забронювати індивідуальний урок?</span>
              <span class="faq__icon" aria-hidden="true"></span>
            </summary>
            <div class="faq__content">
              <p class="faq__answer text-[19px]">
                Онлайн-заняття триває 60 хвилин. Щоб забронювати час, залиште заявку через форму на сайті або напишіть мені в Telegram —
                я відповім протягом дня.
              </p>
            </div>
          </details>
          <details class="group rounded-3xl bg-white p-6 shadow-sm">
            <summary class="faq__summary">
              <span class="faq__summary-text">Чи можна поєднувати індивідуальні заняття з підготовкою до іспиту?</span>
              <span class="faq__icon" aria-hidden="true"></span>
            </summary>
            <div class="faq__content">
              <p class="faq__answer text-[19px]">
                Так, я адаптую програму під конкретний іспит — від НМТ до IELTS. Ви отримаєте план дій, тренування говоріння та
                письма, а також перевірені матеріали для самостійної практики.
              </p>
            </div>
          </details>
        </div>
      </div>
    </section>

<section id="approach" class="result bg-white py-12 md:py-20" data-scroll>
  <div class="mx-auto max-w-7xl px-6 flex flex-col md:flex-row items-center md:items-start gap-10">
    <!-- Ліва колонка -->
    <div class="w-full md:w-2/5 text-center md:text-left">
      <h3 class="section-heading section-heading--left reveal-child-left" data-scroll-child>
        Мій підхід — ваш результат
      </h3>
    </div>

    <!-- Права колонка -->
    <div class="w-full md:w-3/5 space-y-5 text-center md:text-left">
      <p class="section-body section-body--accent" data-scroll-child>
        <span class="section-body__highlight">✓ Індивідуальний план:</span>
        Програма формується на основі вашого рівня, цілей та побажань.
      </p>
      <p class="section-body section-body--accent reveal-child-left" data-scroll-child>
        <span class="section-body__highlight">✓ Дружня атмосфера:</span>
        Заняття проходять у невимушеній обстановці.
      </p>
      <p class="section-body section-body--accent reveal-child-right" data-scroll-child>
        <span class="section-body__highlight">✓ Гнучкість:</span>
        Займаємося онлайн у зручний час. Можливі індивідуальні та парні уроки.
      </p>
    </div>
  </div>
</section>


<!-- Travels (responsive, matches screenshot layout) -->
<section id="travels" class="bg-[#FEF6DB] py-12 sm:py-16" data-scroll>
  <div class="mx-auto w-full xl:w-[1300px] px-6 md:px-8 grid grid-cols-1 md:grid-cols-2 gap-10">
    <!-- Left column: top image, bottom title + text -->
    <div class="flex flex-col gap-6">
      <img
        src="img/Dasha-Paris-marge.jpg"
        alt="Даша у Парижі біля Ейфелевої вежі"
        class="w-full h-auto rounded-md shadow-md reveal-child-zoom"
        data-scroll-child
      />
      <div>
        <h3 class="text-[#595236] text-[26px] sm:text-[32px] leading-[1.25] font-medium reveal-child-left" data-scroll-child>
          Занурюйтесь у культуру, а не просто будьте туристом
        </h3>
        <p class="mt-4 text-[#4B5563] text-[20px] sm:text-[20px] leading-[1.55]" data-scroll-child>
          Англійська дозволяє вийти за межі стандартних маршрутів. Розумійте розповіді
          місцевих гідів, спілкуйтеся з новими людьми, дізнавайтеся про їхні традиції
          та отримуйте поради, яких не знайти в путівниках. Саме так народжуються
          найяскравіші спогади.
        </p>
      </div>
    </div>

    <!-- Right column: top title + text, bottom image -->
    <div class="flex flex-col gap-6 md:pt-4">
        <div>
          <h3 class="text-[#595236] text-[26px] sm:text-[32px] leading-[1.25] font-medium reveal-child-right" data-scroll-child>
            Відчуйте впевненість у кожному кроці
          </h3>
          <p class="mt-4 text-[#4B5563] text-[20px] sm:text-[20px] leading-[1.55] reveal-child-left" data-scroll-child>
            Забудьте про мовні бар'єри та невпевненість. З англійською ви зможете легко
            забронювати готель, замовити саме ту страву, яку хочеться, чи просто запитати
            дорогу у перехожого. Це дарує відчуття справжньої незалежності та спокою в будь-якій країні.
          </p>
        </div>
        <img
          src="img/efes.jpg"
          alt="Подорож Ефес"
          class="w-full h-auto rounded-md shadow-md md:max-w-[570px] reveal-child-zoom"
          data-scroll-child
        />
    </div>
  </div>
</section>

<section id="signup" class="form bg-white py-16 text-center" data-scroll>
    <div class="mx-auto max-w-3xl px-4">
      <h3 class="section-heading section-heading--center section-heading--xl mb-4" data-scroll-child>
        Готові розпочати?
      </h3>
      <p class="section-body section-body--center section-body--narrow mb-10" data-scroll-child>
        Запишіться на перший пробний урок безкоштовно! Просто заповніть форму, і я зв'яжуся з вами.
      </p>

      <div class="mb-10 flex flex-col items-center justify-center gap-3 contact-links" data-scroll-child>

        <div class="items-center gap-3 sm:gap-5">
          <a
            href="{$instagramHref3}"
            class="social-link hover:text-[#7a555b] underline-offset-4 hover:underline"
            target="_blank"
            rel="noopener noreferrer"
          >
            <img src="img/icon-instagram.svg" alt="" class="social-link__icon" aria-hidden="true" />
            <span>Instagram</span>
          </a>
          <a
            href="{$telegramHref}"
            class="social-link hover:text-[#7a555b] underline-offset-4 hover:underline"
            target="_blank"
            rel="noopener noreferrer"
          >
            <img src="img/icon-telegram.svg" alt="" class="social-link__icon" aria-hidden="true" />
            <span>Telegram</span>
          </a>
        </div>
      </div>

      <form id="contact-form" class="space-y-5 reveal-child-zoom" data-scroll-child novalidate>
        <input type="hidden" name="contact_form" value="1" />
        <input
          type="text"
          name="name"
          placeholder="Ваше ім'я"
          autocomplete="name"
          class="w-full sm:w-[80%] lg:w-[60%] mx-auto h-[52px] px-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#7a555b] text-[16px]"
          required
        />

        <div  class="contact-status contact-status--error form-error" data-error-for="name" aria-live="polite" role="status" aria-live="polite"  style="margin-top: 0px"></div>
        <input
          type="text"
          name="contact"
          placeholder="Ваш Viber або Telegram"
          autocomplete="tel"
          class="w-full sm:w-[80%] lg:w-[60%] mx-auto h-[52px] px-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#7a555b] text-[16px]"

          required
        />
         <div  class="contact-status contact-status--error form-error" data-error-for="contact" aria-live="polite" role="status" aria-live="polite"  style="margin-top: 0px"></div>
    
        <textarea
        
          name="message"
          placeholder="Ваше повідомлення (необов'язково)"
          class="w-full sm:w-[80%] lg:w-[60%] mx-auto h-[108px] px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#7a555b] text-[14px]"
        ></textarea>
    
           <div  class="contact-status contact-status--error form-error" data-error-for="message" aria-live="polite" role="status" aria-live="polite"  style="margin-top: 0px"></div>
    
        <button
    
          type="submit"
          class="w-full sm:w-[80%] lg:w-[60%] mx-auto h-[59px] bg-[#7a555b] text-white text-[20px] lg:text-[20px] font-medium rounded-lg shadow-md hover:bg-[#6b4950] transition"
        >
          Надіслати заявку
        </button>
       
      </form>
      <div
        id="contact-status"
        class="contact-status"
        role="status"
        aria-live="polite"
        style="display: none;"
      ></div>
  </div>
</section>

<footer data-scroll>
  <!-- Лого (зліва, відступ дає сам footer через padding: 0 100px) -->
  <img src="img/logo-3.png" alt="Логотип Dasha Tutor — репетитор англійської мови" class="reveal-child-zoom" data-scroll-child loading="lazy">

  <!-- Копірайт (насередині завдяки grid: auto 1fr auto) -->
  <p data-scroll-child>© 2025 Даша | English Tutor. Усі права захищено.</p>

  <!-- Меню/контакти (справа, з тим самим 100px через padding контейнера) -->
  <div data-scroll-child class="reveal-child-right">
    <h5>Contact Us</h5>
    <p> <a
            href="{$instagramHref4}"
            class="social-link hover:text-[#7a555b] underline-offset-4 hover:underline"
            target="_blank"
            rel="noopener noreferrer"
          >Instagram</a></p>
    <p>
      <a
            href="{$telegramHref}"
            class="social-link hover:text-[#7a555b] underline-offset-4 hover:underline"
            target="_blank"
            rel="noopener noreferrer"
          >Telegram</a>
      </p>
  </div>
</footer>



  </main>
</body>
</html>
HTML;

function createRememberToken(string $username, string $secretKey): string
{
    $signature = hash_hmac('sha256', $username, $secretKey);
    return base64_encode($username . ':' . $signature);
}

function validateRememberToken(string $token, string $expectedUsername, string $secretKey): bool
{
    $decoded = base64_decode($token, true);

    if ($decoded === false) {
        return false;
    }

    $parts = explode(':', $decoded, 2);

    if (count($parts) !== 2) {
        return false;
    }

    [$username, $signature] = $parts;

    if (!hash_equals($expectedUsername, $username)) {
        return false;
    }

    $expectedSignature = hash_hmac('sha256', $expectedUsername, $secretKey);

    return hash_equals($expectedSignature, $signature);
}

$authenticated = $_SESSION['authenticated'] ?? false;

if (!$authenticated && isset($_COOKIE[$rememberCookieName])) {
    $token = $_COOKIE[$rememberCookieName];

    if (validateRememberToken($token, $credentials['username'], $secretKey)) {
        $_SESSION['authenticated'] = true;
        $authenticated = true;
    } else {
        setcookie($rememberCookieName, '', [
            'expires' => time() - 3600,
            'path' => '/',
        ]);
    }
}

$error = null;

if (!$authenticated && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $remember = isset($_POST['remember']);

    if (
        hash_equals($credentials['username'], $username)
        && hash_equals($credentials['password'], $password)
    ) {
        $_SESSION['authenticated'] = true;

        if ($remember) {
            $token = createRememberToken($credentials['username'], $secretKey);
            $cookieOptions = [
                'expires' => time() + $rememberDuration,
                'path' => '/',
                'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
                'httponly' => true,
                'samesite' => 'Lax',
            ];
            setcookie($rememberCookieName, $token, $cookieOptions);
        } else {
            setcookie($rememberCookieName, '', [
                'expires' => time() - 3600,
                'path' => '/',
            ]);
        }

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    $error = 'Невірний логін або пароль.';
}

$authenticated = $_SESSION['authenticated'] ?? false;

if ($authenticated) {
    header('Content-Type: text/html; charset=UTF-8');
    echo $landingPageHtml;
    exit;
}

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Увійдіть до панелі</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 flex items-center justify-center p-6">
    <div class="w-full max-w-md">
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-2xl p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-semibold text-white">Вхід</h1>
                <p class="mt-2 text-slate-200">Уведіть свої облікові дані, щоб продовжити</p>
            </div>
            <?php if ($error !== null): ?>
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>
            <form method="post" class="space-y-6">
                <div>
                    <label for="username" class="block text-sm font-medium text-slate-200">Логін</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="<?= isset($username) ? htmlspecialchars($username, ENT_QUOTES, 'UTF-8') : ''; ?>"
                        required
                        class="mt-1 block w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder-slate-400 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                        autocomplete="username"
                    />
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-200">Пароль</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="mt-1 block w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder-slate-400 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                        autocomplete="current-password"
                    />
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center text-sm text-slate-200">
                        <input
                            type="checkbox"
                            name="remember"
                            class="mr-2 h-4 w-4 rounded border-white/30 bg-white/10 text-indigo-500 focus:ring-indigo-400"
                            <?= isset($remember) && $remember ? 'checked' : ''; ?>
                        />
                        Запам'ятати мене
                    </label>
                    <span class="text-xs text-slate-400">Лише для особистого використання</span>
                </div>
                <button
                    type="submit"
                    class="w-full rounded-xl bg-indigo-500 px-4 py-3 text-sm font-semibold text-white transition hover:bg-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:ring-offset-2 focus:ring-offset-slate-900"
                >
                    Увійти
                </button>
            </form>
        </div>
        <p class="mt-6 text-center text-xs text-slate-400">
            Авторизований доступ. Уведіть видані облікові дані.
        </p>
    </div>
</body>
</html>
