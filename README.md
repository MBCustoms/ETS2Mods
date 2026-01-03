# ETS2LT - Advanced Mod Sharing Platform

A modern, robust, and feature-rich platform for sharing Euro Truck Simulator 2 (ETS2) mods, built with Laravel 12.

## 🚀 Key Features

### Core Functionality
-   **Mod Management**: Upload, edit, and version control for mods.
-   **Advanced Search**: Full-text search with filtering by Category, Game Version, and flexible Sorting (Popular, Top Rated).
-   **User Profiles**: Public profiles with badges (Verified, Modder, Elite), bio, and social links.
-   **Roles & Permissions**: Granular access control for Admins, Moderators, and Users.

### Community & Engagement
-   **Interactive Ratings**: Star rating system with reviews.
-   **Comments**: Threaded comments for discussions.
-   **Follow System**: Users can follow mods and receive notifications for updates.

### Security & Integrity
-   **Advanced Moderation**:
    -   Report system with severity levels (Low to Critical).
    -   **Auto-Flagging**: Mods are automatically flagged for review if they receive excessive high-severity reports.
    -   Admin Report Queue for efficient dispute resolution.
-   **Anti-Abuse**:
    -   **Download Rate Limiting**: Prevents scraping and bandwidth abuse (limit: 50 downloads/hour per IP).
    -   **Anti-Spam**: Prevents stats manipulation by ignoring repeated downloads from the same IP within 1 hour.
    -   **Verified Authors**: Trusted status for consistent high-quality contributors.

### Admin & Analytics
-   **Dashboard**: Real-time overview of total mods, users, downloads, and pending actions.
-   **Activity Log**: Detailed audit trail of all admin actions.
-   **User Management**: detailed user controls including verification toggling.

### Internationalization
-   **i18n Ready**: Native support for multi-language switching (EN, ES, DE, FR supported structure).

## 🛠️ Tech Stack
-   **Framework**: Laravel 12.x
-   **Frontend**: Blade, Livewire 3, Tailwind CSS, Alpine.js
-   **Database**: MySQL 8.0+
**(This README was regenerated to include technical setup and developer notes.)**

# ETS2LT — Mods Platform (Laravel)

A community-driven mods listing and distribution platform built with Laravel. This repository contains the web application for browsing, uploading, rating, and downloading mods for Euro Truck Simulator 2 and American Truck Simulator.

## Contents
- Summary and purpose
- Tech stack
- Local development setup
- Useful artisan / npm commands
- Project architecture and key files
- Data model highlights
- Media, uploads and credits handling
- Authentication, authorization and policies
- Routes of interest
- Debugging & troubleshooting notes
- Deployment hints

## Summary
This app provides:
- Public mod browsing (featured, latest, popular)
- Category listings and global search
- Mod upload flow with multiple images and versioning
- User profiles with avatars and badges
- Ratings, comments, and follow/notification features
- Admin area for moderation and management

## Tech stack
- PHP: compatible with PHP 8.2+ (runtime used: 8.3.x)
- Laravel Framework: ^12.0
- Livewire: ^3.7 (dynamic mod search component)
- Spatie packages: Activitylog, MediaLibrary, Permission
- Frontend: Tailwind CSS + Vite
- Testing: PHPUnit

Dependencies are declared in `composer.json` and `package.json`.

## Quick setup (Development)
1. Clone the repo:

    git clone <repo-url> ets2lt
    cd ets2lt

2. Install PHP dependencies:

    composer install

3. Copy environment and generate key:

    cp .env.example .env
    php artisan key:generate

4. Configure database in `.env` (MySQL, Postgres, or local SQLite). Example SQLite (quick start):

    touch database/database.sqlite
    DB_CONNECTION=sqlite
    DB_DATABASE=/full/path/to/database/database.sqlite

5. Run migrations & seeders (if any):

    php artisan migrate
    php artisan db:seed --class=SomeSeederName  # optional

6. Install node deps and build assets:

    npm install
    npm run dev   # or `npm run build` for production

7. Create storage symlink:

    php artisan storage:link

8. Run dev server:

    php artisan serve

Open http://127.0.0.1:8000

## Composer scripts (shortcuts)
- `composer setup` — installs deps, prepares `.env`, runs migrations, builds assets (documented in composer.json)
- `composer dev` — runs the helper dev script defined in `scripts` (concurrently server, queues, vite, etc.)

## Key artisan & npm commands
- Clear caches:

  php artisan cache:clear
  php artisan config:clear
  php artisan route:clear
  php artisan view:clear

- Run tests:

  php artisan test

- Static checks and formatting:

  ./vendor/bin/pint  # or `php artisan pint` if available

## Project architecture / Important files
- `app/Models/` — Eloquent models (`Mod`, `User`, `Category`, `ModImage`, `ModVersion`, etc.)
- `app/Http/Controllers/` — Controllers for pages and API-like endpoints
  - `ModController.php` — listing, store, show, download
  - `UserProfileController.php` — profile viewing and editing
  - `HomeController.php` — now re-uses `mods.index` listing
- `app/Services/` — application services
  - `ModService.php` — core mod creation and versioning logic
  - `ImageUploadService.php` — image upload & deletion logic
  - `DownloadSecurityService.php` — download counting and anti-abuse checks
- `app/Livewire/ModSearch.php` — Livewire component powering `/mods` search and filters
- `resources/views/mods/` — main views for mod listing, create and show pages
- `resources/views/livewire/mod-search.blade.php` — legacy/alternate Livewire view
- `resources/views/users/` — profile templates
- `routes/web.php` — web routes (note: route `/search` added for site-wide search)

## Data model highlights
- `Mod` — has many `ModImage`, has many `ModVersion` (versions store download_url, file_size, game_version). `Mod` contains `credits` field and other meta.
- `ModImage` — stores uploaded image path, `is_main`, and ordering.
- `User` — `avatar` field and `getAvatarUrlAttribute()` helper for display.

## Media / Uploads / Credits
- Uploads are handled by `ImageUploadService`. Uploaded files are stored in configured storage disk and references are saved in `mod_images`.
- The mod upload form accepts multiple files (the create view was updated to allow merging additional selected files without having to remove existing selections).
- `credits` field: optional free-text field saved on `Mod` and shown alongside mod details.
- Note: The app previously had a compatibility wrapper for Spatie MediaLibrary; a compatibility method in `Mod` (`getMedia`) was adjusted to match Spatie's `HasMedia` signature to avoid fatal errors.

## Authentication & Authorization
- Uses Laravel auth building blocks and `AuthorizesRequests` trait in base `Controller` to support `$this->authorize()` in controllers.
- `spatie/laravel-permission` is used for roles and permissions (admin, moderator, etc.).
- Policies exist under `app/Policies` for `Mod`, `ModComment`, `Category`, etc.

## Routes of interest
- `/` — homepage listing (now uses `mods.index` view)
- `/mods` — Livewire-powered search listing (registered as a Livewire component)
- `/search` — Dedicated search route handled by `ModController@index` (use this for homepage/category search forms)
- `/mods/create` — mod upload form (auth required)
- `/mods/{mod}` — mod detail
- `/mods/{mod}/download` — download redirect (counts downloads via `DownloadSecurityService`)
- `/categories/{category}` — category listing (uses `mods.index` to show only category mods)
- `/user/{user}` — user profile

## Search behavior
- The dedicated `/search` route accepts `search`, `category`, and `game_version` query parameters and filters mods accordingly.
- The site's search box (on home and category pages) is wired to submit to `/search` so results are consistent across pages.

## Troubleshooting & common fixes
- If you see a fatal error about `authorize()` being undefined, make sure `app/Http/Controllers/Controller.php` uses the `AuthorizesRequests` trait.
- If you encounter an incompatible `getMedia` method signature with Spatie MediaLibrary, ensure the `getMedia` method matches `HasMedia::getMedia(string $collectionName = 'default', callable|array $filters = []): Illuminate\\Support\\Collection` and returns a `Collection`.
- When changing view or route code, clear compiled views and caches:

  php artisan view:clear
  php artisan cache:clear

- If uploads don't appear or storage files are inaccessible, ensure you created the storage link:

  php artisan storage:link

## Testing & CI
- PHPUnit is configured; run tests with `php artisan test` or `vendor/bin/phpunit`.
- Use `composer test` or the `test` composer script if available.

## Deployment notes
- Build assets with `npm run build`.
- Ensure `APP_ENV=production`, `APP_DEBUG=false` and correct `APP_KEY` and database credentials in `.env`.
- Run migrations on the server and ensure storage is writable.
- Use queue worker and scheduler in production (Supervisor, systemd or similar).

## Developer notes and where to look next
- Mod creation flow: `resources/views/mods/create.blade.php` -> `ModController@store` -> `ModService::createMod` -> `ImageUploadService` + `ModImage` records.
- Download counting: `DownloadSecurityService::recordDownload` — responsible for throttling/uniqueness and anti-abuse logic.
- Livewire UI: `app/Livewire/ModSearch.php` controls client-side filtering; frontend is in `resources/views/livewire/mod-search.blade.php`.

If you'd like, I can:
- Add a short development checklist to `README.md` (migrations, seeders, admin creation).
- Add sample `.env` keys with explanations.
- Generate documentation for API endpoints or add OpenAPI spec.

---
README generated on: 2026-01-03
