# SEO лендінгу Dasha Tutor

Поточна адреса: `http://dasha.loc/new/`. Це локальний домен із файлу hosts; Google не може індексувати його як публічний сайт. Для цієї копії встановлено `noindex, follow` у HTML і заголовку `X-Robots-Tag`.

Налаштовано title, description, canonical без параметрів запиту, Open Graph, Twitter Card, семантичні заголовки, alt для фото та JSON-LD з пов’язаними Person, WebSite, WebPage і Service. Розмітка описує репетитора й послуги; вона не гарантує спеціального вигляду або позиції в пошуку.

`robots.txt` дозволяє читання сторінки, щоб пошуковик міг побачити `noindex`. `sitemap.xml` містить лише головну сторінку: секції з `#якорями` не є окремими сторінками. Локальна карта сайту призначена для перевірки, її не потрібно надсилати в Search Console.

## Перенесення на публічний домен

1. У `.env` задайте `NEW_SITE_URL=https://ваш-публічний-домен/`, `SITE_INDEXABLE=true` і `AUTH_ENABLED=false` після підготовки сайту до публікації. Для проміжної перевірки використовуйте `SITE_INDEXABLE=false`.
2. Замініть `http://dasha.loc/new/` у `robots.txt` і `sitemap.xml` на ту саму остаточну HTTPS-адресу. Canonical, Open Graph і JSON-LD беруть базову адресу з `NEW_SITE_URL`.
3. Налаштуйте HTTPS і постійний редирект альтернативних адрес на обраний домен. Перевірте, що головна сторінка відповідає HTTP 200, а у відповіді немає `noindex` чи форми авторизації.
4. Підтвердьте домен у Google Search Console, надішліть `/sitemap.xml` та перевірте головну сторінку через URL Inspection. Після запуску перевірте швидкодію і мобільну версію за публічною адресою.

Адреси `.loc`, `.ub`, `.local`, `.test`, localhost і приватні IP залишаються `noindex` навіть за `SITE_INDEXABLE=true`. Невалідний `NEW_SITE_URL` повертає безпечне локальне значення. Парольний доступ також вимикає індексацію.

За оновлення контенту можна додати правдиву дату `<lastmod>` у sitemap; не варто оновлювати її на кожен запит. Google ігнорує `priority` і `changefreq`. Не додавайте непідтверджені відгуки, рейтинги, кваліфікації чи гарантовані результати. Видимий FAQ корисний відвідувачам, але Google припинив показ FAQ rich results у травні 2026 року.

## Офіційні рекомендації

- [Canonical і дублікати URL](https://developers.google.com/search/docs/crawling-indexing/consolidate-duplicate-urls)
- [Як працює noindex](https://developers.google.com/search/docs/crawling-indexing/block-indexing)
- [Створення карти сайту](https://developers.google.com/search/docs/crawling-indexing/sitemaps/build-sitemap)
- [Вимоги до структурованих даних](https://developers.google.com/search/docs/appearance/structured-data/sd-policies)
- [Оновлення Google Search: припинення FAQ rich results](https://developers.google.com/search/updates)
