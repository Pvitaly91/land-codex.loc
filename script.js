'use strict';

document.documentElement.classList.add('js-ready');

document.addEventListener('DOMContentLoaded', () => {
  const revealElements = Array.from(document.querySelectorAll('[data-scroll], [data-scroll-child]'));
  const pairDelayStep = 0.12;
  const intraPairGap = 0.08;
  const maxDelay = 0.1;

  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries, obs) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          obs.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.15,
      rootMargin: '0px 0px 2% 0px',
    });

    revealElements.forEach((element, index) => {
      if (!element.style.getPropertyValue('--reveal-delay')) {
        const pairIndex = Math.floor(index / 2);
        const withinPairOffset = (index % 2) * intraPairGap;
        const delay = Math.min(pairIndex * pairDelayStep + withinPairOffset, maxDelay);
        element.style.setProperty('--reveal-delay', `${delay}s`);
      }

      observer.observe(element);
    });
  } else {
    revealElements.forEach((element) => element.classList.add('is-visible'));
  }

  const burgerButton = document.getElementById('menu-button-burger');
  const mobileNav = document.getElementById('mnav');

  if (burgerButton && mobileNav) {
    burgerButton.addEventListener('click', () => {
      const isOpen = mobileNav.classList.toggle('open');
      burgerButton.setAttribute('aria-expanded', String(isOpen));
    });
  }

  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener('click', (event) => {
      const href = anchor.getAttribute('href');
      if (!href) {
        return;
      }

      const targetId = href.substring(1);
      if (!targetId) {
        return;
      }

      const target = document.getElementById(targetId);
      if (!target) {
        return;
      }

      event.preventDefault();
      target.scrollIntoView({ behavior: 'smooth' });

      if (mobileNav && burgerButton) {
        mobileNav.classList.remove('open');
        burgerButton.setAttribute('aria-expanded', 'false');
      }
    });
  });

  const container = document.querySelector('.features .blocks');
  const cards = container ? container.querySelectorAll('.card') : [];
  const prevBtn = document.querySelector('.features .slider-arrow.left');
  const nextBtn = document.querySelector('.features .slider-arrow.right');
  const dotsWrapper = document.querySelector('.features .slider-dots');

  if (container && cards.length > 0 && prevBtn && nextBtn && dotsWrapper) {
    let current = 0;
    let auto = window.setInterval(nextSlide, 110000);
    let scrollTimeout;

    cards.forEach((_, index) => {
      const dot = document.createElement('button');
      if (index === 0) {
        dot.classList.add('active');
      }

      dot.addEventListener('click', () => {
        current = index;
        scrollToCurrent();
        resetAuto();
      });

      dotsWrapper.appendChild(dot);
    });

    function scrollToCurrent() {
      container.scrollTo({
        left: cards[current].offsetLeft,
        behavior: 'smooth',
      });
      updateDots();
    }

    function updateDots() {
      dotsWrapper.querySelectorAll('button').forEach((dot, index) => {
        dot.classList.toggle('active', index === current);
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

    function resetAuto() {
      window.clearInterval(auto);
      auto = window.setInterval(nextSlide, 110000);
    }

    nextBtn.addEventListener('click', () => {
      nextSlide();
      resetAuto();
    });

    prevBtn.addEventListener('click', () => {
      prevSlide();
      resetAuto();
    });

    container.addEventListener('scroll', () => {
      window.clearTimeout(scrollTimeout);
      scrollTimeout = window.setTimeout(() => {
        const scrollLeft = container.scrollLeft;
        let closest = 0;
        let min = Number.POSITIVE_INFINITY;

        cards.forEach((card, index) => {
          const diff = Math.abs(card.offsetLeft - scrollLeft);
          if (diff < min) {
            min = diff;
            closest = index;
          }
        });

        current = closest;
        updateDots();
      }, 100);
    });
  }

  const contactForm = document.getElementById('contact-form');

  if (contactForm) {
    const status = document.getElementById('contact-status');
    const submitButton = contactForm.querySelector('button[type="submit"]');
    const defaultButtonText = submitButton ? submitButton.textContent : '';
    const nameInput = contactForm.querySelector('input[name="name"]');
    const contactInput = contactForm.querySelector('input[name="contact"]');
    const messageInput = contactForm.querySelector('textarea[name="message"]');

    const showStatus = (text, type) => {
      if (!status) {
        return;
      }

      status.classList.remove('contact-status--error', 'contact-status--success');
      if (type) {
        status.classList.add(type);
      }
      status.textContent = text;
    };

    contactForm.addEventListener('submit', async (event) => {
      event.preventDefault();

      if (!submitButton || !nameInput || !contactInput || !messageInput) {
        return;
      }

      const nameValue = nameInput.value.trim();
      const contactValue = contactInput.value.trim();
      const messageValue = messageInput.value.trim();

      if (nameValue.length < 2) {
        showStatus("Будь ласка, вкажіть ім'я (мінімум 2 символи).", 'contact-status--error');
        nameInput.focus();
        return;
      }

      if (contactValue.length < 5) {
        showStatus('Будь ласка, залиште ваш контакт для зворотного звʼязку.', 'contact-status--error');
        contactInput.focus();
        return;
      }

      if (messageValue.length > 1500) {
        showStatus('Повідомлення занадто довге. Максимум 1500 символів.', 'contact-status--error');
        messageInput.focus();
        return;
      }

      showStatus('Надсилаємо повідомлення…', '');
      submitButton.disabled = true;
      submitButton.textContent = 'Відправлення…';

      const formData = new FormData(contactForm);
      formData.set('name', nameValue);
      formData.set('contact', contactValue);
      formData.set('message', messageValue);
      formData.set('contact_form', '1');

      try {
        const response = await fetch(window.location.pathname, {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
          },
          body: formData,
        });

        const payload = await response.json();

        if (!response.ok || !payload.success) {
          const errorMessage = payload && payload.message ? payload.message : 'Сталася помилка під час відправлення.';
          showStatus(errorMessage, 'contact-status--error');
          return;
        }

        contactForm.reset();
        showStatus(payload.message || 'Дякуємо! Повідомлення успішно відправлено.', 'contact-status--success');
      } catch (error) {
        console.error('Contact form submission failed', error);
        showStatus('Не вдалося відправити повідомлення. Перевірте підключення до інтернету.', 'contact-status--error');
      } finally {
        if (submitButton) {
          submitButton.disabled = false;
          submitButton.textContent = defaultButtonText;
        }
      }
    });
  }
});
