# FundiConnect

FundiConnect is a Laravel-based marketplace application for connecting customers with skilled service providers. The platform supports provider discovery, job requests, reviews, disputes, and an admin dashboard for managing trades, providers, and requests.

## Overview

FundiConnect is designed to simplify the process of finding trusted local professionals for various trades. Customers can browse providers, submit job requests, and track their progress, while providers can manage their profile, expertise, and incoming jobs.

## Features

- User registration with customer and provider roles
- Provider profiles with trade specializations and ratings
- Job request workflow from submission to completion
- Review and rating system for completed jobs
- Dispute handling for job-related issues
- Filament-based admin panel for managing platform data
- Modern frontend experience powered by Laravel, Vite, and Tailwind CSS

## Tech Stack

- PHP 8.2+
- Laravel 12
- Filament 5
- Tailwind CSS
- Vite
- MySQL/SQLite-compatible database support and Postgre

## Project Structure

- app/ - Core application logic, models, controllers, and Filament resources
- database/ - Migrations, factories, and seeders
- resources/ - Blade templates, CSS, and JavaScript assets
- routes/ - Web and API route definitions
- tests/ - Feature and unit tests

## Getting Started

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js and npm
- A database such as MySQL or SQLite or Postgre(Recommended)

### Installation

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd fundigo
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install frontend dependencies:
   ```bash
   npm install
   ```

4. Create your environment file:
   ```bash
   cp .env.example .env
   ```

5. Generate the application key:
   ```bash
   php artisan key:generate
   ```

6. Configure your database settings in the .env file, then run migrations:
   ```bash
   php artisan migrate
   ```

7. Build frontend assets:
   ```bash
   npm run build
   ```

### Running the Application

Start the local development environment:

```bash
composer run dev
```

This will launch the Laravel app, queue worker, logs, and Vite development server.

### Testing

Run the test suite with:

```bash
php artisan test
```

## Admin Panel

A Filament admin panel is available for managing providers, trades, disputes, and other platform resources. After setup, open:

```text
/admin
```

## License

This project is open-source software licensed under the MIT license.
