# Emotsy Tasks — Bug Fix Backlog

> Source of original requirements: `EMOTSY - A BUG FIX ANALYSIS REPORT.pdf` (v1.0, created 2026-05-05 by **Adnan Nazeer**, directed to **Arturs Bernovskis**).
> This is the **first task list** for the Emotsy project. No team sheet yet — format mirrors `career/docs/CAREER_TASKS.md`.
> Status legend: **NEW** = reported, not started · **IN PROGRESS** · **QA** = fixed, awaiting verify · **DONE** = shipped & verified · **NEEDS INFO** = blocked on repro/decision.
> Environment in report: **Staging - Brave** (browser). Local dev now mirrors prod at `app.emotsy.local` (see `CLAUDE.md`).
> Last updated: 2026-06-18 (4 of 5 investigated; 3 fixed + locally verified, 1 needs info, 1 needs a plugin-asset decision).

---

## 🗂️ Checklist (mirrors the bug report)

| Bug ID | Page / Area | Title | Severity | Priority | Status | Reporter | DEV note (verified) |
|--------|-------------|-------|:--------:|:--------:|:------:|----------|------------------------|
| BUG-001 | Subscriber | Updated timestamp not working | LOW | P4 | **DONE** | Adnan | **Root cause:** custom field values map to `custom_*` columns; when an edit doesn't change a tracked column the Subscriber model isn't dirty, so `save()` runs no UPDATE and `updated_at` stays == `created_at`. **Fix:** `Subscriber::updateFields()` now sets `updated_at = now()` before save, so every edit records the edit time. Verified locally: a no-op edit now bumps `updated_at`. |
| BUG-001.1 | Campaign Page | Feature not working (unspecified) | HIGH | P4 | **NEEDS INFO** | Adnan | Report row has **no repro / expected / actual** — only "Campaign Page". Tested locally (admin@emotsy.com): campaigns **list**, **create**, and the **recipients wizard** all load HTTP 200 with no errors. Could not reproduce a failure. *(Note: the prod `Campaign.php` hotfix — now in `emotsy_prod` — only changed `subscribersCount()` behaviour, NOT a crash; not the cause here.)* **Need exact repro steps from Adnan.** |
| SMTP-403 | Subscribe → Welcome email | SMTP 403 "from address does not…" on Welcome email | HIGH | P4 | **DONE** (code) / ops | Adnan | **Two parts.** (1) **Root cause** = SES rejects the list `from_email` because it isn't a verified sender/domain → 403. That's a sending-server/SES **config** fix (verify the sender or set a verified `from_email`). (2) **Code bug** = the welcome-email send ran synchronously and unguarded, so the 403 threw all the way up and **500'd the subscribe flow** even though the subscriber was already saved. **Fix:** wrapped the welcome / goodbye / owner-notification sends in `SendListNotificationToSubscriber` + `SendListNotificationToOwner` in try/catch + `Log::warning` — a mail failure no longer breaks subscribe/unsubscribe. |
| BUG-002 (a) | Content › Products | Products page shows backend code | HIGH | P4 | **DONE** | Adnan | **Reproduced:** `/products` returned **HTTP 500 "Trying to access array offset on null"** = `Source.php:126`. A not-yet-connected WooCommerce source has meta `{"data":null}`, so `getData()['data']['name']` walks through null; Laravel turns the warning into a 500 and (with `APP_DEBUG=true` on prod) renders the debug page = the "back end code" the reporter saw. **Fix:** `Source::getName()` now null-coalesces to `'WooCommerce'`. Verified: `/products` → HTTP 200. |
| BUG-002 (b) | Store & Connections | WordPress plugin install link → 404 | HIGH | P4 | **DIAGNOSED** (needs asset) | Adnan | **Root cause:** the "WordPress Integration" link → `route('download', ['name' => 'acelle-sync.zip'])` → `AssetController@download` serves from `storage/app/download/acelle-sync.zip`, **which doesn't exist** (the whole `storage/app/download/` dir is absent on local AND prod). So it 404s. **Fix path:** build `acelle-sync.zip` from the WordPress integration plugin (`luanpm88/acmsync` = "WordPress Plugin for Acelle" v2.1) and place it in `storage/app/download/` on the server. **Held:** that plugin is a full Laravel app needing a proper build (`composer --no-dev`, npm build, strip `.env`/`.git`/tests); shipping an untested dev build to every customer would be worse than the 404 — needs a decision / the canonical zip. |

> ⚠️ The report reuses **BUG-002** for two different bugs (Products page + WordPress link). Tracked here as **BUG-002 (a)** and **BUG-002 (b)**.

---

## ✅ Fix log — 2026-06-18

All verified on the local prod-mirror (`app.emotsy.local`, prod DB clone).

- **BUG-001** — `app/Model/Subscriber.php` `updateFields()`: set `updated_at = Carbon::now()` before `save()`. A no-op edit now bumps the timestamp (tested: `2020-01-01` → current time).
- **BUG-002 (a)** — `app/Model/Source.php` `getName()`: `return $this->getData()['data']['name'] ?? 'WooCommerce';`. `/products` went from HTTP 500 → 200.
- **SMTP-403** — `app/Listeners/SendListNotificationToSubscriber.php` + `app/Listeners/SendListNotificationToOwner.php`: welcome / goodbye / owner-notification sends wrapped in try/catch + `Log::warning`, so an SES failure no longer 500s subscribe/unsubscribe.
- **BUG-001.1** — investigated, no reproducible failure; awaiting repro steps.
- **BUG-002 (b)** — diagnosed; needs the plugin zip placed in `storage/app/download/` (build decision pending).

### Still needs input
- **BUG-001.1**: exact repro steps for the Campaign Page issue.
- **BUG-002 (b)**: provide the canonical `acelle-sync.zip`, or approve building it from `acmsync`.
- **SMTP-403 (ops)**: verify the list `from_email` as an SES sender/domain so the welcome email actually delivers (the code fix only stops it from breaking subscribe).

---

## 📋 Full bug detail (from the PDF)

### BUG-001 — Subscriber: updated timestamp not working
- **Severity:** Low · **Priority:** P4 · **Status:** New · **Env:** Staging - Brave
- **Steps:** Edit a subscriber's data.
- **Expected:** Edit timestamp updates to the last edit time.
- **Actual:** Created time == edited time stamp (i.e. `updated_at` never changes on edit).
- **Reported by:** Adnan

### BUG-001.1 — Campaign Page
- **Severity:** High · **Priority:** P4 · **Status:** New · **Env:** Staging - Brave
- **Steps / Expected / Actual:** *(blank in the report — title only "Campaign Page")*
- **Reported by:** Adnan
- **Action:** Get repro steps before work. ⚠️ NEEDS INFO.

### SMTP 403 on Welcome email (screenshot, p.4 — no separate Bug ID)
- **Exception:** "The application attempted to send an email entitled *Welcome* to adnan2000nazeer@gmail.com. However, there was a problem with the SMTP service… Error from the SMTP service: **403 {"errors":[{"message":"The from address does no…"** "
- **Stack:** `Acelle\Model\MailList::1693 sendMail` → `MailList::886 sendSubscriptionWelcomeEmail` → `Acelle\Listeners\SendListNotificationToSubscriber::36 handleMailListSubscription` → `MailList::1956 subscribe`
- **Code at fault site:** `$message->setFrom([$subscriber->mailList->from_email => $subscriber->mailList->from_name]); … if (config('app.demo')) { return; } try { $this->send($message, …); } catch (\Exception $ex) { … }`
- **Likely cause:** the mail list's `from_email` isn't a verified SES identity → SES returns 403. Fix = verify the sender/domain in the sending server, and/or validate the from-address before sending so a bad address doesn't 500 the subscribe flow.

### BUG-002 (a) — Content › Products page
- **Severity:** High · **Priority:** P4 · **Status:** New · **Env:** Staging - Brave
- **Steps:** Click the **Product** sub-menu from the Content category.
- **Expected:** Opens the product page.
- **Actual:** "Back end code" (raw backend/error output instead of the page).
- **Reported by:** Adnan

### BUG-002 (b) — Store & Connections page
- **Severity:** High · **Priority:** P4 · **Status:** New · **Env:** Staging - Brave
- **Steps:** Click the "Install WordPress plugin" link on the Store & Connections page.
- **Expected:** Opens the WordPress plugin installation/download page.
- **Actual:** **404 error**.
- **Reported by:** Adnan

---

## Next steps
1. Reproduce each bug on local (`app.emotsy.local`, admin@emotsy.com / 123456).
2. Fill the **DEV note** column with the real root cause once reproduced.
3. Get repro steps for **BUG-001.1** (Campaign Page) from Adnan.
4. Fix → verify on local → deploy via `emotsy_prod` (see [docs/DEPLOY.md](DEPLOY.md)) → mark DONE.
