<?php
if (!defined('DASHA_BOOTSTRAPPED')) {
    http_response_code(404);
    exit;
}
/** Shared metadata for the single-page landing. SITE_URL never follows query strings. */
$configuredSiteUrl = trim((string) $getEnvValue('NEW_SITE_URL', 'http://dasha.loc/new/'));
$siteParts = parse_url($configuredSiteUrl);
if (filter_var($configuredSiteUrl, FILTER_VALIDATE_URL) === false
    || !is_array($siteParts)
    || !isset($siteParts['scheme'], $siteParts['host'])
    || !in_array(strtolower($siteParts['scheme']), ['http', 'https'], true)
    || isset($siteParts['user']) || isset($siteParts['pass'])) {
    $siteParts = parse_url('http://dasha.loc/new/');
}

$siteHost = strtolower($siteParts['host']);
$siteOrigin = strtolower($siteParts['scheme']) . '://' . $siteHost;
if (isset($siteParts['port'])) {
    $siteOrigin .= ':' . (int) $siteParts['port'];
}
$sitePath = '/' . trim($siteParts['path'] ?? '', '/');
$canonicalUrl = rtrim($siteOrigin . $sitePath, '/') . '/';
$canonicalHref = htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8');
$ogImageUrl = $canonicalUrl . 'img/main.jpg';
$ogImageHref = htmlspecialchars($ogImageUrl, ENT_QUOTES, 'UTF-8');

$isPrivateHost = static function (string $hostname): bool {
    $hostname = trim(strtolower($hostname), '[]');
    return $hostname === 'localhost'
        || strpos($hostname, '.') === false
        || preg_match('/\.(?:loc|ub|local|localhost|test|invalid|example)$/i', $hostname) === 1
        || (filter_var($hostname, FILTER_VALIDATE_IP) !== false
            && filter_var($hostname, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false);
};
$requestHost = parse_url('http://' . ($_SERVER['HTTP_HOST'] ?? $siteHost), PHP_URL_HOST);
$isLocalHost = $isPrivateHost($siteHost) || $isPrivateHost((string) $requestHost);
$indexableSetting = filter_var(
    $getEnvValue('SITE_INDEXABLE', !$isLocalHost),
    FILTER_VALIDATE_BOOLEAN,
    FILTER_NULL_ON_FAILURE
);
$isIndexable = $indexableSetting === true && !$isLocalHost && !$authEnabled;
$robotsContent = $isIndexable ? 'index, follow, max-image-preview:large' : 'noindex, follow';
if (!headers_sent()) {
    header('X-Robots-Tag: ' . $robotsContent);
}

$metaTitle = 'Репетитор англійської онлайн — Даша Печенюк | Dasha Tutor';
$metaDescription = 'Англійська онлайн для дорослих і підлітків із Дашею Печенюк: розмовна практика, підготовка до НМТ та IELTS, індивідуальні й парні уроки. Перший урок безкоштовний.';
$metaTitleEscaped = htmlspecialchars($metaTitle, ENT_QUOTES, 'UTF-8');
$metaDescriptionEscaped = htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8');

$faqItems = [
    [
        'question' => 'Для кого ці заняття?',
        'answer' => 'Для дорослих і підлітків, які хочуть покращити англійську для спілкування, подорожей, роботи чи навчання. Програму складаємо з урахуванням вашого рівня та цілей.',
    ],
    [
        'question' => 'Як проходить онлайн-урок?',
        'answer' => 'Стандартне заняття триває 60 хвилин. Поєднуємо розмовну практику, граматику, нову лексику та завдання на розуміння мови на слух. Час занять узгоджуємо разом.',
    ],
    [
        'question' => 'Чи є безкоштовний перший урок?',
        'answer' => 'Так, перший пробний урок безкоштовний. Це нагода познайомитися, обговорити ваші цілі та зрозуміти, чи підходить вам формат занять.',
    ],
    [
        'question' => 'Чи можна підготуватися до НМТ або IELTS?',
        'answer' => 'Так, програму можна адаптувати під НМТ або IELTS і поєднати підготовку до іспиту із загальною англійською. План навчання залежить від вашого стартового рівня, цілей та часу на практику.',
    ],
    [
        'question' => 'Чи можна займатися в парі?',
        'answer' => 'Так, доступні індивідуальні та парні онлайн-заняття. Якщо хочете навчатися з другом, напишіть мені — обговоримо ваші цілі, рівень і зручний графік.',
    ],
    [
        'question' => 'Як записатися на заняття?',
        'answer' => 'Залиште ім’я та зручний спосіб зв’язку у формі на сайті або напишіть мені в Telegram. Я зв’яжуся з вами, щоб обговорити запит і домовитися про першу зустріч.',
    ],
];

$personId = $canonicalUrl . '#tutor';
$websiteId = $canonicalUrl . '#website';
$webpageId = $canonicalUrl . '#webpage';
$serviceId = $canonicalUrl . '#english-lessons';
$structuredData = [
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'Person',
            '@id' => $personId,
            'name' => 'Даша Печенюк',
            'alternateName' => 'Dasha Pechenyuk',
            'jobTitle' => 'Репетитор англійської мови',
            'description' => 'Індивідуальні та парні онлайн-заняття англійською для дорослих і підлітків.',
            'url' => $canonicalUrl,
            'image' => $ogImageUrl,
            'sameAs' => [
                'https://www.instagram.com/dasha__english_',
                'https://www.instagram.com/dasha__pechenyuk',
                'https://t.me/dasha_pechenyuk',
            ],
        ],
        [
            '@type' => 'WebSite',
            '@id' => $websiteId,
            'url' => $canonicalUrl,
            'name' => 'Dasha Tutor',
            'inLanguage' => 'uk',
            'publisher' => ['@id' => $personId],
        ],
        [
            '@type' => 'WebPage',
            '@id' => $webpageId,
            'url' => $canonicalUrl,
            'name' => $metaTitle,
            'description' => $metaDescription,
            'inLanguage' => 'uk',
            'isPartOf' => ['@id' => $websiteId],
            'about' => ['@id' => $personId],
            'mainEntity' => ['@id' => $serviceId],
        ],
        [
            '@type' => 'Service',
            '@id' => $serviceId,
            'name' => 'Онлайн-уроки англійської мови',
            'serviceType' => 'Індивідуальні та парні заняття англійською',
            'description' => 'Розмовна англійська, граматика, лексика, підготовка до НМТ та IELTS для дорослих і підлітків.',
            'url' => $canonicalUrl,
            'provider' => ['@id' => $personId],
        ],
    ],
];
$structuredDataJson = json_encode(
    $structuredData,
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
) ?: '{}';
