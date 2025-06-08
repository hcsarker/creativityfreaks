
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

- **Frontend:** HTML5, CSS3, JavaScript, AJAX
- **Backend:** PHP (Modular and clean code structure)
- **Database:** MySQL
- **Development Environment:** XAMPP / Apache
- **Version Control:** Git
- **Payment Processing:** SSLCommerce (for paid courses)
- **Mailing:** PHPMailer (for email notifications)
- **Others:** AOI, Font Awesome, Bootstrap 5, jQuery
---

## Project Status

The project is partially complete:

- ✅ User authentication and session management
- ✅ Responsive design and frontend implementation
- ✅ Course browsing and filtering
- ✅ Community posting, commenting, and like/dislike system
- ✅ User dashboards with course progress
- ⬜ Instructor panel (under development)
- ⬜ Admin panel (under development)
- ✅ Payment integration for paid courses
- ⬜ Real-time notifications (enhancements planned)
- ✅ Animated modals for login and registration
- ⬜ course content upload and management (in progress)


---

## Installation and Setup

1. **Clone the repository:**

   ```bash
   git clone https://github.com/hcsarker/creativity-freaks.git
   cd creativity-freaks
   ```

2. **Create a MySQL database** (e.g., `creativity_freaks`).

3. **Import the database schema:**

   Use the provided `database/schema.sql` file.

4. **Configure database connection:**

   Edit `config/db.php` and update your database credentials.

5. **Start your local server environment** (e.g., XAMPP).

6. **Open the application** in your browser:

   ```
   http://localhost/creativity-freaks/
   ```

---

## Project Structure

```
creativity-freaks/
├── assets/             # CSS, JavaScript, images, fonts
├── ajax/               # AJAX handlers for community interactions
├── includes/           # Header, footer, layout PHP includes , db connection
├── auth/               # Authentication modals and logic
├── pages/              # Admin panel, instructor panel, community pages (in progress) and other static pages(home, about, contact, courses, etc.)
├── uploads/            # User uploads (course materials, assignments)
├── payment/            # Payment processing and integration
├── index.php           # Landing page
├── README.md           # Project documentation
└── ...
```

---

## Future Enhancements

- Complete instructor and admin dashboards with full CRUD operations.
- Integrate payment gateway for premium courses.
- Real-time notifications and chat system.
- Advanced security features: input validation, file upload restrictions.
- Multi-language support and accessibility improvements.
- Performance optimization and caching.

---

## Contributing

Contributions are welcome! To contribute:

1. Fork the repository.
2. Create a new branch (`git checkout -b feature-name`).
3. Commit your changes (`git commit -m "Add feature"`).
4. Push to your fork (`git push origin feature-name`).
5. Submit a pull request.

Please ensure code quality and add relevant documentation.

---

## License

This project is licensed under the MIT License.

---
            
## Contact

**Hridoy Chandra Sarker**  
Email: hcsarker2002@gmail.com  
GitHub: [https://github.com/hcsarker](https://github.com/hcsarker)
LinkedIn: [https://www.linkedin.com/in/hridoy-chandra-sarker](https://www.linkedin.com/in/hridoy-chandra-sarker)