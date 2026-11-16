# PasheAchi: Your Hands Back Home

PasheAchi ("I am beside you") is a web app that lets Bangladeshi expatriates (probashis) arrange everyday help for their family members back home: doctor-visit escorts, medicine and grocery delivery, bill payment, property checks and more. Verified local runners accept the errands and upload photo proof. The family member confirms the handover with a one-time code, and the client's payment is held in escrow until then.

Built for CSE470 (Software Engineering), Section 5. The full requirements are in [docs/srs.md](docs/srs.md).

## Tech stack

- PHP 8.3+ and Laravel 13 (MVC)
- Blade templates styled with Bootstrap 5
- MariaDB 10.4 (the MySQL-compatible server bundled with XAMPP)
- Laravel Breeze for login and registration

## Team

- Oishorjo Akkhor: client and family side
- Antik Mahmud: pricing, matching and payments
- Wahida Hujaifa Satu: runner side
- Mohammad Jawad Bin Mujib: admin and system

## Setup

You need PHP 8.3+, Composer, Node.js and XAMPP with its MySQL (MariaDB) server running. The whole team uses XAMPP so everyone has the same database server.

1. Install dependencies:

   ```bash
   composer install
   npm install
   ```

2. Copy the environment file and generate the app key:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. Create an empty database called `pasheachi`. `.env.example` connects as `root` with no password; change `DB_USERNAME` and `DB_PASSWORD` in `.env` if yours differ. Then run the migrations:

   ```bash
   php artisan migrate
   ```

4. Start the asset build and the server, each in its own terminal:

   ```bash
   npm run dev
   php artisan serve
   ```

5. Open http://localhost:8000.

Run the tests with `php artisan test`.
