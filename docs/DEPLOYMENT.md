# Production deployment: Ubuntu 22.04

Target path: `/var/www/html/ask`; host: `ask.privilege.global`. This guide only prepares the server. DNS and deployment are deliberately outside this repository.

## 1. Server packages

Install Nginx, MySQL 8, Supervisor, Git, Composer, Node 22+ and PHP 8.4 with `fpm`, `mysql`, `mbstring`, `xml`, `curl`, `zip`, `gd`, `bcmath` and `intl` extensions. Create MySQL database and a least-privilege application user:

```sql
CREATE DATABASE ask CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'ask'@'localhost' IDENTIFIED BY 'use-a-long-unique-password';
GRANT SELECT,INSERT,UPDATE,DELETE,CREATE,ALTER,INDEX,DROP ON ask.* TO 'ask'@'localhost';
FLUSH PRIVILEGES;
```

## 2. Application configuration

```bash
cd /var/www/html/ask
cp .env.example .env
composer install --no-dev --prefer-dist --optimize-autoloader
npm ci
npm run build
php artisan key:generate --force
php artisan migrate --force
php artisan storage:link
php artisan ask:create-admin admin@example.com --name="Ask Admin"
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Set `APP_ENV=production`, `APP_DEBUG=false`, the exact HTTPS `APP_URL`, strong random Reverb app key/secret, and MySQL credentials before caching config. Set `SESSION_SECURE_COOKIE=true`. Make `storage` and `bootstrap/cache` writable by the PHP-FPM user.

## 3. Nginx and WebSocket proxy

Use a TLS server block and point `root` to `/var/www/html/ask/public`:

```nginx
server {
  listen 443 ssl http2;
  server_name ask.privilege.global;
  root /var/www/html/ask/public;
  index index.php;
  client_max_body_size 3m;

  location / { try_files $uri $uri/ /index.php?$query_string; }
  location ~ \.php$ {
    include fastcgi_params;
    fastcgi_pass unix:/run/php/php8.4-fpm.sock;
    fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    fastcgi_param DOCUMENT_ROOT $realpath_root;
  }
  location /app/ {
    proxy_pass http://127.0.0.1:8080;
    proxy_http_version 1.1;
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection "Upgrade";
  }
  location ~ /\. { deny all; }
}
```

For this proxy, set server-side `REVERB_HOST=127.0.0.1`, `REVERB_PORT=8080`, `REVERB_SCHEME=http`; client-side `VITE_REVERB_HOST=ask.privilege.global`, `VITE_REVERB_PORT=443`, `VITE_REVERB_SCHEME=https`. Rebuild assets after changing any `VITE_` value.

## 4. Supervisor

`/etc/supervisor/conf.d/ask-reverb.conf`:

```ini
[program:ask-reverb]
command=/usr/bin/php /var/www/html/ask/artisan reverb:start --host=127.0.0.1 --port=8080
directory=/var/www/html/ask
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/var/log/supervisor/ask-reverb.log
stopasgroup=true
killasgroup=true
```

Run `supervisorctl reread && supervisorctl update && supervisorctl status`. If a queue worker is introduced later, add it as a separate Supervisor program; V1 broadcasting is immediate and does not require it.

## 5. Post-deploy checks

Run `php artisan about`, `php artisan test` in a non-production copy, `php artisan optimize`, then open an event from two devices. Confirm question moderation, vote deduplication, QR destination and Reverb updates. Keep `.env` outside Git, rotate Reverb secrets if exposed, and do not run migrations without a backup.
