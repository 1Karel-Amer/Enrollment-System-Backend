# Enrollment System

A full-stack enrollment management system.

## Prerequisites

- PHP 8.x
- Composer (for backend dependencies)
- Node.js & NPM (for frontend dependencies)
- MySQL (or a similar database)

## Setup Instructions

## Access Credentials

For testing purposes, you can use the following default account:

- **Email:** `test@example.com`
- **Password:** `password123`

### 1. Clone & Prepare

1. Clone this repository to your local machine.
2. Open the project folder in your terminal.

### 2. Backend Setup

```bash
# Install PHP dependencies
composer install

# Create your environment file from the template
cp .env.example .env

# Generate the application key
php artisan key:generate

# Run migrations and seed the database
php artisan migrate --seed

# Start the Laravel development server
php artisan serve
```
