<p align="center">
  <img src="public/img/logo.svg" alt="Larding CMS" width="180">
</p>

<p align="center">
  <a href="./README.md">English</a> | <a href="./README.ru.md">Русский</a>
</p>

# Larding CMS 3.0.0

Larding CMS is a lightweight Laravel-based CMS for landing pages, small corporate websites, and custom client projects.

This release includes a web installer, block-based page management, lead collection, file management, roles, and an admin panel that can be deployed on standard PHP hosting.

## Release Status

`Larding 3.0.0` is the first public release build of the project.

This package is intended as a ready-to-install distribution:
- production `vendor` is already included
- install wizard is included
- no Node.js is required on the hosting itself

## Main Features

- Web installer available at `/install`
- Block-based landing page structure
- Admin panel for content management
- Lead form handling
- File manager
- User roles with super-admin support
- Seeded starter content for a fresh installation

## Server Requirements

- PHP `8.2+`
- MySQL `8+` or compatible MariaDB
- PHP extensions:
  - `pdo`
  - `pdo_mysql`
  - `mbstring`
  - `openssl`
  - `fileinfo`

The installer also requires write access to:

- `storage/`
- `bootstrap/cache/`

## Installation

### 1. Upload files

Upload the contents of this package to your hosting.

If your hosting allows changing `DocumentRoot`, point it to `public/`.

If your hosting does not allow changing `DocumentRoot`, this release is already prepared for root-level launch and includes:

- root `index.php`
- root `.htaccess`

That means the package can be placed directly into the public web directory.

### 2. Prepare environment

Create `.env` from `.env.example`:

```bash
cp .env.example .env
```

Set at least:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_INSTALLED=false`
- your `DB_*` connection values

Do not mark the application as installed before running the installer.

### 3. Open installer

Open your site in the browser:

```text
https://your-domain/install
```

Installation flow:

1. Requirements check
2. Database configuration
3. Database initialization
4. First administrator creation

After a successful install, the application writes the final settings and marks itself as installed.

## Notes About Hosting

- Node.js is not required on the production hosting
- front-end assets are already included in the package
- this build is intended for standard shared hosting or a simple VPS setup

## Project Structure

- `app/`, `bootstrap/`, `config/`, `database/`, `resources/`, `routes/`:
  application source
- `public/`:
  public assets
- `vendor/`:
  production PHP dependencies
- `storage/`:
  runtime files and logs

## First Public Release

This public release is being published to collect early feedback on:

- installer behavior
- hosting compatibility
- release packaging
- usability of the CMS core

If you test this build and notice problems or architectural issues, that feedback is especially valuable at this stage.

## License

This release is published for evaluation and development feedback.

If you plan to distribute or commercialize Larding CMS further, define and add the final license in the next public iteration.
