<?php
if (!defined('DASHA_BOOTSTRAPPED')) {
    http_response_code(404);
    exit;
}
// Rendered by index.php after configuration and contact-form handling.
$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
$arrow = '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 12h15M13 5l7 7-7 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
?>
<!DOCTYPE html>
<html lang="uk">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $metaTitleEscaped ?></title>
  <meta name="description" content="<?= $metaDescriptionEscaped ?>">
  <meta name="robots" content="<?= $robotsContent ?>">
  <meta name="theme-color" content="#f7f5ef">
  <meta name="author" content="Dasha Pechenyuk">
  <link rel="canonical" href="<?= $canonicalHref ?>">
  <link rel="icon" href="/new/favicon.svg" type="image/svg+xml">
  <meta property="og:type" content="website">
  <meta property="og:locale" content="uk_UA">
  <meta property="og:site_name" content="Dasha Tutor">
  <meta property="og:title" content="<?= $metaTitleEscaped ?>">
  <meta property="og:description" content="<?= $metaDescriptionEscaped ?>">
  <meta property="og:url" content="<?= $canonicalHref ?>">
  <meta property="og:image" content="<?= $ogImageHref ?>">
  <meta property="og:image:alt" content="Даша — репетитор англійської мови онлайн">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= $metaTitleEscaped ?>">
  <meta name="twitter:description" content="<?= $metaDescriptionEscaped ?>">
  <meta name="twitter:image" content="<?= $ogImageHref ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Lora:ital,wght@1,400;1,500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/new/style.css?v=<?= $cssVersion ?>">
  <script src="/new/script.js?v=<?= $scriptVersion ?>" defer></script>
  <script type="application/ld+json"><?= $structuredDataJson ?></script>
</head>
<body>
  <a class="skip-link" href="#main">Перейти до змісту</a>
  <header class="site-header">
    <div class="container header-inner">
      <a class="brand" href="/new/" aria-label="Dasha Tutor — на головну">
        <img src="/new/img/dasha-tutor-logo-320.webp" width="160" height="36" alt="Dasha Tutor">
      </a>
      <button class="menu-toggle" type="button" aria-controls="site-nav" aria-expanded="false" aria-label="Відкрити меню"><span></span><span></span></button>
      <nav id="site-nav" class="site-nav" aria-label="Основна навігація">
        <a href="#about">Про мене</a>
        <a href="#lessons">Заняття</a>
        <a href="#approach">Мій підхід</a>
        <a href="#faq">Питання</a>
        <a class="nav-mobile-cta" href="#signup">Записатися на урок <?= $arrow ?></a>
      </nav>
      <a class="header-cta" href="#signup">Пробний урок <?= $arrow ?></a>
    </div>
  </header>

  <main id="main">
    <section class="hero container" aria-labelledby="hero-title">
      <div class="hero-copy">
        <p class="eyebrow"><span class="status-dot" aria-hidden="true"></span> Репетитор англійської онлайн</p>
        <h1 id="hero-title">Англійська,<br> що відкриває<br> <em>ваш світ.</em></h1>
        <p class="hero-description">Для розмов, нових можливостей і подорожей.<br class="desktop-break"> Вчимося говорити впевнено — у вашому темпі,<br class="desktop-break"> з підтримкою та задоволенням.</p>
        <div class="hero-actions">
          <a class="button button-primary" href="#signup">Спробувати безкоштовно <?= $arrow ?></a>
          <span class="hero-action-note">Почнімо<br> зі знайомства</span>
        </div>
        <div class="hero-meta"><span>Індивідуально та в парі</span><span class="meta-separator" aria-hidden="true"></span><span>Дорослим і підліткам</span></div>
      </div>
      <div class="hero-visual">
        <div class="hero-photo-wrap">
          <img class="hero-photo" src="/new/img/dasha-english-tutor-768.webp" srcset="/new/img/dasha-english-tutor-480.webp 480w, /new/img/dasha-english-tutor-768.webp 768w, /new/img/dasha-english-tutor-1200.webp 1200w" sizes="(max-width: 700px) 92vw, (max-width: 1000px) 47vw, 560px" width="1200" height="1269" alt="Даша, викладачка англійської, біля картини з Лондоном" fetchpriority="high">
        </div>
        <div class="hero-stamp" aria-hidden="true"><span>ENGLISH FOR</span><svg viewBox="0 0 48 48" fill="none"><path d="M24 3v42M3 24h42M9 9l30 30M9 39 30-30" stroke="currentColor" stroke-width="2"/></svg><span>REAL LIFE</span></div>
        <div class="photo-caption"><span class="caption-wave" aria-hidden="true">☺</span><div><strong>Hi, I'm Dasha!</strong><span>Ваша підтримка у світі англійської</span></div></div>
        <p class="image-side-note" aria-hidden="true">A LITTLE ENGLISH. A BIGGER WORLD.</p>
      </div>
    </section>

    <div class="lesson-strip" aria-label="Про заняття">
      <div class="container strip-inner"><span>Жива розмовна практика</span><span class="strip-star" aria-hidden="true">✳</span><span>Програма під ваші цілі</span><span class="strip-star" aria-hidden="true">✳</span><span>Без страху помилитися</span><span class="strip-star" aria-hidden="true">✳</span><span>З будь-якої точки світу</span></div>
    </div>

    <section class="section container lessons-section" id="lessons" aria-labelledby="lessons-title">
      <div class="section-heading">
        <div><p class="eyebrow">01 / Ваша англійська</p><h2 id="lessons-title">Різні цілі.<br> <em>Ваш власний шлях.</em></h2></div>
        <p class="section-intro">Англійська стає цікавішою, коли ви знаєте, навіщо її вчите. Знайдемо ваш фокус і рухатимемося до нього разом.</p>
      </div>
      <div class="lesson-grid">
        <article class="lesson-card lesson-card-sage">
          <div class="lesson-card-top"><span class="card-number">01</span><svg viewBox="0 0 48 48" fill="none" aria-hidden="true"><path d="M10 10h28v21H24l-9 7v-7h-5V10Z" stroke="currentColor" stroke-width="1.5"/><path d="M17 18h14M17 24h9" stroke="currentColor" stroke-width="1.5"/></svg></div>
          <p class="card-label">Для життя й подорожей</p><h3>Говорити.<br> Розуміти. Бути собою.</h3>
          <p>Замовити каву, підтримати розмову чи спланувати подорож. Практикуємо англійську в ситуаціях, знайомих із життя.</p>
          <a class="text-link" href="#signup" data-goal="Хочу впевненіше спілкуватися англійською та подорожувати.">Хочу говорити вільніше <?= $arrow ?></a>
        </article>
        <article class="lesson-card lesson-card-peach">
          <div class="lesson-card-top"><span class="card-number">02</span><svg viewBox="0 0 48 48" fill="none" aria-hidden="true"><rect x="7" y="15" width="34" height="25" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M17 15v-5h14v5M7 24c11 5 23 5 34 0M24 24v8" stroke="currentColor" stroke-width="1.5"/></svg></div>
          <p class="card-label">Для роботи й можливостей</p><h3>Ваш наступний<br> кар’єрний крок.</h3>
          <p>Співбесіди, робочі зустрічі та презентації. Розвиваємо бізнес-англійську й уміння впевнено розповісти про себе.</p>
          <a class="text-link" href="#signup" data-goal="Мені потрібна англійська для роботи або підготовки до співбесіди.">Хочу нових можливостей <?= $arrow ?></a>
        </article>
        <article class="lesson-card lesson-card-lilac">
          <div class="lesson-card-top"><span class="card-number">03</span><svg viewBox="0 0 48 48" fill="none" aria-hidden="true"><path d="m5 18 19-9 19 9-19 9-19-9ZM12 22v13c7 6 17 6 24 0V22M43 18v17" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg></div>
          <p class="card-label">Для навчання й іспитів</p><h3>Менше хаосу.<br> Більше розуміння.</h3>
          <p>Шкільна програма, НМТ та IELTS. Розбираємо складні теми й готуємося за планом, який враховує вашу мету.</p>
          <a class="text-link" href="#signup" data-goal="Цікавить підготовка до іспитів або допомога зі шкільною англійською.">Хочу підготуватися <?= $arrow ?></a>
        </article>
      </div>
    </section>

    <section class="about-section" id="about" aria-labelledby="about-title">
      <div class="container about-grid">
        <figure class="about-portrait">
          <img src="/new/img/dasha-paris-768.webp" srcset="/new/img/dasha-paris-480.webp 480w, /new/img/dasha-paris-768.webp 768w, /new/img/dasha-paris-1200.webp 1200w" sizes="(max-width: 700px) 88vw, 44vw" width="1200" height="1200" alt="Даша під час подорожі до Парижа, на тлі Ейфелевої вежі" loading="lazy" decoding="async">
          <figcaption><span>Paris, France</span><span>Мова відкриває більше ↗</span></figcaption>
        </figure>
        <div class="about-copy">
          <p class="eyebrow">02 / Давайте знайомитись</p>
          <h2 id="about-title">Привіт, я Даша.<br> <em>І я на вашому боці.</em></h2>
          <p class="about-lead">Вірю, що англійська — це передусім про людей. Про розмови, які хочеться продовжувати, і моменти, які хочеться проживати.</p>
          <p>На моїх онлайн-заняттях можна запитувати, помилятися й пробувати знову. Я допомагаю дорослим і підліткам розібратися в мові та поступово зробити її частиною свого життя.</p>
          <p>Підлаштовую програму під ваш рівень, інтереси й цілі. Поєдную розмовну практику з граматикою, актуальною лексикою та інтерактивними матеріалами.</p>
          <div class="about-signature"><span>Dasha</span><a class="text-link" href="<?= $instagramHref2 ?>" target="_blank" rel="noopener noreferrer">Більше про мене в Instagram <?= $arrow ?></a></div>
        </div>
      </div>
    </section>

    <section class="section container approach-section" id="approach" aria-labelledby="approach-title">
      <div class="section-heading">
        <div><p class="eyebrow">03 / Як ми навчаємось</p><h2 id="approach-title">Серйозно про прогрес.<br> <em>Легко у спілкуванні.</em></h2></div>
        <p class="section-intro">Без однакових уроків для всіх. З увагою до того, що потрібно саме вам.</p>
      </div>
      <div class="approach-grid">
        <div class="approach-note"><span class="note-spark" aria-hidden="true">✳</span><p>“You don’t have<br> to be perfect<br> <em>to start talking.”</em></p><span>Не ідеально. Зате по-справжньому.</span></div>
        <div class="approach-list">
          <article><span>01</span><div><h3>Починаємо з вас</h3><p>Знайомимось, обговорюємо цілі та визначаємо рівень. На цій основі формуємо особистий план навчання.</p></div></article>
          <article><span>02</span><div><h3>Практикуємо живу мову</h3><p>Говоримо, слухаємо, читаємо й розбираємо граматику в контексті. Нові слова одразу стають частиною розмови.</p></div></article>
          <article><span>03</span><div><h3>Рухаємось у вашому темпі</h3><p>Повертаємося до складного, помічаємо прогрес і коригуємо план. Матеріали для практики залишаються з вами між уроками.</p></div></article>
        </div>
      </div>
      <div class="lesson-facts"><div><strong>60 хвилин</strong><span>на повноцінне заняття</span></div><div><strong>Онлайн</strong><span>там, де вам зручно</span></div><div><strong>1:1 або в парі</strong><span>обирайте свій формат</span></div><a href="#signup" class="text-link">Знайти свій час <?= $arrow ?></a></div>
    </section>

    <section class="world-section" id="travels" aria-labelledby="world-title">
      <div class="container world-grid">
        <div class="world-copy"><p class="eyebrow">Більше, ніж іноземна мова</p><h2 id="world-title">Світ цікавіший,<br> коли ви можете<br> <em>з ним поговорити.</em></h2><p>Запитати дорогу в незнайомому місті. Почати розмову з людиною з іншої країни. Почути історію від місцевого гіда.</p><p>Саме для таких моментів ми й вчимо англійську. Щоб почуватися впевненіше — і в подорожах, і в щоденному житті.</p><a class="button button-light" href="#signup">Почати свою історію <?= $arrow ?></a></div>
        <figure class="world-photo"><img src="/new/img/dasha-ephesus-768.webp" srcset="/new/img/dasha-ephesus-480.webp 480w, /new/img/dasha-ephesus-768.webp 768w, /new/img/dasha-ephesus-1200.webp 1200w" sizes="(max-width: 700px) 88vw, 42vw" width="1200" height="1500" alt="Даша серед стародавніх колон під час подорожі до Ефеса" loading="lazy" decoding="async"><figcaption><span>A LITTLE ADVENTURE</span><span>Ефес, Туреччина ↗</span></figcaption></figure>
      </div>
    </section>

    <section class="section container faq-section" id="faq" aria-labelledby="faq-title">
      <div class="faq-intro"><p class="eyebrow">04 / Усе, що ви хотіли запитати</p><h2 id="faq-title">Можливо,<br> <em>вам цікаво.</em></h2><p>Не знайшли своє питання?<br> Напишіть мені — розберемося разом.</p><a class="text-link" href="<?= $telegramHref ?>" target="_blank" rel="noopener noreferrer">Запитати в Telegram <?= $arrow ?></a></div>
      <div class="faq-list">
        <?php foreach ($faqItems as $faqIndex => $faqItem): ?>
        <details class="faq-item"<?= $faqIndex === 0 ? ' open' : '' ?>>
          <summary><span><?= $escape($faqItem['question']) ?></span><span class="faq-plus" aria-hidden="true"></span></summary>
          <div class="faq-answer"><p><?= $escape($faqItem['answer']) ?></p></div>
        </details>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="signup-section" id="signup" aria-labelledby="signup-title">
      <div class="container signup-grid">
        <div class="signup-copy"><p class="eyebrow"><span class="status-dot" aria-hidden="true"></span> Ваш перший крок</p><h2 id="signup-title">Почнімо<br> з простого<br> <em>“Hello!”</em></h2><p>Запишіться на безкоштовний пробний урок. Познайомимося, поговоримо про ваші цілі та знайдемо комфортний формат навчання.</p><div class="signup-socials"><a href="<?= $telegramHref ?>" target="_blank" rel="noopener noreferrer"><img src="/new/img/icon-telegram.svg" width="20" height="20" alt=""> Telegram ↗</a><a href="<?= $instagramHref3 ?>" target="_blank" rel="noopener noreferrer"><img src="/new/img/icon-instagram.svg" width="20" height="20" alt=""> Instagram ↗</a></div></div>
        <div class="form-panel">
          <div class="form-heading"><h3>До зустрічі на уроці!</h3><span aria-hidden="true">↗</span></div>
          <p class="form-description">Залиште контакт — я напишу вам, щоб узгодити деталі.</p>
          <form id="contact-form" action="/new/" method="post" novalidate>
            <input type="hidden" name="contact_form" value="1">
            <div class="form-field"><label for="contact-name">Як вас звати? <span aria-hidden="true">*</span></label><input id="contact-name" name="name" type="text" placeholder="Ваше ім’я" autocomplete="name" minlength="2" maxlength="100" required aria-describedby="name-error"><p class="field-error" id="name-error" data-error-for="name"></p></div>
            <div class="form-field"><label for="contact-contact">Telegram або номер телефону <span aria-hidden="true">*</span></label><input id="contact-contact" name="contact" type="text" placeholder="@username або +380" autocomplete="tel" minlength="5" maxlength="200" required aria-describedby="contact-error"><p class="field-error" id="contact-error" data-error-for="contact"></p></div>
            <div class="form-field"><label for="contact-message">Для чого вам англійська? <span class="optional">Необов’язково</span></label><textarea id="contact-message" name="message" placeholder="Трохи про ваші цілі та побажання" rows="3" maxlength="1500" aria-describedby="message-error"></textarea><p class="field-error" id="message-error" data-error-for="message"></p></div>
            <button class="button button-primary submit-button" type="submit"><span>Записатися на пробний урок</span><?= $arrow ?></button>
            <p class="form-note">Використаю ваш контакт лише для відповіді на заявку.</p>
          </form>
          <div id="contact-status" class="contact-status" role="status" aria-live="polite" hidden></div>
          <noscript><p class="noscript-note">Для заявки через форму увімкніть JavaScript або <a href="<?= $telegramHref ?>">напишіть мені в Telegram</a>.</p></noscript>
        </div>
      </div>
    </section>
  </main>
  <footer class="site-footer"><div class="container footer-main"><a class="brand" href="/new/" aria-label="Dasha Tutor — на головну"><img src="/new/img/dasha-tutor-logo-320.webp" width="160" height="36" alt="Dasha Tutor" loading="lazy"></a><p>Англійська, що стає частиною вашого життя.</p><a class="back-top" href="#main">На початок ↑</a></div><div class="container footer-bottom"><span>© <?= date('Y') ?> Dasha Tutor. Усі права захищено.</span><span>Індивідуальні уроки англійської онлайн</span><a href="<?= $instagramHref4 ?>" target="_blank" rel="noopener noreferrer">Instagram ↗</a></div></footer>
</body>
</html>
