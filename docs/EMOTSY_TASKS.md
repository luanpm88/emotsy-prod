# Emotsy Tasks — Bug Fix Backlog

> Source of original requirements: `EMOTSY - A BUG FIX ANALYSIS REPORT.pdf` (v1.0, created 2026-05-05 by **Adnan Nazeer**, directed to **Arturs Bernovskis**).
> This is the **first task list** for the Emotsy project. No team sheet yet — format mirrors `career/docs/CAREER_TASKS.md`.
> Status legend: **NEW** = reported, not started · **IN PROGRESS** · **QA** = fixed, awaiting verify · **DONE** = shipped & verified · **NEEDS INFO** = blocked on repro/decision.
> Environment in report: **Staging - Brave** (browser). Local dev now mirrors prod at `app.emotsy.local` (see [SETUP](#) / `CLAUDE.md`).
> Last updated: 2026-06-17 (checklist created from the PDF; all items NEW pending investigation).

---

## 🗂️ Checklist (mirrors the bug report)

| Bug ID | Page / Area | Title | Severity | Priority | Status | Reporter | DEV note (first look) |
|--------|-------------|-------|:--------:|:--------:|:------:|----------|------------------------|
| BUG-001 | Subscriber | Updated timestamp not working | LOW | P4 | NEW | Adnan | Editing a subscriber doesn't bump `updated_at` — "Created time is same as edited time stamp". Likely the subscriber update path saves without touching `updated_at` (mass-update / `timestamps=false` / direct query builder bypassing Eloquent). Check `emt_subscribers.updated_at` after an edit. |
| BUG-001.1 | Campaign Page | Feature not working (unspecified) | HIGH | P4 | NEEDS INFO | Adnan | Report row has **no repro / expected / actual** — only "Campaign Page". Need exact steps from Adnan before targeting. May relate to the `Campaign.php` subscriber-count change already on prod (uncommitted hotfix, now committed to `emotsy_prod`). |
| BUG-001.x (SMTP) | Subscribe → Welcome email | SMTP 403 "from address does not…" on Welcome email | HIGH | P4 | NEW | Adnan | Stack: `MailList.php:1693 sendMail` ← `:886 sendSubscriptionWelcomeEmail` ← `SendListNotificationToSubscriber:36` ← `:1956 subscribe`. SES rejects the `from_email` (`$subscriber->mailList->from_email`) because it isn't a verified sender / domain. Mostly a sending-server / SES verification config issue, but the welcome-email path should fail gracefully (it currently throws to the user). PHP 8.3.15. |
| BUG-002 (a) | Content › Products | Products page shows backend code | HIGH | P4 | NEW | Adnan | Click **Products** submenu under Content → instead of the product page it shows "Back end code" (raw error / stack instead of a rendered page). Likely an unhandled exception or a debug dump on the products route/controller. Reproduce on local then read the actual exception. |
| BUG-002 (b) | Store & Connections | WordPress plugin install link → 404 | HIGH | P4 | NEW | Adnan | The "Install WordPress plugin" link on the Store/Connections page points at a dead URL (404). Expected: opens the WP plugin download page. Find the hard-coded link and point it at the correct/current plugin URL. |

> ⚠️ The report reuses **BUG-002** for two different bugs (Products page + WordPress link). Tracked here as **BUG-002 (a)** and **BUG-002 (b)**.

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
