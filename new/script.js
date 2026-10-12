'use strict';

document.addEventListener('DOMContentLoaded', () => {
  const menuButton = document.querySelector('.menu-toggle');
  const navigation = document.getElementById('site-nav');
  const closeMenu = (restoreFocus = false) => {
    if (!menuButton || !navigation) return;
    menuButton.setAttribute('aria-expanded', 'false');
    menuButton.setAttribute('aria-label', 'Відкрити меню');
    navigation.classList.remove('is-open');
    document.body.classList.remove('menu-open');
    if (restoreFocus) menuButton.focus();
  };
  if (menuButton && navigation) {
    menuButton.addEventListener('click', () => {
      const isOpen = menuButton.getAttribute('aria-expanded') !== 'true';
      menuButton.setAttribute('aria-expanded', String(isOpen));
      menuButton.setAttribute('aria-label', isOpen ? 'Закрити меню' : 'Відкрити меню');
      navigation.classList.toggle('is-open', isOpen);
      document.body.classList.toggle('menu-open', isOpen);
    });
    navigation.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => closeMenu()));
    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape' && menuButton.getAttribute('aria-expanded') === 'true') closeMenu(true);
    });
    document.addEventListener('click', (event) => {
      if (!event.target.closest('.site-header')) closeMenu();
    });
    window.matchMedia('(min-width: 701px)').addEventListener('change', (event) => {
      if (event.matches) closeMenu();
    });
  }
  const messageField = document.getElementById('contact-message');
  document.querySelectorAll('[data-goal]').forEach((link) => {
    link.addEventListener('click', () => {
      if (messageField && !messageField.value.trim()) messageField.value = link.dataset.goal;
    });
  });
  const form = document.getElementById('contact-form');
  if (!form) return;
  const status = document.getElementById('contact-status');
  const submit = form.querySelector('[type="submit"]');
  const submitText = submit.querySelector('span');
  const originalText = submitText.textContent;
  const fieldNames = ['name', 'contact', 'message'];
  const fields = Object.fromEntries(fieldNames.map((name) => [name, form.elements.namedItem(name)]));
  let submitting = false;
  const fieldError = (name, message = '') => {
    const field = fields[name];
    if (!field) return;
    form.querySelector(`[data-error-for="${name}"]`).textContent = message;
    if (message) field.setAttribute('aria-invalid', 'true');
    else field.removeAttribute('aria-invalid');
  };
  const showStatus = (message, type = '') => {
    status.textContent = message;
    status.dataset.type = type;
    status.hidden = !message;
  };
  fieldNames.forEach((name) => fields[name].addEventListener('input', () => fieldError(name)));
  form.addEventListener('submit', async (event) => {
    event.preventDefault();
    if (submitting) return;
    fieldNames.forEach((name) => fieldError(name));
    showStatus('');
    const values = Object.fromEntries(fieldNames.map((name) => [name, fields[name].value.trim()]));
    const errors = {};
    if (Array.from(values.name).length < 2) errors.name = 'Вкажіть ваше ім’я — щонайменше 2 символи.';
    if (values.contact.length < 5) errors.contact = 'Залиште номер телефону або ваш Telegram.';
    if (Array.from(values.message).length > 1500) errors.message = 'Скоротіть повідомлення до 1500 символів.';
    if (Object.keys(errors).length) {
      Object.entries(errors).forEach(([name, message]) => fieldError(name, message));
      fields[Object.keys(errors)[0]].focus();
      return;
    }
    submitting = true;
    submit.disabled = true;
    form.setAttribute('aria-busy', 'true');
    submitText.textContent = 'Надсилаємо заявку…';
    const body = new FormData(form);
    fieldNames.forEach((name) => body.set(name, values[name]));
    const controller = new AbortController();
    const timeout = window.setTimeout(() => controller.abort(), 45000);
    try {
      const response = await fetch(form.action, { method: 'POST', headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, body, signal: controller.signal });
      const payload = await response.json();
      if (!response.ok || !payload.success) {
        if (payload.errors) {
          const invalidNames = Object.keys(payload.errors).filter((name) => fields[name]);
          invalidNames.forEach((name) => fieldError(name, payload.errors[name]));
          if (invalidNames.length) fields[invalidNames[0]].focus();
        }
        if (payload.message) showStatus(payload.message, 'error');
        else if (!payload.errors) showStatus('Не вдалося надіслати заявку. Спробуйте ще раз або напишіть мені в Telegram.', 'error');
        return;
      }
      form.reset();
      form.hidden = true;
      showStatus(payload.message || 'Дякую! Я зв’яжуся з вами, щоб узгодити пробний урок.', 'success');
      status.setAttribute('tabindex', '-1');
      status.focus();
    } catch (error) {
      showStatus(error.name === 'AbortError' ? 'Відповідь сервера затримується. Напишіть мені в Telegram, щоб уточнити заявку.' : 'Не вдалося надіслати заявку. Перевірте з’єднання або напишіть мені в Telegram.', 'error');
    } finally {
      window.clearTimeout(timeout);
      submitting = false;
      submit.disabled = false;
      submitText.textContent = originalText;
      form.removeAttribute('aria-busy');
    }
  });
});
