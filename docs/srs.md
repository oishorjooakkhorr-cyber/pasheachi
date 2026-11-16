# PasheAchi: Your Hands Back Home — Software Requirements Specification

> Copied from the Google Doc **PasheAchi_SRS** (CSE470, Section 5) on 2026-11-16.
> The Google Doc is the source of truth. If it changes, update this file to match.
> Left out of this copy: student IDs, the AI-usage disclosure, the pre-submission checklist and the figures (the class diagram and MVC diagram are in Drive as `PasheAchi_Class_Diagram.png` / `.drawio` and `PasheAchi_MVC_Architecture.png`).

**Team:** Oishorjo Akkhor, Antik Mahmud, Wahida Hujaifa Satu, Mohammad Jawad Bin Mujib
**Repository:** https://github.com/oishorjooakkhorr-cyber/pasheachi
**Tech stack:** PHP 8.3+, Laravel 13 (MVC), Blade + Bootstrap 5, MariaDB 10.4 (MySQL-compatible, via XAMPP)

---

## 1. Introduction

### 1.1 Purpose

PasheAchi is a web application that lets Bangladeshi expatriates arrange everyday help for their family members back home. This document defines the users, requirements, the 20 features, the MVC architecture, the class design, the sprint plan and the test plan.

### 1.2 Scope

PasheAchi (Bangla for "I am beside you") connects three kinds of users:

- **Probashis** (expatriate clients) post errands for their parents or relatives: doctor-visit escorts, medicine or grocery delivery, bill payment, Eid gift shopping, property checks and document collection.
- **Runners** (verified, in the same district) accept errands, complete them and upload photo proof. The family member confirms the handover with a one-time code. The client's payment is held in escrow until that confirmation.
- **Admins** verify runners, manage categories and currency rates, resolve disputes and monitor the platform.

The platform earns a commission on every errand and a priority fee on urgent ones.

**Out of scope:**

- Real payment gateways and real bKash payouts (simulated with database records)
- Live exchange-rate APIs (the admin enters rates)
- SMS delivery (the OTP is shown to the client, who passes it to the family member)
- Live GPS tracking and native mobile apps
- Backup features (in-app chat, favourite runners, parents' view page), unless time allows after Sprint 4

## 2. Overall Description

### 2.1 Product Perspective

A new, standalone web product: an on-demand errand marketplace focused on elderly and family care for Bangladesh's expatriate community, where trust and proof of service matter more than speed. Payments, payouts and notifications are all handled inside the app, so the whole workflow can be demonstrated on one server.

### 2.2 Requirements Summary

| ID | Requirement |
|----|-------------|
| R1 | **Family and care management:** clients keep profiles of family members, see each member's care history and set up monthly recurring errands. |
| R2 | **Errand requests and pricing:** clients post errands and see the full cost in BDT and their own currency before paying; urgent errands get priority. |
| R3 | **Runner matching and tracking:** errands reach verified runners in the right district; the client tracks each errand step by step. |
| R4 | **Proof of service:** runners upload photos and receipts; the family member's one-time code proves the runner was there. |
| R5 | **Safe payments and payouts:** money is held in escrow until the errand is confirmed, then released to the runner, who can withdraw earnings. |
| R6 | **Trust and service quality:** runners are verified, rated after every errand and ranked into trust levels; clients can raise disputes and get refunds. |
| R7 | **Notifications:** users are told about status changes, payments, disputes and upcoming recurring errands. |
| R8 | **Administration:** admins approve runners, manage categories and rates, and monitor the platform from a dashboard. |

### 2.3 Users

- **Probashi (Client):** lives abroad; posts and pays for errands, tracks them, reviews runners, raises disputes. Usually on a phone in another time zone; expects prices in their own currency (USD, SAR, GBP, …).
- **Runner:** verified local helper; accepts errands in their own district, updates status, uploads proof, withdraws earnings. Works mainly on a phone, so **runner screens are mobile-first**.
- **Admin:** verifies runners, manages categories and currency rates, resolves disputes, watches statistics. Uses a desktop browser.
- **Family member (no account):** receives the service. Their details are stored by the client; they give the runner the one-time code at handover.

### 2.4 Operating Environment

- **Client side:** any modern browser, desktop or mobile; responsive layout.
- **Server side:** PHP 8.3+, Laravel 13, MariaDB 10.4 (the MySQL-compatible server bundled with XAMPP), Composer. Node.js/npm only to build the Breeze auth pages (Vite).
- **Development:** XAMPP for the database, `php artisan serve` for the app, Git, GitHub. Scheduler runs with `php artisan schedule:work` in development and a cron entry when deployed.

### 2.5 Design and Implementation Constraints

- **Stack:** PHP 8.3+ with Laravel 13, Blade styled with Bootstrap 5, MariaDB 10.4 (MySQL-compatible, bundled with XAMPP). No MERN and no Python framework.
- **MVC:**
  - **Models** (`app/Models`, Eloquent: `User`, `FamilyMember`, `Errand`, `Payment`, …) hold data and business rules, e.g. `calculateCost()`, `verifyOtp()`, `recalculateTrustLevel()`.
  - **Views** (`resources/views`, Blade) only display data and forms.
  - **Controllers** (`app/Http/Controllers`, e.g. `ErrandController`, `PaymentController`, `Admin\DashboardController`) receive requests from `routes/web.php`, validate with **Form Request** classes, call model methods and return a view or redirect.
  - **Role middleware** limits each route group to clients, runners or admins.
- **AI-backed feature:** none.
- **Third-party packages:** limited to Laravel Breeze (auth scaffolding) and `barryvdh/laravel-dompdf` (PDF receipts).

### 2.6 Assumptions and Dependencies

- Clients and runners have internet access and a smartphone or computer; runners can photograph proof.
- The client can reach the family member by phone to pass on the OTP.
- The admin enters and updates currency rates; no live exchange-rate API.
- Payments and bKash payouts are simulated; no real money moves.
- The server can run the Laravel scheduler every minute.
- **Dependencies:** Laravel 13; Laravel Breeze (Blade stack); `barryvdh/laravel-dompdf`; Bootstrap 5 (CDN); MariaDB 10.4 (via XAMPP).

## 3. System Architecture

### 3.1 Architectural Overview

- **Model** (`app/Models`): one Eloquent class per table. Models hold relationships and business rules: cost calculation, allowed status changes, OTP checking, escrow release, trust-level calculation.
- **View** (`resources/views`): Blade templates grouped by area: `errands/`, `family/`, `runner/`, `admin/`, `payments/`. No queries or business rules in views.
- **Controller** (`app/Http/Controllers`): one controller per feature area, plus an `Admin` sub-folder. Controllers check access (middleware), validate (Form Requests), call model methods, choose the view or redirect.
- **Supporting parts:** `routes/web.php`; role middleware separating client/runner/admin routes; Task Scheduler for recurring errands; Storage for uploads.

**Example trace (F14):** runner submits the family OTP → `POST /errands/{id}/confirm` → `OtpConfirmationController@store` validates the code → `Errand::verifyOtp()` marks the errand `COMPLETED` and `Payment::releaseToRunner()` moves the money inside a DB transaction → redirect to `errands/show` with a success message.

### 3.2 Technology Stack

| Layer | Technology and purpose |
|-------|------------------------|
| Language | PHP 8.3+ on the server; HTML/CSS in Blade views |
| Framework | Laravel 13: routing, controllers, Eloquent, validation, middleware, scheduler |
| Frontend | Blade + Bootstrap 5 (forms, tables, cards, navbar). Server-rendered, no SPA |
| Database | MariaDB 10.4 (MySQL-compatible, via XAMPP) with Eloquent, migrations and seeders (12 tables) |
| Auth & roles | Laravel Breeze (Blade stack) + custom role middleware (client, runner, admin) |
| Scheduling | Laravel Task Scheduler: recurring errands and reminders |
| File storage | Local disk: proof photos on the **public** disk; NID and police-clearance files on a **private** disk |
| PDF | `barryvdh/laravel-dompdf` for escrow receipts |
| Testing | Laravel feature tests (PHPUnit or Pest) + manual test cases |
| Version control | Git + GitHub (branches, PRs, issues) |
| Hosting | Local server for the demo; optional PHP host |
| External APIs | None. Payments, bKash payouts and exchange rates are simulated |

### 3.3 Class Design

15 classes and 6 enumerations (see the class diagram in Drive).

- **Users:** abstract `User` with subclasses `Client`, `Runner`, `Admin`. Stored in one `users` table with a `role` column.
- **Errand workflow:** `FamilyMember`, `ErrandCategory`, `Errand`, `RecurringErrand`, `Proof`
- **Money:** `Payment`, `Withdrawal`, `CurrencyRate`
- **Quality and communication:** `Review`, `Dispute`, `Notification`

**Key relationships:**

- Compositions: Client ◆ FamilyMember, Errand ◆ Proof, User ◆ Notification
- An Errand has 0..1 Payment, 0..1 Review and 0..1 Dispute
- A Runner is assigned 0..* errands
- An Admin verifies runners and resolves disputes
- `CurrencyRate` is a standalone lookup table: the admin updates it; `Errand::calculateCost()` reads it

**Database (12 tables):** `users`, `family_members`, `errand_categories`, `errands`, `recurring_errands`, `proofs`, `payments`, `withdrawals`, `currency_rates`, `reviews`, `disputes`, `notifications`. Enumerations are stored as string columns.

## 4. Requirements and Features

All CRUD on the same entity by the same actor with the same logic counts as **one** feature. Feature IDs F1–F20 are used in sprint records and commit messages (e.g. `F14: verify family OTP`). Login, registration, logout and password reset are supporting functions, not features.

| Req | Feature | Actor | MVC components | Sprint | Owner |
|-----|---------|-------|----------------|--------|-------|
| R1 | **F1 – Family Member Profiles.** Add, view, edit and remove family members with relation, phone, address, district and care notes (e.g. "diabetic, uses a wheelchair"). Only the owning client can see them. | Client | M: `FamilyMember` · V: `family/index`, `family/form` · C: `FamilyMemberController` | 1 | Oishorjo |
| R1 | **F6 – Care History.** Timeline of every past errand for one family member, newest first, with status, cost, proof photos and receipts. | Client | M: `FamilyMember`, `Errand`, `Proof` · V: `family/history` · C: `CareHistoryController` | 2 | Oishorjo |
| R1 | **F7 – Recurring Errands.** Client sets a monthly rule (family member, category, day of month). The scheduler creates the errand each month; rules can be paused and resumed. | Client, System | M: `RecurringErrand`, `Errand` · V: `recurring/index`, `recurring/form` · C: `RecurringErrandController` + scheduled command | 3 | Oishorjo |
| R2 | **F2 – Post an Errand.** Choose category, family member, date/time and description. Validated (future date, active category); saved as `POSTED`. | Client | M: `Errand`, `ErrandCategory` · V: `errands/create` · C: `ErrandController` | 1 | Oishorjo |
| R2 | **F3 – Automatic Cost Calculation.** Total = category service fee + item cost + priority fee, shown in BDT and the client's currency using admin-set rates. Admin keeps the rates up to date. | Client, Admin | M: `Errand`, `CurrencyRate` · V: `errands/create`, `admin/rates` · C: `ErrandController`, `Admin\CurrencyRateController` | 1 | Antik |
| R2 | **F17 – Emergency Errands.** Client marks an errand urgent. A fixed priority fee is added and the errand appears at the top of runners' feeds. | Client, Runner | M: `Errand` · V: `errands/create`, `runner/feed` · C: `ErrandController`, `ErrandFeedController` | 3 | Jawad |
| R3 | **F4 – Runner Selection / Auto-Assign.** Client sees verified runners in the family member's district with rating, trust level and completed count and picks one, or lets the system assign the best available runner. | Client, System | M: `Runner`, `Errand` · V: `errands/choose-runner` · C: `RunnerAssignmentController` | 2 | Antik |
| R3 | **F12 – Runner Errand Feed.** Runner sees open errands in their own district only, urgent first, and accepts one. An errand can be accepted by only one runner. | Runner | M: `Errand` · V: `runner/feed` · C: `ErrandFeedController` | 2 | Wahida |
| R3 | **F5 – Live Status Tracking.** Posted → Accepted → On the Way → In Progress → Completed; only the next valid step is allowed. Client sees the current step with timestamps; the page refreshes automatically. | Client, Runner | M: `Errand` · V: `errands/track` · C: `ErrandStatusController` | 2 | Jawad |
| R4 | **F13 – Status Update & Proof Upload.** The assigned runner updates status and uploads photos or receipts with notes (JPG/PNG/PDF, size-limited), stored as `Proof` records. | Runner | M: `Proof`, `Errand` · V: `runner/errand-show` · C: `ProofController` | 2 | Wahida |
| R4 | **F14 – Family OTP Confirmation.** A one-time code is generated per errand and given to the family member. The runner enters it at handover; a correct code completes the errand and cannot be reused. | Runner, Family member | M: `Errand`, `Payment` · V: `runner/confirm-otp` · C: `OtpConfirmationController` | 3 | Wahida |
| R5 | **F8 – Escrow Payment & PDF Receipt.** Client pays upfront (simulated). Payment stays `HELD` until OTP confirmation, then is `RELEASED` to the runner minus commission. A PDF receipt is generated. | Client, System | M: `Payment`, `Errand` · V: `payments/checkout`, `payments/receipt` · C: `PaymentController` | 3 | Antik |
| R5 | **F15 – Earnings & Withdrawal.** Runner sees balance and earnings history and requests a payout to a bKash number (simulated). Amount cannot exceed the balance. | Runner | M: `Withdrawal`, `Payment`, `Runner` · V: `runner/earnings` · C: `WithdrawalController` | 4 | Antik |
| R6 | **F11 – Runner Registration & Verification.** Runner signs up with district and uploads NID, photo and police clearance. Account stays `PENDING` until an admin approves it (F19). | Runner | M: `Runner` · V: `runner/register` · C: `RunnerRegistrationController` | 1 | Wahida |
| R6 | **F9 – Rate & Review Runner.** After completion the client gives a 1–5 rating and a comment, once per errand. The runner's average rating updates. | Client | M: `Review`, `Errand` · V: `reviews/create` · C: `ReviewController` | 4 | Oishorjo |
| R6 | **F10 – Dispute & Refund.** Client raises a dispute (wrong item, task not done). Admin reviews the proof and resolves it with a full or partial refund, or rejects it. | Client, Admin | M: `Dispute`, `Payment` · V: `disputes/create`, `admin/disputes` · C: `DisputeController`, `Admin\DisputeController` | 4 | Antik |
| R6 | **F18 – Runner Trust Levels.** Bronze, Silver or Gold, recalculated from completed errands, average rating and upheld disputes. Only Gold runners can accept high-value errands. | System, Runner | M: `Runner`, `Review`, `Dispute` · V: `runner/profile` (badge) · C: `RunnerProfileController` | 4 | Wahida |
| R7 | **F16 – Notifications.** In-app notifications for every status change, payment event, dispute update and recurring-errand reminder, with unread count and mark-as-read. | All users | M: `Notification` · V: `notifications/index`, navbar bell · C: `NotificationController` | 3 | Jawad |
| R8 | **F19 – Runner Approval & Category Management.** Admin reviews pending runners' documents and approves or rejects them; adds, edits and deactivates errand categories and their base fees. | Admin | M: `Runner`, `ErrandCategory` · V: `admin/runners`, `admin/categories` · C: `Admin\RunnerApprovalController`, `Admin\CategoryController` | 1 | Jawad |
| R8 | **F20 – Admin Dashboard.** Totals for errands by status, commission earned, active districts and dispute statistics. | Admin | M: `Errand`, `Payment`, `Dispute` · V: `admin/dashboard` · C: `Admin\DashboardController` | 4 | Jawad |

## 5. External Interface Requirements

| Interface | Description |
|-----------|-------------|
| User interface | Responsive Blade + Bootstrap 5 pages with a separate dashboard per role. Validation errors next to each field; costs in BDT and the client's currency; errand status as a step tracker with colour-coded badges; runner screens designed for mobile. |
| Hardware | None special. Runners need a phone camera. |
| Software | MariaDB 10.4 (MySQL-compatible); Laravel Breeze; `barryvdh/laravel-dompdf`; Laravel Storage; SMTP (Mailtrap in development) for optional email notifications. No external payment, SMS or exchange-rate API. |
| Communication | HTTP (HTTPS when deployed) with standard form submissions. The tracking page polls the errand status every few seconds. All forms carry CSRF tokens. |

## 6. Non-Functional Requirements

| Category | Requirement |
|----------|-------------|
| Performance | Pages load within 2 s on the demo server. Runner feed and care history paginated at **15 per page**. Foreign keys plus the `district` and `status` columns are indexed. |
| Security | bcrypt passwords. Every route group requires login and the correct role; runners can change only errands assigned to them. CSRF on all forms, server-side validation, Eloquent against SQL injection. Uploads limited to **JPG/PNG/PDF up to 5 MB**; NID and police-clearance files are private and visible only to admins. Each OTP works once. |
| Usability | A first-time client can post an errand in under 3 minutes. Plain-language labels; runner screens work on small phones; every status has a clear name and colour. |
| Reliability | Money actions (hold, release, refund, withdrawal) run in **DB transactions**. Errand status can only move to the next allowed step. Running the recurring-errand job twice on the same day creates **no duplicates**. |
| Scalability | Stateless controllers, all data in MariaDB. Notifications and recurring jobs can move to queues. New districts and categories are data, not code. |

## 7. Sprint Plan

| Sprint | Review date | Goals | Features |
|--------|-------------|-------|----------|
| 1 | 8 Nov 2026 | Laravel 13 project + GitHub repo; migrations for core tables; auth and role middleware (client, runner, admin). | F1, F2, F3, F11, F19 |
| 2 | 25 Nov 2026 | Runner feed by district, choose/auto-assign runner, status tracking with allowed order, proof upload, care history. First integration test: post → accept → proof. | F4, F5, F6, F12, F13 |
| 3 | 9 Dec 2026 | OTP confirmation, escrow payment + PDF receipt, notifications, emergency errands, recurring errands via scheduler. Integration test: paid errand → OTP → payment released. | F7, F8, F14, F16, F17 |
| 4 | 28 Dec 2026 | Reviews, disputes and refunds, earnings and bKash withdrawal, trust levels, admin dashboard. Regression testing, bug fixing, README, final SRS, demo video (final demo 30 Dec 2026). | F9, F10, F15, F18, F20 |

## 8. Test Plan

Each feature is tested manually at the end of its sprint. Money and status rules are also covered by automated Laravel feature tests against a separate test database.

| ID | Feature | Scenario | Expected result |
|----|---------|----------|-----------------|
| T1 | F1 | Invalid: save a family member with the district empty. | Validation error next to the field; nothing saved. |
| T2 | F2 | Invalid: post an errand with a past date. | Rejected with a clear message; errand not created. |
| T3 | F3 | Normal: service fee, item cost and urgent fee; client currency USD. | Total = sum of the three; USD amount = total ÷ admin-set USD rate. |
| T4 | F4 | Normal: auto-assign for a family member in Sylhet. | Only a verified, available Sylhet runner is chosen. |
| T5 | F5 | Invalid: change status from Posted directly to Completed. | Rejected; only the next step is allowed. |
| T6 | F6 | Normal: open care history for one family member. | Only that member's errands, newest first, with proofs. |
| T7 | F7 | Boundary: run the recurring-errand job twice on the same day. | Exactly one errand per active rule. |
| T8 | F8 | Normal: client pays for an errand. | Payment `HELD`; receipt PDF shows correct amounts. |
| T9 | F9 | Boundary: rating of 0 or 6, or a second review for the same errand. | Rejected; one review of 1–5 per completed errand. |
| T10 | F10 | Invalid: refund larger than the amount paid. | Rejected; a valid refund sets the payment to `REFUNDED`. |
| T11 | F11 | Invalid: upload a .exe or a file over 5 MB as the NID. | Rejected; a valid upload leaves the runner `PENDING`. |
| T12 | F12 | Normal: runner opens the feed. | Only `POSTED` errands from the runner's district; urgent first. |
| T13 | F13 | Security: runner uploads proof to an errand not assigned to them. | 403; nothing stored. |
| T14 | F14 | Wrong OTP, then correct OTP, then the same OTP again. | Wrong refused; correct completes the errand and releases payment; reuse refused. |
| T15 | F15 | Boundary: withdraw more than the balance, then exactly the balance. | First rejected; second accepted, balance becomes 0. |
| T16 | F16 | Normal: runner changes an errand's status. | Client gets a notification; unread count goes up, then down on read. |
| T17 | F17 | Normal: client marks an errand urgent. | Priority fee added; errand appears first in the feed. |
| T18 | F18 | Invalid: a Silver runner tries to accept a high-value errand. | Blocked; a Gold runner can accept it. |
| T19 | F19 | Normal: admin deactivates a category. | Category gone from the post-errand form; old errands unchanged. |
| T20 | F20 | Normal: open the admin dashboard. | Commission earned = sum of commission on released payments. |

**End-to-end integration test** (from Sprint 3): client posts an errand → pays → runner accepts → updates status and uploads proof → enters the OTP → payment is released → client reviews the runner → runner's rating, balance and trust level update.
