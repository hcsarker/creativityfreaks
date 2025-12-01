# Creativity Freaks

Creativity Freaks is a modern, fully responsive, and feature-rich e-learning platform designed to connect students, instructors, and administrators in an interactive online learning environment. The platform supports both free and paid courses, a vibrant community system, and role-based user management.

---

## Table of Contents

- [Project Overview](#project-overview)
- [Features](#features)
- [Technologies Used](#technologies-used)
- [Project Status](#project-status)
- [Installation and Setup](#installation-and-setup)
- [Project Structure](#project-structure)
- [Future Enhancements](#future-enhancements)
- [Contributing](#contributing)
- [License](#license)
- [Contact](#contact)

---

## Project Overview

Creativity Freaks aims to provide a seamless and engaging platform for learners and instructors. It includes:

- Secure authentication with role-based access (Student, Instructor, Admin).
- Course browsing and filtering by categories and subcategories.
- Interactive community system where users can post questions, comment, and like/dislike posts.
- Personalized dashboards showing enrolled courses and learning progress.
- Support for both free and paid courses.
- Real-time notifications for user interactions.
- Dark/light UI mode toggle for better user experience.

---

## Features

- **User Authentication & Roles:** Secure login and registration with session management, supporting Student, Instructor, and Admin roles.
- **Responsive UI:** Mobile-friendly, adaptive layouts with smooth animations.
- **Course Management:** View and filter courses by category and subcategory.
- **Community Interaction:** Post questions, comment with text and images, and like/dislike both posts and comments using AJAX.
- **User Dashboards:** Show enrolled courses, progress bars, and personalized info.
- **Notification System:** Users receive notifications for likes and comments on their posts.
- **Dark/Light Mode:** Toggle between light and dark themes.
- **Animated Login/Register Modals:** Smooth modal animations for authentication forms.
- **Modular PHP Architecture:** Reusable components with includes for header, footer, and layout.

---

## Technologies Used

# Creativity Freaks

A modern, responsive e‑learning platform connecting students, instructors, and admins with courses, community, and payments — built with PHP, MySQL, and vanilla JS.

— Beautiful UX, pragmatic PHP, and a clean modular structure.

## Contents

- Overview
- Features
- Tech Stack
- Architecture
- Getting Started
- Environment & Config
- Database Setup
- Notifications
- Security & Hardening
- Project Structure
- Roadmap
- Contributing
- License

## Overview

Creativity Freaks provides:

- Role‑based access (Student, Instructor, Admin)
- Course browsing and enrollment
- Community posts, comments, and likes
- Instructor tools and dashboards
- Payments via SSLCommerz
- Email via PHPMailer
- In‑app notifications

## Features

- Auth & Roles: Secure login/register, session-backed roles.
- Courses: Browse, details view, enrollment flows.
- Community: Posts, threaded comments, likes, AJAX interactions.
- Dashboards: Personalized student/instructor panels.
- Notifications: Bell menu with recent items and unread counts.
- Payments: SSLCommerz success/fail/cancel flows.
- Email: Transactional emails via PHPMailer.
- Responsive UI: Mobile-first styling and animations.

## Tech Stack

- Frontend: HTML5, CSS3, JavaScript (fetch/AJAX)
- Backend: PHP (procedural with includes)
- Database: MySQL (mysqli prepared statements)
- Infra: Apache (XAMPP/LAMPP)
- Payments: SSLCommerz
- Mail: PHPMailer

## Architecture

- `includes/` contains common building blocks (DB, layout/header/footer, auth guard, notifications API).
- `pages/` holds route-like views (and nested areas: `admin/`, `instructor/`, `community/`).
- `assets/` hosts CSS/JS, with JS modules calling backend endpoints.
- `ajax/` and some `includes/` files serve as lightweight JSON endpoints.

## Getting Started

1. Clone

```bash
git clone https://github.com/hcsarker/creativityfreaks.git
cd creativityfreaks
```

2. Place in web root

- Linux (LAMPP): `/opt/lampp/htdocs/creativityfreaks`
- Windows (XAMPP): `C:\xampp\htdocs\creativityfreaks`

3. Env config

```bash
cp .env.example .env
# edit .env to match your local DB
```

4. Create database

```sql
CREATE DATABASE creativity_freaks CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

5. Import schema

- If you have an SQL dump, import it via phpMyAdmin or CLI.

6. Visit the app

```
http://localhost/creativityfreaks/
```

## Environment & Config

- `.env` is loaded by `includes/env.php` (no external deps).
- DB vars used by `includes/db.php` with safe defaults:
  - `CF_DB_HOST`, `CF_DB_NAME`, `CF_DB_USER`, `CF_DB_PASS`
- Example file: `.env.example`

## Database Setup

- Uses MySQL with `mysqli` prepared statements.
- Ensure proper indexes on users, notifications, posts, comments.
- Character set: `utf8mb4` (emoji safe).

## Notifications

- API: `includes/notifications.php` returns JSON list + unread count.
- Mark read: `includes/mark_read.php` (POST) marks individual items read.
- Frontend: `assets/js/notifications.js` renders a dropdown and updates counts.
- Behavior: An item is marked read when clicked (not on open) for accuracy.

## Security & Hardening

Implemented in this repo:

- Secure sessions: `includes/init.php` sets `HttpOnly`, `SameSite=Lax`, and `Secure` (when HTTPS).
- CSRF protection: Token generation + verification helpers in `includes/init.php`.
  - Tokens added to forms (login/register) and AJAX requests (mark read).
- Safer DB errors: `includes/db.php` logs connection errors without exposing details.
- Output escaping: `htmlspecialchars()` used on dynamic content in templates.

Recommended next steps:

- Add pagination on long lists (e.g., instructor notifications).
- Cover all POST endpoints with CSRF checks.
- Uploads hardening: validate mime/extension, randomized names, restrict execution.
- Basic rate limiting for login to slow brute‑force attempts.
- Centralized error logging/monitoring (e.g., file logs).

## Project Structure

```
.
├── ajax/
├── assets/
│   ├── css/
│   └── js/
├── auth/
├── includes/
│   ├── env.php
│   ├── init.php
│   ├── db.php
│   ├── header.php
│   ├── footer.php
│   ├── layout.php
│   ├── notifications.php
│   └── mark_read.php
├── pages/
│   ├── community/
│   └── instructor/
├── payment/
├── uploads/
└── index.php
```

## Roadmap

- Instructor/admin dashboards: complete CRUD and analytics.
- Global CSRF coverage and validation layer.
- Uploads scanning & serving via non-executable locations.
- Real-time enhancements: optional websockets or SSE for notifications.
- Localization (Bangla/English) and accessibility.

## Contributing

PRs welcome! Please:

- Write clear commits and keep changes focused.
- Escape output and use prepared statements.
- Add docs for new endpoints or UI.

## License

MIT

           
## Contact
We welcome feedback, feature requests, and contributions from the community. Join us in shaping the future of online learning with Creativity Freaks!

**Hridoy Chandra Sarker**  
Email: hcsarker2002@gmail.com  
GitHub: [https://github.com/hcsarker](https://github.com/hcsarker)
LinkedIn: [https://www.linkedin.com/in/hridoy-chandra-sarker](https://www.linkedin.com/in/hridoy-chandra-sarker)
—

Bangla (Quick Note): এই প্রোজেক্টটি একটি ফুল-স্ট্যাক ই-লার্নিং প্ল্যাটফর্ম। লোকাল সেটআপ করতে `.env` কনফিগার করুন, ডাটাবেস তৈরি করে ইমপোর্ট দিন, তারপর `http://localhost/creativityfreaks/` ভিজিট করুন। সিকিউরিটি হিসেবে সেশন/CSRF যুক্ত করা হয়েছে; বাকি POST এন্ডপয়েন্টগুলোতেও ধাপে ধাপে কভার করা হবে।
