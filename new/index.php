<?php
declare(strict_types=1);

define('DASHA_BOOTSTRAPPED', true);

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

$envFile = dirname(__DIR__) . '/.env';
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

$authUsername = (string) $getEnvValue('AUTH_USERNAME', 'admin');
$authPassword = (string) $getEnvValue('AUTH_PASSWORD', 'StrongPass123');

$credentials = [
    'username' => $authUsername,
    'password' => $authPassword,
];

$authEnabledValue = $getEnvValue('AUTH_ENABLED', 'true');
$authEnabled = filter_var(
    is_bool($authEnabledValue) ? $authEnabledValue : (string) $authEnabledValue,
    FILTER_VALIDATE_BOOLEAN,
    FILTER_NULL_ON_FAILURE
);

if ($authEnabled === null) {
    $authEnabled = true;
}

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

require __DIR__ . '/includes/seo.php';

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

ob_start();
require __DIR__ . '/views/landing.php';
$landingPageHtml = (string) ob_get_clean();

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
$error = null;

if ($authEnabled) {
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
} else {
    $authenticated = true;
    if (($_SESSION['authenticated'] ?? false) !== true) {
        $_SESSION['authenticated'] = true;
    }
}

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
