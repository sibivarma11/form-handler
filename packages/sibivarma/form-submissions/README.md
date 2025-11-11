# Form Submissions Package

A Laravel package for handling form submissions with email notifications and Filament admin interface.

## Installation

```bash
composer require sibivarma/form-submissions
```

## Setup

1. Publish and run migrations:
```bash
php artisan vendor:publish --tag=form-submissions-migrations
php artisan migrate
```

2. Publish config (optional):
```bash
php artisan vendor:publish --tag=form-submissions-config
```

3. Configure mail settings in `.env`:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_ENCRYPTION=tls
MAIL_PASSWORD="your-app-password"
MAIL_FROM_ADDRESS="your-email@gmail.com"
FORM_SUBMISSIONS_EMAIL="admin@example.com"
```

## Usage

Submit forms via API:
```bash
curl -X POST http://your-app.com/api/form-submission \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "message": "Hello world"
  }'
```

## Features

- API endpoint for form submissions
- Email notifications
- Filament admin interface (if Filament is installed)
- Validation
- IP address and user agent tracking