# CLAUDE.md — Project Guide for Emotsy (app.emotsy.com)

> Fast index for any session to catch up. For deploys see [docs/DEPLOY.md](docs/DEPLOY.md); for the bug backlog see [docs/EMOTSY_TASKS.md](docs/EMOTSY_TASKS.md).

## What this is
**Emotsy** — an email-marketing / automation SaaS built on **Acelle Mail** (Laravel). Live at **https://app.emotsy.com**. This is a **separate product** from the career/Stevejober app (`~/apps/career`, also Acelle-based) — don't mix them.

- Multi-tenant SaaS (`APP_SAAS=true`, `APP_EMOTSY=true`).
- Core domain: customers, subscribers, mail lists, campaigns, automations, sending servers, products/store, WordPress connections.
- 98 tables, all prefixed **`emt_`** (e.g. `emt_users`, `emt_customers`, `emt_campaigns`, `emt_subscribers`).

## Repo & branch
- **Canonical branch: `emotsy_prod`** (cut from the exact prod state — see DEPLOY.md). Work here.
- Remotes: `origin` = `louisitvn/acellemail` (server deploy), `prod` = `luanpm88/emotsy-prod`. **Push to BOTH.**
- Old branches (`emotsy`, `emotsy-content-hub`) were messy — superseded by `emotsy_prod`.

## Local dev (already set up at `~/apps/emotsy`)
| | |
|---|---|
| URL | http://app.emotsy.local (nginx vhost → `public/`, server_name `app.emotsy.local emotsy.local`) |
| Login | **admin@emotsy.com / 123456** (password reset locally; prod password differs) |
| DB | `emotsy` (MySQL, user `root` / `123456`), prefix `emt_` |
| PHP | 8.3 · Composer 2.8 |
| `.env` | local copy: `APP_URL=http://app.emotsy.local`, `DB_DATABASE=emotsy`, `MAIL_MAILER=log`. **Prod APP_KEY kept** (don't regenerate — decrypts stored secrets). Prod `.env` backed up as `.env.prod.bak` (gitignored). |

**How it was built:** full `rsync` mirror of `/var/www/prod` (incl. `.env`, `.git`, `vendor`, `storage`) → `~/apps/emotsy`; prod DB dumped → imported into local `emotsy`. `storage/app/installed` marker present (Acelle installer check). To rebuild local from prod, repeat that mirror + DB import.

### `/etc/hosts` — one manual step (needs sudo)
```bash
echo '127.0.0.1  app.emotsy.local' | sudo tee -a /etc/hosts
```
(The nginx vhost + reload are already done. Without this line the browser can't resolve `app.emotsy.local`, though `curl -H "Host: app.emotsy.local" http://127.0.0.1/` already works.)

## Deploy
`emotsy_prod` → push to both remotes → `ssh emotsy` → `cd /var/www/prod && git pull && composer install --no-dev && artisan migrate --force && artisan queue:restart`. Full steps + the one-time server branch-switch in [docs/DEPLOY.md](docs/DEPLOY.md).

## Current work
First task list = the **bug-fix analysis report** (Adnan, v1.0). Tracked in [docs/EMOTSY_TASKS.md](docs/EMOTSY_TASKS.md):
1. **BUG-001** — subscriber edit doesn't update `updated_at` (LOW)
2. **BUG-001.1** — Campaign Page issue (HIGH, **needs repro steps**)
3. **SMTP 403** — Welcome email "from address" rejected by SES (`MailList.php:1693`)
4. **BUG-002 (a)** — Content › Products page shows backend code (HIGH)
5. **BUG-002 (b)** — Store & Connections WordPress plugin link → 404 (HIGH)

## Gotchas
1. **Table prefix `emt_`** — raw SQL needs the prefix; Eloquent applies it automatically.
2. **Apache on prod (not nginx), 4 GB RAM** — OOM-prone; systemd auto-restart override in place.
3. **Don't `route:cache`** (Acelle dynamic routes); clear `config:cache` on `.env` change.
4. **Never `key:generate` on prod** — APP_KEY decrypts stored secrets.
5. **`MAIL_MAILER=log` locally** so dev doesn't hit SES.
6. **Always `queue:restart`** after deploy (queue driver = database).
7. Server `.env` ≠ local `.env`; prod copy is `.env.prod.bak` (gitignored, holds secrets — never commit).

## Code layout (Acelle Mail)
- Models: `app/Model/` (e.g. `Campaign.php`, `MailList.php`, `Subscriber.php`, `Customer.php`)
- Controllers: `app/Http/Controllers/`
- Views: `resources/views/`
- Routes: `routes/web.php`, `routes/api.php`
- Config: `config/` (`app.php` has the `emotsy`/`saas` flags)
