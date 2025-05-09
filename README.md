# Wishlist app

A modern web application that provides a **_Wishlist_** feature for an e-commerce environment.

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Running the Application](#running-the-application)
- [API Documentation](#api-documentation)
- [Testing](#testing)

## Requirements

- PHP >= 8.2
- Composer

## Installation

### Step 1: Clone the repository

```bash
git clone https://github.com/MusahMusah/executivepro-assessment.git
cd executivepro-assessment
```

### Step 2: Install PHP dependencies

```bash
composer install
```

### Step 3: Create environment file

```bash
cp .env.example .env
```

### Step 4: Generate application key

```bash
php artisan key:generate
```

### Step 5: Setup database

Create a sqlite database file in the `database` directory with name `database.sqlite`.

### Step 6: Run migrations

```bash
php artisan migrate
```

### Step 7: Seed the database (optional)

```bash
php artisan db:seed
```

## Running the Application

### Development Server

```bash
php artisan serve
```

The application will be available at [http://localhost:8000](http://localhost:8000).

## API Documentation

API documentation is available through Postman at:

[Postman Documentation](https://universal-escape-417744.postman.co/workspace/My-Workspace~dc681f5f-208e-43e6-8f80-8a8b659037d2/collection/12541181-6dd35111-96e1-4a41-9f29-86b1ce366abc?action=share&creator=12541181&active-environment=12541181-ea6e7600-fc6f-48bd-a99b-b6d3971de9f2)

### Authentication

The API uses Laravel Sanctum for authentication. To authenticate:

1. Make a POST request to `/api/login` with your credentials
2. Use the returned token in subsequent requests as a Bearer token

Example:

```
Authorization: Bearer YOUR_TOKEN_HERE
```

### Rate Limiting

API endpoints are rate-limited to 60 requests per minute per user.

## Testing

### Running Tests

To run all tests:

```bash
php artisan test
```

To run specific test suites:

```bash
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
```

To run a specific test file:

```bash
php artisan test tests/Feature/ExampleTest.php
```

## Troubleshooting

### Common Issues

#### Permission Issues

If you encounter permission errors:

```bash
chmod -R 775 storage bootstrap/cache
```

#### Composer Memory Limit

If Composer runs out of memory:

```bash
COMPOSER_MEMORY_LIMIT=-1 composer install
```

#### Cache Issues

To clear all application caches:

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```