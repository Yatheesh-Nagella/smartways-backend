# Smartways Backend Architecture

**Framework:** Laravel 12.x  
**Authentication:** Laravel Sanctum (API Token-based)  
**Database:** MySQL via XAMPP  
**Environment:** XAMPP (Apache + MySQL + PHP)

---

## 🏗️ Project Structure Overview

This is a Laravel API backend designed to work with a React Native frontend. The application uses Laravel Sanctum for stateless API authentication and follows RESTful principles.

### 🔐 Authentication Flow
1. **Login:** Client sends credentials to `/api/login`
2. **Token Generation:** Server validates credentials and returns Sanctum token
3. **Protected Routes:** Client includes `Authorization: Bearer {token}` header
4. **Token Validation:** Sanctum middleware validates token on protected routes

### 🗂️ Key Directories

```
smartways-backend/
├── app/                    # Application logic
│   ├── Http/Controllers/   # Request handling
│   ├── Models/            # Database models (Eloquent)
│   ├── Providers/         # Service providers
│   └── Http/Middleware/   # Custom middleware
├── config/                # Configuration files
├── database/              # Migrations, factories, seeders
├── routes/                # Route definitions (API, web, auth)
├── tests/                 # PHPUnit tests
└── storage/               # Logs, cache, sessions
```

### 🛣️ API Routes

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| `POST` | `/api/login` | User authentication | ❌ |
| `POST` | `/api/forgot-password` | Password reset link | ❌ |
| `GET` | `/api/ping` | Health check | ❌ |
| `GET` | `/api/user` | Get authenticated user | ✅ |

### 🔄 Database Schema

- **users:** Core user authentication table
- **personal_access_tokens:** Sanctum API tokens
- **password_reset_tokens:** Password reset functionality
- **sessions:** Session storage for web auth
- **cache/jobs/failed_jobs:** Laravel system tables

---

## 🚀 Development Workflow

### Local Development
```bash
# Start XAMPP services
# Navigate to project directory
php artisan serve                    # Start development server
php artisan migrate:fresh --seed     # Reset database
php artisan config:clear            # Clear config cache
```

### Testing
```bash
php artisan test                     # Run PHPUnit tests
```

### Database Management
```bash
php artisan migrate                  # Run migrations
php artisan db:seed                  # Seed database
php artisan tinker                   # Interactive console
```

---

## 🔧 Configuration

### Environment Variables (`.env`)
- **Database:** MySQL on port 3307 (XAMPP custom port)
- **App URL:** `http://localhost:8000`
- **Frontend URL:** `http://localhost:3000`
- **Mail Driver:** Log (for development)

### CORS Configuration
- Allows all origins (`*`) for development
- Supports credentials for Sanctum authentication
- Configured for `/api/*` paths

---

## 🧪 Testing Strategy

- **Feature Tests:** End-to-end API testing with database
- **Unit Tests:** Isolated component testing
- **Authentication Tests:** Login, registration, password reset flows
- **Email Verification Tests:** Email verification workflow

---

## 📱 Frontend Integration

This backend is designed to work with:
- **React Native (Expo)** mobile applications
- **Token-based authentication** for stateless API calls
- **CORS-enabled** for cross-origin requests
- **JSON API responses** for easy frontend consumption

---

## 📁 File Structure & Descriptions

### **Root Configuration Files**
- **`.env`** - Environment variables (database credentials, app settings)
- **`.env.example`** - Template for environment configuration
- **`composer.json`** - PHP dependencies and autoloading configuration
- **`phpunit.xml`** - PHPUnit testing configuration
- **`artisan`** - Command-line interface for Laravel commands
- **`.gitignore`** - Git ignore patterns for Laravel projects
- **`.gitattributes`** - Git file handling attributes
- **`.editorconfig`** - Code formatting standards for editors

### **🏗️ Bootstrap & Core**
- **`bootstrap/app.php`** - Application bootstrapping and middleware configuration
- **`bootstrap/providers.php`** - Service provider registration
- **`public/index.php`** - Entry point for web requests
- **`public/.htaccess`** - Apache rewrite rules for clean URLs

### **🛣️ Routes**
- **`routes/api.php`** - API endpoints (login, user, ping, forgot-password)
- **`routes/web.php`** - Web routes (basic Laravel info endpoint)
- **`routes/auth.php`** - Authentication routes (register, login, password reset)
- **`routes/console.php`** - Artisan console commands

### **🎛️ Controllers**
- **`app/Http/Controllers/Controller.php`** - Base controller class
- **`app/Http/Controllers/Api/LoginController.php`** - API login with Sanctum token generation
- **`app/Http/Controllers/Auth/AuthenticatedSessionController.php`** - Web session-based login/logout
- **`app/Http/Controllers/Auth/RegisteredUserController.php`** - User registration
- **`app/Http/Controllers/Auth/PasswordResetLinkController.php`** - Send password reset emails
- **`app/Http/Controllers/Auth/NewPasswordController.php`** - Process password reset
- **`app/Http/Controllers/Auth/EmailVerificationNotificationController.php`** - Send email verification
- **`app/Http/Controllers/Auth/VerifyEmailController.php`** - Verify email addresses

### **📋 Requests & Middleware**
- **`app/Http/Requests/Auth/LoginRequest.php`** - Login validation with rate limiting
- **`app/Http/Middleware/EnsureEmailIsVerified.php`** - Middleware to check email verification

### **🗄️ Models & Database**
- **`app/Models/User.php`** - User model with Sanctum traits
- **`database/factories/UserFactory.php`** - User model factory for testing
- **`database/seeders/DatabaseSeeder.php`** - Database seeding for development
- **`database/migrations/0001_01_01_000000_create_users_table.php`** - User, password reset, and session tables
- **`database/migrations/0001_01_01_000001_create_cache_table.php`** - Cache storage tables
- **`database/migrations/0001_01_01_000002_create_jobs_table.php`** - Queue job tables
- **`database/migrations/2025_05_24_214439_create_personal_access_tokens_table.php`** - Sanctum API tokens

### **⚙️ Configuration**
- **`config/app.php`** - Core application settings
- **`config/auth.php`** - Authentication guards and providers
- **`config/database.php`** - Database connections (MySQL, SQLite, etc.)
- **`config/sanctum.php`** - Sanctum API authentication settings
- **`config/cors.php`** - Cross-origin resource sharing configuration
- **`config/session.php`** - Session storage configuration
- **`config/cache.php`** - Cache driver configuration
- **`config/mail.php`** - Email service configuration
- **`config/queue.php`** - Background job queue configuration
- **`config/services.php`** - Third-party service credentials
- **`config/logging.php`** - Application logging configuration
- **`config/filesystems.php`** - File storage configuration

### **🧪 Testing**
- **`tests/TestCase.php`** - Base test class
- **`tests/Feature/ExampleTest.php`** - Basic application response test
- **`tests/Unit/ExampleTest.php`** - Simple unit test example
- **`tests/Feature/Auth/AuthenticationTest.php`** - Login/logout functionality tests
- **`tests/Feature/Auth/RegistrationTest.php`** - User registration tests
- **`tests/Feature/Auth/PasswordResetTest.php`** - Password reset workflow tests
- **`tests/Feature/Auth/EmailVerificationTest.php`** - Email verification tests

### **📁 Storage & Views**
- **`storage/`** - Contains logs, cache, sessions, and file storage
- **`resources/views/.gitkeep`** - Placeholder for view templates (API-only project)

### **🔧 Service Providers**
- **`app/Providers/AppServiceProvider.php`** - Application service bindings and password reset URL customization

### **📝 Documentation**
- **`README.md`** - Laravel framework information
- **`Journal/DAY1.md`** - Development log from day 1 (initial setup)
- **`Journal/DAY2.md`** - Development log from day 2 (networking issues and fixes)
- **`architecture.md`** - This file - comprehensive architecture documentation

---

## 🎯 Summary

This Laravel backend is well-structured for API development with proper authentication, testing, and configuration management. The Sanctum integration provides secure token-based authentication perfect for mobile app development.