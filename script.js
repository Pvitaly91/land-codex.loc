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
    const fieldNames = ['name', 'contact', 'message'];
    const fields = fieldNames.reduce((acc, name) => {
      acc[name] = contactForm.querySelector(`[name="${name}"]`);
      return acc;
    }, {});
    const errorNodes = fieldNames.reduce((acc, name) => {
      acc[name] = contactForm.querySelector(`[data-error-for="${name}"]`);
      return acc;
    }, {});

    const showStatus = (text, type) => {
      if (!status) {
        return;
      }

      status.classList.remove('contact-status--error', 'contact-status--success');

      if (!text) {
        status.textContent = '';
        status.style.display = 'none';
        return;
      }

      status.style.display = '';

      if (type) {
        status.classList.add(type);
      }

      status.textContent = text;
    };

    const clearFieldErrors = () => {
      fieldNames.forEach((name) => {
        const errorNode = errorNodes[name];

        if (fields[name]) {
          fields[name].removeAttribute('aria-invalid');
        }

        if (errorNode) {
          errorNode.textContent = '';
        }
      });
    };

    const setFieldError = (name, message) => {
      const field = fields[name];
      const errorNode = errorNodes[name];

      if (field) {
        if (message) {
          field.setAttribute('aria-invalid', 'true');
        } else {
          field.removeAttribute('aria-invalid');
        }
      }

      if (errorNode) {
        errorNode.textContent = message || '';
      }
    };

    clearFieldErrors();
    showStatus('', '');

    contactForm.addEventListener('submit', async (event) => {
      event.preventDefault();

      if (!submitButton) {
        return;
      }

      clearFieldErrors();
      showStatus('', '');

      const nameValue = fields.name ? fields.name.value.trim() : '';
      const contactValue = fields.contact ? fields.contact.value.trim() : '';
      const messageValue = fields.message ? fields.message.value.trim() : '';
      let firstInvalidField = null;

      if (fields.name && nameValue.length < 2) {
        setFieldError('name', "Будь ласка, вкажіть ім'я (мінімум 2 символи).");
        firstInvalidField = firstInvalidField || fields.name;
      }

      if (fields.contact && contactValue.length < 5) {
        setFieldError('contact', 'Будь ласка, залиште ваш контакт для зворотного звʼязку.');
        firstInvalidField = firstInvalidField || fields.contact;
      }

      if (fields.message && messageValue.length > 1500) {
        setFieldError('message', 'Повідомлення занадто довге. Максимум 1500 символів.');
        firstInvalidField = firstInvalidField || fields.message;
      }

      if (firstInvalidField) {
        showStatus('Перевірте правильність заповнення форми.', 'contact-status--error');
        firstInvalidField.focus();
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

        let payload = null;

        try {
          payload = await response.json();
        } catch (parseError) {
          console.error('Failed to parse response', parseError);
        }

        if (!response.ok || !payload || !payload.success) {
          if (payload && payload.errors) {
            const errorKeys = Object.keys(payload.errors);
            let focused = false;

            errorKeys.forEach((key) => {
              setFieldError(key, payload.errors[key]);
              if (!focused && fields[key]) {
                fields[key].focus();
                focused = true;
              }
            });
          }

          const errorMessage = payload && payload.message ? payload.message : 'Сталася помилка під час відправлення.';
          showStatus(errorMessage, 'contact-status--error');
          return;
        }

        contactForm.reset();
        clearFieldErrors();
        showStatus(payload.message || 'Дякуємо! Повідомлення успішно відправлено.', 'contact-status--success');
      } catch (error) {
        console.error('Contact form submission failed', error);
        showStatus('Не вдалося відправити повідомлення. Перевірте підключення до інтернету.', 'contact-status--error');
      } finally {
        submitButton.disabled = false;
        submitButton.textContent = defaultButtonText;
      }
    });
  }
});
