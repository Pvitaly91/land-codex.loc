<?php
declare(strict_types=1);

session_start();

$credentials = [
    'username' => 'admin',
    'password' => 'StrongPass123',
];

$rememberCookieName = 'remember_auth';
$rememberDuration = 30 * 24 * 60 * 60; // 30 days
$secretKey = 'pRjFf3SxKZq8T0vL2nMhY1wB5cD9eG4u';
$documentPath = __DIR__ . '/index.html';

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
    if (!is_readable($documentPath)) {
        http_response_code(500);
        echo 'Основний файл сторінки недоступний.';
        exit;
    }

    readfile($documentPath);
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
