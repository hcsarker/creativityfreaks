CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  avatar VARCHAR(255) DEFAULT 'default.png',
  role ENUM('student','instructor','admin') DEFAULT 'student',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE courses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  category VARCHAR(100) NOT NULL,
  thumbnail VARCHAR(255) DEFAULT 'default-course.jpg',
  price DECIMAL(10,2) DEFAULT 0.00,
  instructor_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (instructor_id) REFERENCES users(id) ON DELETE CASCADE
);

ALTER TABLE courses ADD COLUMN subcategory VARCHAR(100);
ALTER TABLE courses
ADD COLUMN featured TINYINT(1) NOT NULL DEFAULT 0 AFTER price;


INSERT INTO courses (title, description, category, price, thumbnail, instructor_id)
VALUES
('Web Development Basics', 'Learn HTML, CSS, and JavaScript from scratch.', 'Web Development', 0, 'webdev.jpg', 1),
('Advanced PHP', 'Deep dive into PHP and backend development.', 'Web Development', 49.99, 'php.jpg', 1),
('Graphic Design Fundamentals', 'Learn the basics of graphic design and tools.', 'Design', 0, 'design.jpg', 2),
('UI/UX Mastery', 'Master user experience and interface design.', 'Design', 79.99, 'uiux.jpg', 2),
('Python for Beginners', 'Start coding in Python from scratch.', 'Programming', 0, 'python.jpg', 1);


INSERT INTO courses (title, description, category, subcategory, price, thumbnail, instructor_id)
VALUES 
('Basic Web Development', 'Learn HTML, CSS, JS', 'Skill', 'Web Development', 0, 'web.jpg', 2 ),
('Medical Admission Test', 'Full preparation for MBBS', 'Admission', 'Medical', 20, 'medical.jpg' , 1),
('Class 9 Math', 'Complete math guide for Class 9', 'Academic', 'Class 6-10', 0, 'math9.jpg' , 3), 
('Class 10 Math', 'Complete math guide for Class 10', 'Academic', 'Class 6-10', 0, 'math10.jpg' , 3), 
('Class 11 Math', 'Complete math guide for Class 11', 'Academic', 'Class 11-12', 0, 'math11.jpg' , 3), 
('Class 12 Math', 'Complete math guide for Class 12', 'Academic', 'Class 11-12', 0, 'math12.jpg' , 3); 

INSERT INTO courses (title, description, category, subcategory, thumbnail, price, instructor_id) VALUES
('Mathematics for Class 10', 'Comprehensive math course covering algebra, geometry, and more for Class 10 students.', 'Academic', 'Class 6-10', 'math-class10.jpg', 49.99, 1),

('Physics for 11th Grade', 'In-depth physics lessons focusing on mechanics and thermodynamics.', 'Academic', 'Class 11-12', 'physics-11th.jpg', 59.99, 2),

('Introduction to Programming', 'Learn basic programming concepts using Python.', 'Skill', 'Programming', 'intro-programming.jpg', 39.99, 3),

('Medical Entrance Exam Prep', 'Preparation course for medical entrance exams including MCQs and mock tests.', 'Admission', 'Medical', 'medical-prep.jpg', 79.99, 1),

('English Conversation Skills', 'Improve your spoken English for everyday conversations.', 'Language', 'English', 'english-conversation.jpg', 29.99, 2),

('Engineering Admission Guidance', 'Step-by-step guide for engineering entrance exams and admission process.', 'Admission', 'Engineering', 'engineering-admission.jpg', 69.99, 3),

('Creative Writing Workshop', 'Enhance your writing skills with practical exercises and feedback.', 'Skill', 'Writing', 'creative-writing.jpg', 34.99, 1),

('Advanced French', 'Master advanced French grammar, vocabulary, and conversation.', 'Language', 'French', 'advanced-french.jpg', 44.99, 2);

INSERT INTO courses (title, description, category, subcategory, thumbnail, price, instructor_id)
VALUES 
('Complete Class 10 Science', 'Master Physics, Chemistry & Biology for Class 10 board exams with real-world examples and visuals.', 'Academic', 'Class 10', 'science10.jpg', 0.00, 2),

('Medical Admission Prep 2025', 'Get fully prepared for the upcoming medical admission test with expert-led video lessons and mock exams.', 'Admission', 'Medical', 'medical2025.jpg', 199.99, 3),

('Spoken English Mastery', 'Learn to speak fluent English confidently with practical dialogues, grammar tips, and pronunciation hacks.', 'Language', 'Spoken English', 'spoken_english.jpg', 49.99, 4),

('Web Development Bootcamp', 'A full-stack web development course covering HTML, CSS, JavaScript, PHP, and MySQL from scratch.', 'Skill', 'Programming', 'web_bootcamp.jpg', 89.99, 2),

('HSC Physics Crash Course', 'Fast-track your Physics preparation for HSC with short lectures, key concepts, and problem solving.', 'Academic', 'Class 11-12', 'hsc_physics.jpg', 0.00, 5),

('IELTS Preparation Full Course', 'Ace your IELTS test with strategies, model answers, and section-wise practice materials.', 'Admission', 'IELTS', 'ielts.jpg', 99.99, 3),

('Graphic Design for Beginners', 'Learn Adobe Photoshop, Illustrator, and Canva basics to kickstart your design career.', 'Skill', 'Design', 'graphic_design.jpg', 59.00, 4);


CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    subject VARCHAR(255),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

vumw yirn ysbm xgxn

-- Step 1: Posts
CREATE TABLE community_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT,
    image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Step 2: Comments
CREATE TABLE community_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    comment TEXT,
    image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES community_posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Step 3: Likes
CREATE TABLE community_likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('post', 'comment') NOT NULL,
    target_id INT NOT NULL,
    action ENUM('like', 'dislike') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

SELECT p.id, p.title, p.content, p.image, p.created_at, 
         u.name AS user_name, u.avatar 
  FROM community_posts p
  JOIN users u ON p.user_id = u.id
  ORDER BY p.created_at DESC

  
  CREATE TABLE comment_likes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  comment_id INT NOT NULL,
  user_id INT NOT NULL,
  action ENUM('like', 'dislike') NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(comment_id, user_id)
);

CREATE TABLE comment_replies (
  id INT AUTO_INCREMENT PRIMARY KEY,
  comment_id INT NOT NULL,
  user_id INT NOT NULL,
  reply TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (comment_id) REFERENCES community_comments(id),
  FOREIGN KEY (user_id) REFERENCES users(id)
);


-- CREATE TABLE stats (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     stat_value VARCHAR(50) NOT NULL,
--     stat_label VARCHAR(255) NOT NULL,
--     sort_order INT DEFAULT 0,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
-- );

-- Insert sample data
-- INSERT INTO stats (stat_value, stat_label, sort_order) VALUES
-- ('50+', 'Creative Courses', 1),
-- ('10K+', 'Active Learners', 2),
-- ('100+', 'Expert Instructors', 3),
-- ('95%', 'Satisfaction Rate', 4);



-- CREATE TABLE student_courses (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     student_id INT NOT NULL,
--     course_id INT NOT NULL,
--     enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     last_accessed TIMESTAMP,
--     progress INT DEFAULT 0,
--     FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
--     FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
--     UNIQUE KEY (student_id, course_id)
-- );

CREATE TABLE reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  course_id INT NOT NULL,
  user_id INT NOT NULL,
  rating INT CHECK (rating BETWEEN 1 AND 5),
  review TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE enrollments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  course_id INT NOT NULL,
  enrolled_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY unique_enrollment (user_id, course_id)
);

CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('course','community','message','system') NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    related_id INT COMMENT 'ID of related item (course, post, etc)',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX (user_id, is_read, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO notifications (user_id, type, message, is_read, related_id) VALUES
(1, 'course', 'New course "Advanced JavaScript" is now available!', FALSE, 101),
(2, 'community', 'Someone commented on your post in the community.', FALSE, 205),
(3, 'message', 'You have received a new private message from John.', FALSE, 0),
(4, 'system', 'Your password was changed successfully.', TRUE, NULL),
(5, 'course', 'Your enrollment in "Python Basics" was confirmed.', FALSE, 102),
(6, 'community', 'Your post was liked by 5 users.', TRUE, 207),
(7, 'message', 'Reminder: Your scheduled tutoring session is tomorrow.', FALSE, 0);


-- Add a batches table if it doesn't exist
CREATE TABLE IF NOT EXISTS batches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    category VARCHAR(100) NOT NULL,
    start_date DATE NOT NULL,
    total_seats INT NOT NULL,
    image_url VARCHAR(255),
    original_price DECIMAL(10,2) NOT NULL,
    discounted_price DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Modify your existing enrollments table to include payment info
ALTER TABLE enrollments 
ADD COLUMN payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
ADD COLUMN transaction_id VARCHAR(255),
ADD COLUMN amount_paid DECIMAL(10,2),
ADD COLUMN batch_id INT,
ADD FOREIGN KEY (batch_id) REFERENCES batches(id);

ALTER TABLE enrollments
ADD COLUMN payment_method VARCHAR(50),
ADD COLUMN payment_details TEXT,
ADD COLUMN payment_verified BOOLEAN DEFAULT FALSE;

-- Insert sample batches for Exam Preparation category
INSERT INTO batches (title, description, category, start_date, total_seats, image_url, original_price, discounted_price) 
VALUES 
('College Admission', 'Comprehensive preparation for all stages of College examination', 'Exam Preparation', '2023-06-15', 60, 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60', 12000.00, 8999.00),
('SSC Exam', 'Crash course for engineering aspirants targeting top ranks', 'Exam Preparation', '2023-05-25', 50, 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60', 12000.00, 8999.00),
('BCS Examination', 'Strategies and practice for business school admission test', 'Exam Preparation', '2023-06-01', 40, 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60', 12000.00, 8999.00),
('Bank Exam', 'Complete preparation for medical entrance examination', 'Exam Preparation', '2023-06-05', 30, 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60', 12000.00, 8999.00);

-- Insert sample batches for Admission category
INSERT INTO batches (title, description, category, start_date, total_seats, image_url, original_price, discounted_price) 
VALUES 
('Varsity Admission', 'Comprehensive preparation for all stages of UPSC examination', 'Admission', '2023-06-15', 60, 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f', 12000.00, 8999.00),
('Medical Entrance', 'Crash course for engineering aspirants targeting top ranks', 'Admission', '2023-05-25', 50, 'https://images.unsplash.com/photo-1544947950-fa07a98d237f', 12000.00, 8999.00),
('Engineering Entrance', 'Strategies and practice for engineering school admission test', 'Admission', '2023-06-01', 40, 'https://images.unsplash.com/photo-1503676382389-4809596d5290', 12000.00, 8999.00),
('Science & Technology', 'Complete preparation for medical entrance examination', 'Admission', '2023-06-05', 30, 'https://images.unsplash.com/photo-1523240795612-9a054b0db644', 12000.00, 8999.00);

-- Insert sample batches for HSC Final category
INSERT INTO batches (title, description, category, start_date, total_seats, image_url, original_price, discounted_price) 
VALUES 
('Physics', 'Comprehensive preparation for all stages of HSC examination', 'HSC Final', '2023-06-15', 60, 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60', 12000.00, 8999.00),
('Chemistry', 'Crash course for engineering aspirants targeting top ranks', 'HSC Final', '2023-05-25', 50, 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60', 12000.00, 8999.00),
('Biology', 'Strategies and practice for medical school admission test', 'HSC Final', '2023-06-01', 40, 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60', 12000.00, 8999.00),
('Math', 'Complete preparation for medical entrance examination', 'HSC Final', '2023-06-05', 30, 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60', 12000.00, 8999.00);


 --Add an admin user
INSERT INTO users (name, email, password, avatar, role, created_at)
VALUES (
  'Admin User',
  'admin@creativityfreaks.com',
  '$2y$10$KIXQyYqzBZ/4O9dZhePlYObMP7GLksN76a3f1O/1gLRaUPndzCmGy', -- password: admin123
  'default.png',
  'admin',
  NOW()
);

-- Add an instructor user
INSERT INTO users (name, email, password, avatar, role, created_at)
VALUES (
  'Instructor User',
  'instructor@creativityfreaks.com',
  '$2y$10$KIXQyYqzBZ/4O9dZhePlYObMP7GLksN76a3f1O/1gLRaUPndzCmGy', -- password: admin123
  'default.png',
  'instructor',
  NOW()
);


| Role       | Email                                                                     | Password |
| ---------- | ------------------------------------------------------------------------- | -------- |
| Admin      | [admin@creativityfreaks.com](mailto:admin@creativityfreaks.com)           | admin123 |
| Instructor | [instructor@creativityfreaks.com](mailto:instructor@creativityfreaks.com) | admin123 |

UPDATE users 
SET role = 'instructor' 
WHERE email = 'instructor1@creativityfreaks.com';

UPDATE users 
SET role = 'student' 
WHERE email = 'student2@gmail.com';


CREATE TABLE course_contents (
  id INT AUTO_INCREMENT PRIMARY KEY,
  course_id INT NOT NULL,
  instructor_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  type ENUM('video','pdf','ppt','doc','sheet','link') NOT NULL,
  file_path TEXT,
  video_url TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (course_id) REFERENCES courses(id),
  FOREIGN KEY (instructor_id) REFERENCES users(id)
);

CREATE TABLE student_progress (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  course_id INT NOT NULL,
  content_id INT NOT NULL,
  is_completed TINYINT(1) DEFAULT 0,
  viewed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES users(id),
  FOREIGN KEY (course_id) REFERENCES courses(id),
  FOREIGN KEY (content_id) REFERENCES course_contents(id)
);
