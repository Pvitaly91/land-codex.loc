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

  const sliderContainers = document.querySelectorAll('[data-slider]');

  sliderContainers.forEach((slider) => {
    const track = slider.querySelector('.slider-track');
    if (!track) {
      return;
    }

    const items = Array.from(track.children);
    const prevBtn = slider.querySelector('.slider-arrow.left');
    const nextBtn = slider.querySelector('.slider-arrow.right');
    const dotsWrapper = slider.querySelector('.slider-dots');

    if (!prevBtn || !nextBtn || !dotsWrapper || items.length === 0) {
      return;
    }

    const intervalAttr = Number.parseInt(slider.getAttribute('data-slider-interval') ?? '', 10);
    const interval = Number.isFinite(intervalAttr) && intervalAttr > 0 ? intervalAttr : 110000;

    dotsWrapper.innerHTML = '';

    if (items.length <= 1) {
      prevBtn.style.display = 'none';
      nextBtn.style.display = 'none';
      dotsWrapper.style.display = 'none';
      return;
    }

    let current = 0;
    let auto = window.setInterval(nextSlide, interval);
    let scrollTimeout;

    items.forEach((_, index) => {
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

    function scrollToCurrent(options = {}) {
      const behavior = options.instant ? 'auto' : 'smooth';
      const target = items[current];

      if (!target) {
        return;
      }

      track.scrollTo({
        left: target.offsetLeft,
        behavior,
      });
      updateDots();
    }

    function updateDots() {
      dotsWrapper.querySelectorAll('button').forEach((dot, index) => {
        dot.classList.toggle('active', index === current);
      });
    }

    function nextSlide() {
      current = (current + 1) % items.length;
      scrollToCurrent();
    }

    function prevSlide() {
      current = (current - 1 + items.length) % items.length;
      scrollToCurrent();
    }

    function resetAuto() {
      window.clearInterval(auto);
      auto = window.setInterval(nextSlide, interval);
    }

    nextBtn.addEventListener('click', () => {
      nextSlide();
      resetAuto();
    });

    prevBtn.addEventListener('click', () => {
      prevSlide();
      resetAuto();
    });

    track.addEventListener('scroll', () => {
      window.clearTimeout(scrollTimeout);
      scrollTimeout = window.setTimeout(() => {
        const scrollLeft = track.scrollLeft;
        let closest = 0;
        let min = Number.POSITIVE_INFINITY;

        items.forEach((item, index) => {
          const diff = Math.abs(item.offsetLeft - scrollLeft);
          if (diff < min) {
            min = diff;
            closest = index;
          }
        });

        current = closest;
        updateDots();
      }, 100);
    });

    scrollToCurrent({ instant: true });
  });

  const prefersReducedMotionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');

  const initFaqAccordion = (reduceMotion) => {
    const faqItems = document.querySelectorAll('#faq details');

    faqItems.forEach((details) => {
      const summary = details.querySelector('summary');
      const content = details.querySelector('.faq__content');

      if (!summary) {
        return;
      }

      const setAriaExpanded = () => {
        summary.setAttribute('aria-expanded', details.open ? 'true' : 'false');
      };

      setAriaExpanded();
      details.addEventListener('toggle', setAriaExpanded);

      if (reduceMotion || !content) {
        if (content && details.open) {
          content.style.maxHeight = 'none';
          content.style.opacity = '1';
        }

        return;
      }

      if (details.open) {
        content.style.maxHeight = 'none';
        content.style.opacity = '1';
      }

      const onceTransitionEnd = (node, callback) => {
        const handler = (event) => {
          if (event.target !== node || event.propertyName !== 'max-height') {
            return;
          }

          node.removeEventListener('transitionend', handler);
          callback();
        };

        node.addEventListener('transitionend', handler);
      };

      summary.addEventListener('click', (event) => {
        event.preventDefault();

        if (details.dataset.animating === 'true') {
          return;
        }

        if (details.open) {
          const startHeight = content.scrollHeight;
          details.dataset.animating = 'true';
          content.style.maxHeight = `${startHeight}px`;
          content.style.opacity = '1';

          window.requestAnimationFrame(() => {
            content.style.maxHeight = '0px';
            content.style.opacity = '0';
          });

          onceTransitionEnd(content, () => {
            details.open = false;
            content.style.maxHeight = '';
            content.style.opacity = '';
            details.removeAttribute('data-animating');
          });

          return;
        }

        details.dataset.animating = 'true';
        details.open = true;
        content.style.maxHeight = '0px';
        content.style.opacity = '0';

        window.requestAnimationFrame(() => {
          content.style.maxHeight = `${content.scrollHeight}px`;
          content.style.opacity = '1';
        });

        onceTransitionEnd(content, () => {
          content.style.maxHeight = 'none';
          content.style.opacity = '1';
          details.removeAttribute('data-animating');
        });
      });
    });
  };

  initFaqAccordion(prefersReducedMotionQuery.matches);

  const heroSection = document.getElementById('main-left-block');
  const heroMediaQuery = window.matchMedia('(max-width: 1024px)');

  if (heroSection) {
    let rafId = 0;
    let scrollListenerAttached = false;

    const applyOffset = () => {
      rafId = 0;

      if (!heroMediaQuery.matches) {
        return;
      }

      const rect = heroSection.getBoundingClientRect();
      const offset = rect.top * -1;
      heroSection.style.setProperty('--hero-bg-offset', `${offset}px`);
    };

    const requestOffset = () => {
      if (!heroMediaQuery.matches) {
        return;
      }

      if (rafId !== 0) {
        return;
      }

      rafId = window.requestAnimationFrame(applyOffset);
    };

    const detachScroll = () => {
      if (!scrollListenerAttached) {
        return;
      }

      window.removeEventListener('scroll', requestOffset);
      scrollListenerAttached = false;
    };

    const attachScroll = () => {
      if (scrollListenerAttached) {
        return;
      }

      window.addEventListener('scroll', requestOffset, { passive: true });
      scrollListenerAttached = true;
    };

    const enableHeroParallax = () => {
      if (heroMediaQuery.matches) {
        heroSection.classList.add('hero-fixed-background');
        attachScroll();
        applyOffset();
      } else {
        heroSection.classList.remove('hero-fixed-background');
        detachScroll();

        if (rafId !== 0) {
          window.cancelAnimationFrame(rafId);
          rafId = 0;
        }

        heroSection.style.removeProperty('--hero-bg-offset');
      }
    };

    enableHeroParallax();

    if (typeof heroMediaQuery.addEventListener === 'function') {
      heroMediaQuery.addEventListener('change', enableHeroParallax);
    } else if (typeof heroMediaQuery.addListener === 'function') {
      heroMediaQuery.addListener(enableHeroParallax);
    }

    window.addEventListener('resize', requestOffset);
  }

  const contactForm = document.getElementById('contact-form');

  if (contactForm) {
    const status = document.getElementById('contact-status');
    const submitButton = contactForm.querySelector('button[type="submit"]');
    const defaultButtonText = submitButton ? submitButton.textContent : '';
    const formHideDelay = 360;
    let hideFormTimer = 0;
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

      status.classList.remove('contact-status--error', 'contact-status--success', 'contact-status--pending', 'contact-status--visible');

      if (!text) {
        status.textContent = '';
        status.style.display = 'none';
        status.setAttribute('aria-hidden', 'true');
        status.removeAttribute('tabindex');
        return;
      }

      status.style.display = '';

      if (type) {
        status.classList.add(type);
      }

      status.classList.add('contact-status--visible');
      status.setAttribute('aria-hidden', 'false');
      status.textContent = text;
    };

    const hideFormWithAnimation = () => {
      if (contactForm.classList.contains('contact-form--hidden')) {
        return;
      }

      window.clearTimeout(hideFormTimer);
      contactForm.classList.add('contact-form--sent');
      hideFormTimer = window.setTimeout(() => {
        contactForm.classList.add('contact-form--hidden');
        contactForm.classList.remove('contact-form--sent');
      }, formHideDelay);
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
        showStatus('', '');
        firstInvalidField.focus();
        return;
      }

      showStatus('Надсилаємо повідомлення…', 'contact-status--pending');
      contactForm.classList.add('contact-form--submitting');
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

          const hasMessage = payload && Object.prototype.hasOwnProperty.call(payload, 'message');
          const errorMessage = hasMessage ? payload.message : 'Сталася помилка під час відправлення.';

          if (errorMessage) {
            showStatus(errorMessage, 'contact-status--error');
          } else {
            showStatus('', '');
          }
          return;
        }

        contactForm.reset();
        clearFieldErrors();

        const successMessage = payload.message || 'Дякуємо! Повідомлення успішно відправлено.';
        showStatus(successMessage, 'contact-status--success');

        if (status) {
          status.setAttribute('tabindex', '-1');
          status.focus();
          status.addEventListener('blur', () => {
            status.removeAttribute('tabindex');
          }, { once: true });
        }

        contactForm.classList.remove('contact-form--submitting');
        hideFormWithAnimation();
      } catch (error) {
        console.error('Contact form submission failed', error);
        showStatus('Не вдалося відправити повідомлення. Перевірте підключення до інтернету.', 'contact-status--error');
      } finally {
        submitButton.disabled = false;
        submitButton.textContent = defaultButtonText;
        if (!contactForm.classList.contains('contact-form--hidden')) {
          contactForm.classList.remove('contact-form--submitting');
        }
      }
    });
  }
});
