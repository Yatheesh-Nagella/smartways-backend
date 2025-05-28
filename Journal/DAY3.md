# 🚚 Smartways Logistics – Day 3 Progress (May 27, 2025)

## ✅ Objectives Accomplished

### 1. 🔐 Forgot Password Feature (Frontend + Backend)
- Designed and implemented a `ForgotPasswordScreen` in React Native (Expo).
- Included Smartways and Carbon logos, email input centered in the layout.
- On email submission, a password reset link is requested from the backend.

### 2. ⚙️ Backend Laravel API for Password Reset
- Defined routes in `routes/api.php`:
  - `POST /forgot-password` – sends reset link via email.
  - `POST /reset-password` – accepts token, new password, and email.
- Added validation and email delivery logic using Laravel's built-in `Password::sendResetLink`.
- Ensured email template content includes reset token + email in URL params.

### 3. 📧 Email Delivery via Mailtrap
- Configured `.env` for mail using `MAIL_MAILER=smtp` (fixed `UnsupportedSchemeException`).
- Tested password reset email — successfully received email with reset link.

### 4. 🛠 Database Migration
- Added `password_resets` table via migration to store reset tokens.

### 5. 🌐 Frontend Web Setup Planning
- Evaluated React Native Web (Expo) for serving reset link screen.
- Proposed two strategies:
  - React Native Expo + Web using deep linking.
  - Separate React app using React Router.

### 6. 🧪 Testing & Debugging
- Verified error handling for invalid users.
- Handled `500 Internal Server Error` and JSON parse errors.
- Confirmed Mailtrap logs and reset email rendering.

---

## 🔄 To-Do (Upcoming)
- Implement frontend screen for resetting password (`PasswordResetScreen`).
- Handle token/email submission to `POST /reset-password`.
- Optionally: Build a standalone React web client for full email compatibility.
