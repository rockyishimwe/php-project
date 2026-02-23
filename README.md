# Enterprise OS - PHP Project

## 🚀 Setup Instructions

### 1. Requirements
- PHP 8.0+ with PDO and PDO_SQLite extension
- A web server (Apache/Nginx) or PHP's built-in server

### 2. Database Setup
Run this file once to create the SQLite database:
```
http://localhost/your-path/setup_sqlite.php
```
Or via CLI:
```bash
php setup_sqlite.php
```

### 3. Login Credentials

| User    | Email                     | Password    | Role          |
|---------|---------------------------|-------------|---------------|
| Admin   | admin@enterprise.os       | Admin123!   | System Admin  |
| Marius  | marius@enterprise.os      | Marius123!  | CFO           |
| Forever | forever@enterprise.os     | Forever123! | Lead Dev      |
| Albert  | albert@enterprise.os      | Albert123!  | R&D Director  |

### 4. File Structure
```
├── index.php            → Entry point (redirects to pages/home.php)
├── dashboard.php        → Main app dashboard (role-based)
├── contact.php          → Contact page
├── features.php         → Features showcase
├── testimonials.php     → Testimonials
├── demo.php             → Demo page
├── setup_sqlite.php     → Run once to create DB
├── includes/
│   ├── config.php       → App configuration
│   ├── database.php     → Database class
│   ├── auth.php         → Authentication functions
│   ├── functions.php    → Utility functions
│   ├── security.php     → Security (CSRF, sanitization)
│   ├── session_handler.php → DB-backed sessions
│   ├── header.php       → Shared navbar/header
│   └── footer.php       → Shared footer
├── pages/
│   ├── home.php         → Landing page
│   ├── login.php        → Login page
│   └── register.php     → Registration page
├── assets/
│   ├── css/styles.css   → Global styles
│   └── js/main.js       → Global scripts
└── data/
    └── database.sqlite  → SQLite DB (auto-created)
```

## 🐛 Bugs Fixed in This Version
1. **CSS syntax error** in `dashboard.php` — stray `}` that broke admin styles
2. **`isAdmin()` bug** — was comparing to PHP `true` but SQLite returns int 1/0
3. **Missing DB columns** — `sessions` was missing `payload`, `users` was missing `email`
4. **Broken nav links** — public pages linked login to `?page=login` (wrong context)
5. **Root `home.php` wrong paths** — was using `../includes/` instead of `includes/`
6. **Contact form never submitted** — JS `e.preventDefault()` + `window.location.reload()` 
7. **No mobile menu** — added hamburger button + slide-out nav on all pages
8. **Contact form names** — input fields had no `name` attributes, so POST was empty

## 🔒 Security Features
- CSRF token protection on all forms
- Password hashing with Argon2ID
- Input sanitization
- Session timeout (30 min)
- Rate limiting
- Security headers (X-Frame-Options, CSP, etc.)
