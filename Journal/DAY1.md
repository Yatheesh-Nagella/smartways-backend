# Smartways Backend – Laravel (PHP + Sanctum)

**Date:** 2025-05-25

## 🖥️ Tech Stack
- **Framework:** Laravel 12.x
- **Auth:** Laravel Sanctum for API token authentication
- **Database:** MySQL (via XAMPP)

## ✅ Features Implemented
- ✅ `/api/login` endpoint – returns token + user data
- ✅ `/api/user` – protected user details endpoint
- ✅ Password hashing via `bcrypt()`
- ✅ Manual user creation for access control

## 📦 Packages Installed
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

## 🧪 Testing
- Use Postman or curl:
```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"test@gmail.com","password":"test"}'
```

## 👤 Manual User Creation
```php
php artisan tinker

\App\Models\User::create([
    'name' => 'Test',
    'email' => 'test@gmail.com',
    'password' => bcrypt('test')
]);
```
