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

$landingPageHtml = <<<'HTML'
<!DOCTYPE html>
<html lang="uk">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dasha Tutor — Hero Header (Responsive, Desktop-Exact)</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<style>
  /* =========================
   Base & Variables
========================= */
:root{
  --brand:        #d6b7ae;
  --bg-page:      #634956;
  --ink:          #030303;
  --ink-muted:    #4b5563;
  --ink-accent:   #6A8793;
  --rose:         #7a555b;
  --rose-200:     #FFEBEC;
  --amber-50:     #fef6db;
  --sand-50:      rgba(255,244,231,0.82);
  --card-bg:      #e5d0ed;
  --primary:      #7a555b;
  --primary-weak: #d28e70;
  --footer-bg:    #d6e2eb;

  --radius:       8px;
  --radius-lg:    12px;
  --shadow-sm:    0 0 10px rgba(0,0,0,0.1);
  --shadow-md:    0 10px 15px rgba(0,0,0,0.1);
}

html, body{
  font-family: "Montserrat", sans-serif !important;
  background-color: var(--bg-page);
  scroll-behavior: smooth;
}

/* опціональні класи */
.font-display, .font-sans{ font-family: "Montserrat", sans-serif; }

/* =========================
   Header / Nav
========================= */
header, #mnav{
  background-color: var(--sand-50);
  box-shadow: 2px -2px 10px rgba(3,3,3,0.1);
  color: #7a555b;
  font-size: clamp(18px, 4vw, 24px);
  line-height: clamp(24px, 4.8vw, 31px);
  margin: auto !important;
}

#mnav{
  opacity: 0;
  transform: translateY(-10px);
  transition: opacity .3s ease, transform .3s ease;
  pointer-events: none;
}
#mnav.open{
  opacity: 1;
  transform: translateY(0);
  pointer-events: auto;
}

nav{
  color: var(--ink);
  font-size: clamp(16px, 3vw, 24px);
  line-height: 1.4;
}

/* =========================
   Hero
========================= */
#main-photo{
  position: absolute;
  top: -85px;
  left: 50%;
  width: 50%;
  height: 900px;
  background: url(img/main.jpg) -20px center / cover no-repeat;
}

h1.main{
  color: #AF6B38;
  font-size: clamp(32px, 7vw, 52px);
  font-weight: 500;
  line-height: clamp(40px, 8vw, 68px);
}
h1.main span{ color: var(--ink-accent); }
h1.main strong{ font-weight: 600; }

.text-block{
  margin-top: 50px;
  color: #7a555b;
  font-size: clamp(16px, 4.5vw, 24px);
  line-height: 1.5;
}

/* кнопка у хедері */
.order-lesson{
  cursor: pointer;
  position: relative; /* зберігаємо положення, якщо залежало від top/left */
  width: 206px;
  height: 52px;
  padding: 1rem 16px 0; /* збережено відступ зверху */
  border: 0;
  box-sizing: border-box;
  border-radius: var(--radius);
  box-shadow: var(--shadow-sm);
  background-color: var(--primary-weak);
  color: rgba(250,250,248,0.83);
  font-family: "Roboto", sans-serif;
  font-weight: 500;
  font-size: clamp(16px, 3.5vw, 18px);
  line-height: 1.4;
  text-align: center;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  outline: none;
}

.order-lesson__icon{ display: none; }
.order-lesson__text{ display: inline; }
.order-lesson__icon svg{ display: block; }

/* CTA в геро */
#try-first-leson{
  display: inline-block;
  cursor: pointer;
  text-align: center;
  width: 571px;
  height: 65px;
  margin-top: 60px;
  padding: 0 8px;
  border: 0;
  box-sizing: border-box;
  border-radius: var(--radius);
  box-shadow: var(--shadow-sm);
  background-color: var(--primary);
  color: #fff;
  font-family: "Roboto", sans-serif;
  font-weight: 500;
  font-size: clamp(18px, 5vw, 24px);
  line-height: 1.4;
  outline: none;
}

#manu-button-burger{ display: none; }

/* =========================
   About
========================= */
section.about{
  background-color: #fff;
  min-height: 450px;
  padding-top: 60px;
}

.title{
  color: var(--ink);
  font-size: clamp(28px, 6vw, 42px);
  font-weight: 500;
  line-height: clamp(36px, 7vw, 52px);
  text-align: center;
  margin: 0 auto 60px;
}

.text{
  color: var(--ink-muted);
  font-size: clamp(16px, 4.5vw, 24px);
  line-height: 1.55;
  text-align: center;
  max-width: 950px;
  margin: 0 auto 40px;
}

/* =========================
   Features
========================= */
.features{
  background-color: var(--rose-200);
  min-height: 450px;
  padding-top: 60px;
}
.features .title{ color: #7A555B; }
.features .text{ color: var(--ink-accent); }

.blocks{
  padding: 0 0 62px 62px;
}

.card{
  display: inline-block;
  width: 30%;
  height: 156px;
  margin-top: 32px;
  margin-right: 32px;
  background-color: var(--card-bg);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-md);
}
.card h3{
  margin: 24px 0 0 17px;
  color: var(--ink);
  font-family: "Roboto", sans-serif;
  font-weight: 500;
  font-size: clamp(18px, 3.5vw, 22px);
  line-height: 1.4;
}
.card p{
  width: 70%;
  margin: 8px 0 0 62px;
  color: var(--ink-muted);
  font-family: "Roboto", sans-serif;
  font-weight: 500;
  font-size: clamp(14px, 3.5vw, 16px);
  line-height: 1.5;
}

/* слайдер на мобі */
.slider-arrow, .slider-dots{ display: none; }

/* =========================
   Result
========================= */
.result{
  display: flex;
  align-items: center;
  min-height: 450px;
  background-color: #fff;
}
.result .first{ width: 39%; justify-content: center; }
.result .second{ width: 60%; margin-left: 4%; vertical-align: middle; }
.result h3, .trevel h3{
  color: #000;
  font-size: clamp(28px, 6vw, 52px);
  font-weight: 500;
  line-height: clamp(36px, 7vw, 68px);
}
.result .second p:first-of-type{ margin-top: 0; }
.result .second p{
  margin-top: 4%;
  margin-right: 10%;
  color: var(--ink-accent);
  font-size: clamp(18px, 4.5vw, 24px);
  line-height: 1.5;
}
.result .second p span{ color: #000; }

/* =========================
   Travel
========================= */
.trevel{
  padding: 126px 0 98px;
  background-color: var(--amber-50);
}
.trevel .first, .trevel .second{ display: flex; }
.trevel img{
  width: 570px;
  height: auto;
  margin: 0 74px 0 91px;
}
.trevel div h3{
  margin-left: 0;
  color: #595236;
  font-size: clamp(26px, 6vw, 42px);
  font-weight: 500;
  line-height: clamp(32px, 7vw, 52px);
}
.trevel div{ margin-right: 60px; }
.trevel div p{
  margin-top: 40px;
  color: var(--ink-muted);
  font-size: clamp(16px, 4.5vw, 24px);
  line-height: 1.55;
}
.trevel .second{ margin-top: -200px; }
.trevel .second div{
  margin-left: 91px;
  padding-top: 250px;
}

/* =========================
   Footer
========================= */
footer{
  display: grid;
  grid-template-columns: auto 1fr auto; /* ліво-центр-право */
  align-items: center;
  min-height: 240px;
  padding: 24px 100px;                  /* 100px зліва/справа на десктопі */
  background-color: var(--footer-bg);
  gap: 16px;
}
footer img{
  width: 250px;
  height: 50px;
  margin-left: 0;                        /* забираємо старий margin */
}
footer p, footer h5{
  color: #212225;
  font-size: clamp(14px, 3.3vw, 18px);
  line-height: 1.5;
  margin: 0;
}
footer p{
  text-align: center;
}
footer h5{ font-weight: 500; margin-bottom: 8px; text-align: right; }
footer div p{ margin-top: 6px; width: 250px; text-align: right; }

/* =========================
   Contact Form
========================= */
section.form{
  background-color: #fff;
  padding-top: 132px;
  text-align: center;
}
.form{ padding-bottom: 120px; }

.form h3{
  color: var(--ink);
  font-size: clamp(32px, 7vw, 60px);
  font-weight: 500;
  line-height: clamp(38px, 8vw, 70px);
  text-align: center;
  margin-bottom: 20px;
}
.form p{
  max-width: 700px;
  margin: 0 auto;
  color: var(--ink-muted);
  font-size: clamp(16px, 4.5vw, 24px);
  line-height: 1.6;
  text-align: center;
}
.form form{ margin-top: 50px; }

.form form input,
.form textarea{
  box-sizing: border-box;
  border-radius: var(--radius);
  box-shadow: var(--shadow-sm);
  background-color: #fff;
  color: #000;
  outline: none;
  display: block;
  margin: 20px auto;
  border: 1px solid #d3d3d3;
  padding: 0 8px;
  font-family: "Roboto", sans-serif;
}

.form form input{
  height: 52px;
  font-size: 16px;
  line-height: 20px;
}
.form textarea{
  height: 108px;
  font-size: 14px;
  line-height: 24px;
  margin-bottom: 20px;
}

/* глобальна кнопка (залишаю як у вас, щоб не ламати інші місця) */
button.form-btn{
  cursor: pointer;
  width: 35%;
  height: 59px;
  margin-left: auto;
  margin-right: auto;
  border: 0;
  border-radius: var(--radius);
  box-shadow: var(--shadow-sm);
  background-color: var(--primary);
  color: #fff;
  font-family: "Roboto", sans-serif;
  font-weight: 500;
  font-size: clamp(16px, 3.8vw, 20px);
  line-height: 1.4;
  padding: 0 8px;
  outline: none;
  box-sizing: border-box;
}

/* =========================
   Media Queries
========================= */
@media (max-width: 1280px){
  header{ width: 100%; }
  #main-photo{ left: 52%; width: 48%; }
  #main-block-text{ margin-left: 30px; }
  .card{ width: 45%; }
}

#main-block-text div{ padding-right: 20px; }

@media (max-width: 1200px){
  h1.main{ font-size: clamp(32px, 6vw, 42px); line-height: clamp(40px, 7vw, 52px); }
  #main-block-text{ width: 530px; }
  #main-photo{ left: 54%; width: 46%; }
  #try-first-leson{
    width: 471px; height: 65px;
    font-size: clamp(18px, 4.8vw, 22px); font-weight: 500; line-height: 1.4;
    margin-top: 60px;
  }
  .text-block{ margin-top: 35px; font-size: clamp(16px, 4vw, 22px); line-height: 1.5; }
}

@media (max-width: 1100px){
  #main-block-text div{ max-width: 500px; }
}

@media (max-width: 1024px){
  #main-block-text{
    position: absolute;
    top: 30px;
    right: 0;
    width: 70%;
    height: 100%;
    margin-left: 0;
    background-color: rgba(255,255,255,0.6);
  }
  #main-block-text div{
    max-width: 80%;
    margin: 150px 0 0 10%;
    text-align: center;
  }
  #main-left-block{
    background: url(img/main.jpg) -40px 10px / 108% no-repeat;
  }
  .about-text{ padding: 5%; }
  #main-photo{ display: none; }
  footer h5{ font-weight: 500; margin-bottom: 8px; text-align: center; }
footer div p{ margin-top: 6px; width: 250px; text-align: center; }
}
@media (max-width: 1024px){
  footer{
    grid-template-columns: 1fr;          /* колона на планшет/мобілку */
    justify-items: center;
    text-align: center;
    padding: 24px 20px;                  /* менші бокові відступи */
    row-gap: 12px;
  }
  footer img{
    width: 220px; height: auto;
  }
}
@media (max-width: 930px){
  #try-first-leson{
    width: auto; height: auto;
    padding: 2% 5%;
    font-size: clamp(16px, 4.5vw, 18px); font-weight: 500; line-height: 1.4;
    margin-top: 60px;
  }
  #manu-button-burger{ display: block; }
  nav{ display: none !important; }
}

/* мобільний слайдер для .features */
@media (max-width: 888px){
  .slider-container{ position: relative; }
  .blocks{
    display: flex;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
    scroll-behavior: smooth;
    padding: 0 16px;
    margin-left: 32px;
            padding-bottom: 60px;
  }
  .blocks::-webkit-scrollbar{ display: none; }
  .card{
    flex: 0 0 100%;
    display: block;
    width: 100%;
    scroll-snap-align: start;
  }
  .slider-arrow{
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 32px; height: 32px;
    display: flex; align-items: center; justify-content: center;
    border: none; border-radius: 9999px;
    background: rgba(255,255,255,0.8);
    color: var(--primary);
    font-size: 20px;
  }
  .slider-arrow.left{ left: 4px; }
  .slider-arrow.right{ right: 4px; }
  .slider-dots{
    position: absolute;
    left: 50%; bottom: 8px;
    transform: translateX(-50%);
    display: flex; gap: 6px;
    margin-bottom: 30px;
  }
  .slider-dots button{
    width: 8px; height: 8px; padding: 0;
    border: none; border-radius: 50%;
    background: #d1d5db;
  }
  .slider-dots button.active{ background: var(--primary); }
}

@media (max-width: 768px){
  .order-lesson{
    width: 52px;
    height: 52px;
    padding: 0;
  }
  .order-lesson__icon{
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }
  .order-lesson__text{ display: none; }
}

@media (max-width: 703px){
  section.about{
    background-color: #fff;
    min-height: 370px;
    padding: 30px;
  }
  .text, .text-block{ font-size: clamp(16px, 4.5vw, 20px); line-height: 1.5; }
  h1.main{ font-size: clamp(28px, 8vw, 36px); line-height: clamp(34px, 9vw, 46px); }
  #main-left-block{ height: 615px; }
}

@media (max-width: 609px){
  #try-first-leson{ margin-top: 30px; }
}

@media (max-width: 590px){
  #main-block-text{ width: 100%; }
  #main-block-text div{
    max-width: 90%;
    margin-left: 5%;
  }
  h1.main{ font-size: clamp(26px, 9vw, 34px); line-height: clamp(34px, 10vw, 44px); }
  #try-first-leson{ margin-top: 60px; }
}

@media (max-width: 530px){
  #main-left-block{ background-size: 138%; }
}

</style>
</head>
<body class="font-sans text-stone-800 antialiased">
  
  <!-- Wrapper: fluid on mobile/tablet, exact width on desktop -->
  <main class="w-full xl:w-[1443px] mx-auto">
    <!-- Header: fluid on mobile/tablet, exact width on desktop -->
    <header class="sticky top-0 z-30 bg-white/22  border-b border-stone-200 mx-auto w-full  xl:w-[1100px] lg:h-[110px]">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-[80px] sm:h-[80px] lg:h-[110px]" >
           <button id="manu-button-burger" class=" inline-flex items-center justify-center rounded-xl border px-3 py-2" aria-label="Menu" onclick="document.getElementById('mnav').classList.toggle('open')">
              <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h16M3 11h16M3 16h16"/></svg>
            </button>
          <a href="#" class="flex items-center gap-2 group select-none w-[192px] h-[43px]">
            <img src="img/logo-3.png">
          </a>

          <!-- Desktop Nav -->
          <nav class="hidden md:flex items-center gap-6 lg:gap-8 text-[18px] ">
            <a class="hover:text-stone-900 " href="#about">Про мене</a>
            <a class="hover:text-stone-900 " href="#services">Послуги</a>
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
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-3 grid gap-2 text-[16px]">
          <a class="py-2" href="#about">Про мене</a>
          <a class="py-2" href="#services">Послуги</a>
          <a class="py-2" href="#travels">Подорожі</a>
          <a class="py-2" href="#approach">Мій підхід</a>
         
        </div>
      </div>
    </header>

    <!-- Hero -->
    <!-- Keep the original negative offset ONLY on desktop to match exact layout -->
    <section id="main-left-block" class="relative -mt-0 -mt-[110px] h-[815px]" style="background-color: #ebdcd6;" >
      <div class="mx-auto max-w-7xl grid lg:grid-cols-2" >
      
        <div class=" lg:mt-[200px]" id="main-block-text" >
          <div>
            <h1 class="main">
              <strong>Відкрийте світ англійської</strong>
              <span>— легко та з задоволенням</span>
            </h1>

            <p class="text-block">
              Привіт, я — Даша! Пропоную персоналізовані онлайн‑уроки, що допоможуть вам впевнено заговорити англійською.
              Давайте досягати ваших мовних цілей разом!
            </p>
 
            <a href="#signup" id="try-first-leson">
              Спробувати перший урок безкоштовно
            </a>
            
          </div>  
        </div>
        <div class="relative order-first lg:order-none" id="main-photo">
        
        </div>
        <!-- Right (photo) -->
       
      </div>
    </section>
    <section id="about" class="about" >
      <h2 class="title">Кілька слів про мене</h2>
      <p class="text">Моя головна мета — не просто навчити вас граматики, а закохати в англійську мову. Я вірю, що навчання має бути комфортним та надихаючим. Саме тому я створюю на уроках невимушену та дружню атмосферу, де кожен учень почувається впевнено, не боїться ставити питання та робити помилки, адже вони — невід'ємна частина прогресу.</p>
    </section>
    <section id="services" class="features" >
      <h2 class="title">Що чекає на вас на наших заняттях?</h2>
      <p class="text">Комплексний підхід до ваших цілей.</p>
      <div class="slider-container">
      <div class="blocks">
        <div class="card">
          
<h3><svg xmlns="http://www.w3.org/2000/svg" style="display:inline-block; margin-right: 14px" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 31 31" width="31" height="31" fill="none">
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="31" height="31" fill="#f9c6ce" x="0" y="0" opacity="100%">
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
</svg>Покращення навичок спілкування</h3>
          <p>Акцент на розмовній практиці для подолання мовного бар'єру.</p>
        </div>
         <div class="card" style="background-color: #e5e7eb;">
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
         <div class="card" style="background-color: #E6F5F2;">
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

             <div class="card" style="background-color: #D6EBE4;">
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
         <div class="card" style="background-color: #F5FBE6;">
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
         <div class="card" style="background-color: #F3DFE0;">
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
      <button class="slider-arrow left" aria-label="Попередній">&#10094;</button>
      <button class="slider-arrow right" aria-label="Наступний">&#10095;</button>
      <div class="slider-dots"></div>
      </div>
    </section>
  
<section id="approach" class="result bg-white py-12 md:py-20">
  <div class="mx-auto max-w-7xl px-6 flex flex-col md:flex-row items-center md:items-start gap-10">
    <!-- Ліва колонка -->
    <div class="w-full md:w-2/5 text-center md:text-left">
      <h3 class="text-black text-[32px] sm:text-[42px] lg:text-[52px] font-medium leading-tight">
        Мій підхід — ваш результат
      </h3>
    </div>

    <!-- Права колонка -->
    <div class="w-full md:w-3/5 space-y-5 text-center md:text-left">
      <p class="text-[#6a8793] text-[18px] sm:text-[20px] lg:text-[24px] leading-relaxed">
        <span class="text-black font-medium">✓ Індивідуальний план:</span>
        Програма формується на основі вашого рівня, цілей та побажань.
      </p>
      <p class="text-[#6a8793] text-[18px] sm:text-[20px] lg:text-[24px] leading-relaxed">
        <span class="text-black font-medium">✓ Дружня атмосфера:</span>
        Заняття проходять у невимушеній обстановці.
      </p>
      <p class="text-[#6a8793] text-[18px] sm:text-[20px] lg:text-[24px] leading-relaxed">
        <span class="text-black font-medium">✓ Гнучкість:</span>
        Займаємося онлайн у зручний час. Можливі індивідуальні та парні уроки.
      </p>
    </div>
  </div>
</section>


<!-- Travels (responsive, matches screenshot layout) -->
<section id="travels" class="bg-[#FEF6DB] py-12 sm:py-16">
  <div class="mx-auto w-full xl:w-[1300px] px-6 md:px-8 grid grid-cols-1 md:grid-cols-2 gap-10">
    <!-- Left column: top image, bottom title + text -->
    <div class="flex flex-col gap-6">
      <img
        src="img/Dasha-Paris-marge.jpg"
        alt="Даша у Парижі біля Ейфелевої вежі"
        class="w-full h-auto rounded-md shadow-md"
      />
      <div>
        <h3 class="text-[#595236] text-[26px] sm:text-[32px] leading-[1.25] font-medium">
          Занурюйтесь у культуру, а не просто будьте туристом
        </h3>
        <p class="mt-4 text-[#4B5563] text-[18px] sm:text-[20px] leading-[1.55]">
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
        <h3 class="text-[#595236] text-[26px] sm:text-[32px] leading-[1.25] font-medium">
          Відчуйте впевненість у кожному кроці
        </h3>
        <p class="mt-4 text-[#4B5563] text-[18px] sm:text-[20px] leading-[1.55]">
          Забудьте про мовні бар'єри та невпевненість. З англійською ви зможете легко
          забронювати готель, замовити саме ту страву, яку хочеться, чи просто запитати
          дорогу у перехожого. Це дарує відчуття справжньої незалежності та спокою в будь-якій країні.
        </p>
      </div>
      <img
        src="img/efes.jpg"
        alt="Подорож Ефес"
        class="w-full h-auto rounded-md shadow-md md:max-w-[570px] "
      />
    </div>
  </div>
</section>

<section id="signup" class="form bg-white py-16 text-center">
  <div class="mx-auto max-w-3xl px-4">
    <h3 class="text-[#030303] text-[32px] sm:text-[48px] lg:text-[60px] font-medium leading-tight mb-4">
      Готові розпочати?
    </h3>
    <p class="text-[#4b5563] text-[18px] sm:text-[20px] lg:text-[24px] leading-[1.4] max-w-2xl mx-auto mb-10">
      Запишіться на перший пробний урок безкоштовно! Просто заповніть форму, і я зв'яжуся з вами.
    </p>

    <form class="space-y-5">
      <input
        type="text"
        placeholder="Ваше ім'я"
        class="w-full sm:w-[80%] lg:w-[60%] mx-auto h-[52px] px-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#7a555b] text-[16px]"
      />
      <input
        type="text"
        placeholder="Ваш Viber або Telegram"
        class="w-full sm:w-[80%] lg:w-[60%] mx-auto h-[52px] px-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#7a555b] text-[16px]"
      />
      <textarea
        placeholder="Ваше повідомлення (необов'язково)"
        class="w-full sm:w-[80%] lg:w-[60%] mx-auto h-[108px] px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#7a555b] text-[14px]"
      ></textarea>
      <button
        type="submit"
        class="w-full sm:w-[80%] lg:w-[60%] mx-auto h-[59px] bg-[#7a555b] text-white text-[18px] lg:text-[20px] font-medium rounded-lg shadow-md hover:bg-[#6b4950] transition"
      >
        Надіслати заявку
      </button>
    </form>
  </div>
</section>

<footer>
  <!-- Лого (зліва, відступ дає сам footer через padding: 0 100px) -->
  <img src="img/logo-3.png" alt="Logo">

  <!-- Копірайт (насередині завдяки grid: auto 1fr auto) -->
  <p>© 2025 Даша | English Tutor. Усі права захищено.</p>

  <!-- Меню/контакти (справа, з тим самим 100px через padding контейнера) -->
  <div>
    <h5>Contact Us</h5>
    <p>Instagram</p>
    <p>Telegram</p>
  </div>
</footer>



  </main>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
          const targetId = this.getAttribute('href').substring(1);
          const target = document.getElementById(targetId);
          if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth' });
            const mobileNav = document.getElementById('mnav');
            if (mobileNav) mobileNav.classList.remove('open');
          }
        });
      });

      const container = document.querySelector('.features .blocks');
      if (!container) return;
      const cards = container.querySelectorAll('.card');
      const prevBtn = document.querySelector('.features .slider-arrow.left');
      const nextBtn = document.querySelector('.features .slider-arrow.right');
      const dotsWrapper = document.querySelector('.features .slider-dots');

      let current = 0;

      cards.forEach((_, i) => {
        const dot = document.createElement('button');
        if (i === 0) dot.classList.add('active');
        dot.addEventListener('click', () => {
          current = i;
          scrollToCurrent();
          resetAuto();
        });
        dotsWrapper.appendChild(dot);
      });

      function scrollToCurrent() {
        container.scrollTo({
          left: cards[current].offsetLeft,
          behavior: 'smooth'
        });
        updateDots();
      }

      function updateDots() {
        dotsWrapper.querySelectorAll('button').forEach((dot, i) => {
          dot.classList.toggle('active', i === current);
        });
      }

      function nextSlide() {
        current = (current + 1) % cards.length;
        scrollToCurrent();
      }

      function prevSlide() {
        current = (current - 1 + cards.length) % cards.length;
        scrollToCurrent();
      }

      nextBtn.addEventListener('click', () => {
        nextSlide();
        resetAuto();
      });
      prevBtn.addEventListener('click', () => {
        prevSlide();
        resetAuto();
      });

      let auto = setInterval(nextSlide, 110000);
      function resetAuto() {
        clearInterval(auto);
        auto = setInterval(nextSlide, 110000);
      }

      let scrollTimeout;
      container.addEventListener('scroll', () => {
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => {
          let scrollLeft = container.scrollLeft;
          let closest = 0;
          let min = Infinity;
          cards.forEach((card, i) => {
            const diff = Math.abs(card.offsetLeft - scrollLeft);
            if (diff < min) { min = diff; closest = i; }
          });
          current = closest;
          updateDots();
        }, 100);
      });
    });
  </script>
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
