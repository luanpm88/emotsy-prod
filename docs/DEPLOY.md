# Deploy — Emotsy (app.emotsy.com)

> **Canonical branch from now on: `emotsy_prod`.**
> The repo history was messy (work was being committed across `emotsy` / `emotsy-content-hub` with 49 unpushed prod commits + an uncommitted hotfix). `emotsy_prod` was cut from the **exact running prod state** (`/var/www/prod` HEAD `3654215e32` + the uncommitted `Campaign.php` hotfix, now committed) so the branch == production. Use it for all future work and deploys to keep prod identical.

---

## Topology

| | Value |
|---|---|
| **Prod URL** | https://app.emotsy.com |
| **Prod server path** | `/var/www/prod` |
| **SSH host alias** | `emotsy` (`ssh emotsy`) |
| **Web server** | **Apache** (NOT nginx). 4 GB RAM — OOM-prone; systemd auto-restart override is in place. |
| **Local path** | `~/apps/emotsy` |
| **Local URL** | http://app.emotsy.local (nginx vhost → `public/`) |
| **DB (prod)** | `prod` / user `prod` · table prefix `emt_` |
| **DB (local)** | `emotsy` / root · same prefix `emt_` |
| **Queue** | `database` driver — always `queue:restart` after deploy |

### Git remotes (both local and `/var/www/prod`)
| Remote | URL | Role |
|--------|-----|------|
| `origin` | `git@github.com:louisitvn/acellemail.git` | server deploy remote (server pulls from here) |
| `prod` | `git@github.com:luanpm88/emotsy-prod.git` | dedicated Emotsy working repo |

**Push to BOTH** so they stay in sync (same rule as the career project).

---

## One-time: publish `emotsy_prod` + switch the server onto it

Do this once. After it, use the **Ongoing deploy** flow below.

```bash
# 1. Local — publish the canonical branch to BOTH remotes
cd ~/apps/emotsy
git push -u origin emotsy_prod
git push     prod   emotsy_prod

# 2. Server — switch /var/www/prod onto emotsy_prod (it's the same code, so no behaviour change)
ssh emotsy
cd /var/www/prod
git stash         # only if anything is still uncommitted (the Campaign.php hotfix is already in emotsy_prod)
git fetch origin
git checkout emotsy_prod
git pull origin emotsy_prod
php composer.phar install --no-interaction --no-dev
php artisan migrate --force
php artisan queue:restart
exit
```

> The switch is safe because `emotsy_prod` was branched from the server's current HEAD and the only added commits are the already-applied `Campaign.php` hotfix + a `.gitignore` line. Verify nothing breaks: `curl -I https://app.emotsy.com/login` → `200`.

---

## Ongoing deploy

```bash
# Local — make sure you're current, then push to both remotes
cd ~/apps/emotsy
git checkout emotsy_prod
git fetch --all
# If a teammate pushed: git merge origin/emotsy_prod   (resolve conflicts)
# ... do work, commit ...
git push origin emotsy_prod
git push prod   emotsy_prod

# Server — pull + rebuild
ssh emotsy
cd /var/www/prod
git pull origin emotsy_prod
php composer.phar install --no-interaction --no-dev   # only if composer.lock changed
php artisan migrate --force                            # only if new migrations
php artisan queue:restart
exit

# Verify
curl -I https://app.emotsy.com/login   # expect 200
```

---

## Gotchas (read before deploying)

1. **Keep prod's `.env` and `APP_KEY` untouched.** The server `.env` holds the real DB creds, SES SMTP creds, Google OAuth, and the `APP_KEY` that decrypts stored secrets. **Never** run `key:generate` on the server. The local `.env` is a separate copy (`app.emotsy.local`, DB `emotsy`); prod's original is saved locally as `.env.prod.bak` (gitignored).
2. **Table prefix is `emt_`** (`DB_TABLES_PREFIX=emt_`). Eloquent applies it automatically; raw SQL must use `emt_users`, `emt_campaigns`, etc.
3. **Do NOT `route:cache`.** Acelle registers plugin/dynamic routes — route caching can break them. `config:cache` is generally fine on Acelle but clear it on any `.env` change; when unsure, don't cache.
4. **Apache, not nginx, on prod** — and only 4 GB RAM. A heavy deploy/composer run can OOM Apache. If the site 500s/refuses after deploy, check `systemctl status apache2`; the auto-restart override should bring it back.
5. **Always `queue:restart`** after deploy — workers hold stale code otherwise (queue driver is `database`).
6. **`--no-dev` on the server** composer install (don't pull dev deps onto prod).
7. **Sending-server / SES from-address must be verified** — see BUG-001.x (SMTP 403) in [EMOTSY_TASKS.md](EMOTSY_TASKS.md). A bad `from_email` 403s the welcome-email path.

---

## Local dev quick reference
See [CLAUDE.md](../CLAUDE.md) for the full local setup. TL;DR: `~/apps/emotsy`, branch `emotsy_prod`, DB `emotsy` (root/123456), URL `http://app.emotsy.local`, login `admin@emotsy.com` / `123456`.
