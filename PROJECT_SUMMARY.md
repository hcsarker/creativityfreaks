# Creativity Freaks — Project Summary

A modern e‑learning platform connecting students, instructors, and admins. Core capabilities include course browsing/enrollment (free & paid), a social community for posts/comments/likes, role‑based dashboards, in‑app notifications, email, and payment processing.

## Overview

- Purpose: Seamless online learning with interactive community and instructor tools.
- Roles: Student, Instructor, Admin with session-backed access control.
- Core Flows: Auth → Browse → Enroll → Learn → Interact → Notify → Pay.

## Features

- Auth & Roles: Secure login/register; protected routes per role.
- Courses: List, filter, details, enrollment; instructor content upload.
- Community: Posts, threaded comments, likes via AJAX; personal posts view.
- Dashboards: Student progress; instructor course management; admin analytics.
- Notifications: Unread counts; dropdown UI; mark-as-read endpoint.
- Payments: SSLCommerz success/fail/cancel; payment pages under `payment/`.
- Email: PHPMailer for transactional mail.
- UI/UX: Responsive layout, dark/light mode, animated auth modals.

## Architecture

- `includes/`: Environment (`env.php`), session/CSRF init (`init.php`), DB (`db.php`), layout (`header.php`, `footer.php`, `layout.php`), notifications API (`notifications.php`, `mark_read.php`).
- `pages/`: Route-like PHP views (home, courses, community, instructor/admin dashboards, payment status pages).
- `assets/`: CSS/JS modules (community, dashboard, notifications).
- `ajax/`: Lightweight endpoints (e.g., `load_courses.php`).
- `payment/`: Handlers for SSLCommerz (`success.php`, `fail.php`, `cancel.php`).
- `uploads/`: User content storage (`avatars/`, `community/`, `course_content/`, `thumbnail/`).
- Entry: `index.php` bootstraps layout/pages.

## Tech Stack

- Frontend: HTML5, CSS3, Vanilla JS (fetch/AJAX).
- Backend: PHP (procedural with includes).
- Database: MySQL with `mysqli` prepared statements.
- Infra: Apache (LAMPP/XAMPP), SSLCommerz, PHPMailer.

## Setup (Local)

1. Place repo under web root: `/opt/lampp/htdocs/creativityfreaks`.
2. Configure environment: `.env` loaded by `includes/env.php` (DB creds: `CF_DB_HOST`, `CF_DB_NAME`, `CF_DB_USER`, `CF_DB_PASS`).
3. Create DB:
   ```sql
   CREATE DATABASE creativity_freaks CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
4. Import schema via phpMyAdmin/CLI.
5. Visit: `http://localhost/creativityfreaks/`.

## Security

- Implemented: Secure sessions (HttpOnly, SameSite, Secure), CSRF tokens, prepared statements, output escaping, safer DB error logging.
- Recommended: Global CSRF coverage, upload validation & non-executable storage, pagination, login rate limiting, centralized logging.

## Notable Paths

- Community: `pages/community/*`, `assets/js/community.js`.
- Instructor: `pages/instructor/*` (course creation, uploads, notifications).
- Admin: `pages/admin/*` (analytics/visuals).
- Payments: `pages/sslcz_pay.php`, `includes/sslcommerz_ipn.php`, `payment/*`.

## Roadmap (Highlights)

- Complete instructor/admin CRUD + analytics.
- Harden uploads and input validation.
- Optional real-time notifications (WebSockets/SSE).
- Localization (Bangla/English) and accessibility.

## Contact

- Author: Hridoy Chandra Sarker
- Email: hcsarker2002@gmail.com
- GitHub: https://github.com/hcsarker
- LinkedIn: https://www.linkedin.com/in/hridoy-chandra-sarker
