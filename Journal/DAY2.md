# DAY 2 - Journal Entry

**Date:** 2025-05-26
**Location:** From Oklahoma City to Dallas, TX
**Setup Context:** Laravel + MySQL (XAMPP) backend with React Native frontend (Expo Go on iOS)

---

## 🚗 Travel & Environment Setup

* Traveled from **Oklahoma City to Dallas**.
* Checked in to a hotel with new **Wi-Fi network**.
* Attempted to run a **React Native Expo app** (frontend) with a Laravel backend hosted on **localhost**.

---

## 🔧 Issues Encountered & Diagnostics

### 🐘 **MySQL Service Not Starting**

**Symptoms:**

* `mysql_error.log` showed repeated starts with InnoDB crash recovery logs.
* XAMPP logs: `MySQL shutdown unexpectedly`

**Diagnostics:**

* Checked `netstat -ano` → verified no port 3306 conflict.
* Found MySQL running on **port 3307** in `my.ini`

**Fix:**

* Ensured port consistency in `.env`:

  ```env
  DB_PORT=3307
  ```
* Manually verified MySQL was working:

  ```bash
  cd C:\xampp\mysql\bin
  .\mysql.exe -u root -P 3307 -h 127.0.0.1 -p
  ```
* Created database manually:

  ```sql
  CREATE DATABASE smartways CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
  ```

---

### 🛠️ **Laravel Migrations Broken**

**Symptoms:**

* Errors like: `Table 'migrations' already exists` or `Tablespace exists. Please DISCARD before IMPORT`

**Cause:**

* MySQL tables had orphaned `.ibd` or `tablespace` metadata files.

**Fixes Tried:**

* `php artisan migrate:rollback` → failed
* Final Fix:

  * Dropped the DB manually via MySQL console
  * Re-created DB
  * Then ran:

    ```bash
    php artisan config:clear
    php artisan migrate:fresh --seed
    ```

---

### 📡 **Expo App Not Loading on iPhone**

**Symptoms:**

* QR scanned via Expo Go app, but app did **not load** → timed out
* Error: `unknown error: the request timed out`

**Cause:**

* Phone and laptop were on the same Wi-Fi, but:

  * Firewall blocked Metro bundler (port 8081)
  * Expo was using `LAN` mode which required direct visibility to `172.20.4.188:8081`

**Fix:**

1. Allowed `node.exe` through Windows Firewall
2. Switched to tunnel mode:

   ```bash
   npx expo start --tunnel
   ```
3. Ensured API endpoint used internal Wi-Fi IP:

   ```ts
   fetch('http://172.20.4.188:8000/api/user', ...)
   ```
4. Expo worked! iOS app successfully loaded.
4. Route::get('/ping', function () {
    return response()->json(['pong' => true]);
});

---

## ✅ Summary of Key Commands Used

```bash
# Clear config cache
php artisan config:clear

# Fresh DB setup
php artisan migrate:fresh --seed

# Start Expo in tunnel mode
npx expo start --tunnel

# Manually access MySQL via CLI
cd C:\xampp\mysql\bin
.\mysql.exe -u root -P 3307 -h 127.0.0.1 -p

# Create database in SQL
CREATE DATABASE smartways CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

---

## 💡 Learnings

* Always validate port conflicts and ensure firewall exceptions for local dev tools.
* Switching to `--tunnel` mode in Expo is a **reliable workaround** for network issues.
* Diagnostic logs (`php artisan`, XAMPP, Postman, and iOS Expo error screens) are vital for pinpointing issues.
* Manual DB cleanup can sometimes be more effective than scripted Laravel rollback commands.

---

**Next Steps:**

* Set up `.env` file switcher for environments (home, mobile hotspot, hotel)
* Automate DB reset commands with a script
* Create a frontend UI for login and testing token persistence
