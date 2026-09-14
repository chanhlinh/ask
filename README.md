# Ask — Live Q&A

Standalone, mobile-first Live Q&A built with Laravel 12, React/Vite, MySQL and Laravel Reverb. It has no dependency on Privilege or any other application.

## What it includes

- Public QR entry at `/e/{code}`: anonymous/no-login question submission, moderation-aware status, upvotes, popular/latest feed and live updates.
- Presentation view at `/e/{code}/screen`: every approved question, event QR, total count and restrained large-format styling.
- Protected `/admin`: event creation plus Pending, Approved, Answered and Rejected moderation actions.
- White-label event name, logo and accent color; safe image validation and local public storage.
- Browser-scoped duplicate vote prevention: a random UUID is stored only in the attendee's browser and HMAC-hashed server-side. No fingerprinting or attendee profile is collected.

## Local setup

```bash
cp .env.example .env
composer install
npm install
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan ask:create-admin admin@example.com --name="Ask Admin"
npm run build
php artisan reverb:start
php artisan serve
```

Open `/admin`, create an event, then use the public and screen links shown there. The QR encodes only the public event URL.

## Test and production build

```bash
php artisan test
npm run build
```

## Deployment

See [docs/DEPLOYMENT.md](docs/DEPLOYMENT.md). Do not serve the application before `APP_KEY`, database credentials, Reverb secrets, HTTPS proxy settings and the first administrator are configured.
