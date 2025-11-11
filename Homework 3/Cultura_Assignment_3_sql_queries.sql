-- SAMPLE DATA
-- This file contains sample data and 12 queries covering Users, Posts, Events and RSVPs

-- clear existing data
DELETE FROM EventCategory;
DELETE FROM Event;
DELETE FROM Category;
DELETE FROM posts;
DELETE FROM post_types;
DELETE FROM rsvp;
DELETE FROM rsvp_status;
DELETE FROM user_roles;
DELETE FROM Workshop;
DELETE FROM SocialEvent;
DELETE FROM admins;
DELETE FROM residential_staff;
DELETE FROM students;
DELETE FROM users;
DELETE FROM roles;

-- reset auto-increment counters
ALTER TABLE Category AUTO_INCREMENT = 1;
ALTER TABLE Event AUTO_INCREMENT = 1;
ALTER TABLE post_types AUTO_INCREMENT = 1;
ALTER TABLE posts AUTO_INCREMENT = 1;
ALTER TABLE rsvp_status AUTO_INCREMENT = 1;
ALTER TABLE rsvp AUTO_INCREMENT = 1;
ALTER TABLE users AUTO_INCREMENT = 1;
ALTER TABLE roles AUTO_INCREMENT = 1;

-- sample data: user and roles
INSERT INTO users (name, email, password, status, created_at) VALUES
('John EventMaster', 'jevent@constructor.university', 'hash1', 'active', '2025-08-01 10:00:00'),
('Sarah Organizer', 'sorganizer@constructor.university', 'hash2', 'active', '2025-08-05 09:10:00'),
('Mike Coordinator', 'mcoordinator@constructor.university', 'hash3', 'active', '2025-08-10 08:45:00'),
('Emma Planner', 'eplanner@constructor.university', 'hash4', 'active', '2025-08-12 14:22:00'),
('Ali Khan', 'akhan@constructor.university', 'hash5', 'active', '2025-09-01 12:05:00'),
('Mina Böttcher', 'mboettcher@constructor.university', 'hash6', 'active', '2025-09-15 18:30:00'),
('David Braun', 'dbraun@constructor.university', 'hash7', 'active', '2025-09-20 07:55:00'),
('Sara Ahmed', 'sahmed@constructor.university', 'hash8', 'active', '2025-09-25 11:11:00');

INSERT INTO roles (role_name, description) VALUES
('Student', 'General student account'),
('ResidentialStaff', 'Residence hall staff'),
('Admin', 'Site administrator');

INSERT INTO students (user_id, major, class_year) VALUES
(5, 'Computer Science', 2026),
(6, 'Electrical Engineering', 2025),
(7, 'Mathematics', 2025),
(8, 'Business Administration', 2025);

INSERT INTO residential_staff (user_id, college) VALUES
(2, 'Mercator'),
(3, 'Krupp'),
(4, 'Nord');

INSERT INTO admins (user_id) VALUES
(1),
(7);

INSERT INTO user_roles (user_id, role_id, assigned_at) VALUES
(1, 2, '2025-09-01 09:00:00'), (1, 3, '2025-09-15 10:00:00'),
(2, 2, '2025-09-02 10:00:00'),
(3, 2, '2025-09-03 11:00:00'),
(4, 2, '2025-09-04 12:00:00'),
(5, 1, '2025-09-05 09:00:00'),
(6, 1, '2025-09-06 10:00:00'),
(7, 1, '2025-09-07 11:00:00'), (7, 3, '2025-09-20 14:00:00'),
(8, 1, '2025-09-08 12:00:00');

-- sample data: event categories
INSERT INTO Category (name) VALUES
('Music Concerts'),
('Sports Events'), 
('Academic Workshops'),
('Cultural Festivals'),
('Art Exhibitions');

INSERT INTO Event (manager_user_id, name, description, location, capacity, is_published, datetime) VALUES
(1, 'Rock Music Festival', 'Annual rock music festival with multiple bands', 'Main Auditorium', 200, TRUE, '2025-11-15 19:00:00'),
(2, 'Basketball Championship', 'Inter-college basketball finals', 'Sports Complex', 150, TRUE, '2025-11-20 18:00:00'),
(1, 'Jazz Night', 'Live jazz performance', 'Music Hall', 80, TRUE, '2025-11-25 20:00:00'),
(3, 'Python Coding Workshop', 'Learn Python programming basics', 'Computer Lab', 30, TRUE, '2025-12-01 14:00:00'),
(2, 'Football Tournament', 'Friendly football matches', 'College Stadium', 100, FALSE, '2025-12-05 16:00:00'),
(4, 'Art Gallery Opening', 'Student art exhibition opening', 'Art Gallery', 60, TRUE, '2025-12-10 17:00:00'),
(1, 'Cultural Food Fair', 'International food tasting event', 'Main Hall', 120, TRUE, '2025-12-15 18:00:00'),
(3, 'Data Science Seminar', 'Advanced data science techniques', 'Lecture Hall', 50, TRUE, '2025-12-20 15:00:00'),
(2, 'Classical Concert', 'Orchestra performance', 'Concert Hall', 120, TRUE, '2025-12-22 19:00:00');

INSERT INTO EventCategory (event_id, category_id) VALUES
(1, 1), (1, 4), (2, 2), (3, 1), (4, 3), (5, 2), (6, 5), (7, 4), (7, 1), (8, 3), (9, 1);

-- sample data for Workshop and SocialEvent tables
INSERT INTO Workshop (event_id, topic, duration) VALUES
(4, 'Python Programming Basics', 120),
(8, 'Data Science Techniques', 180);

INSERT INTO SocialEvent (event_id, dress_code) VALUES
(1, 'Casual'),
(3, 'Smart Casual'),
(7, 'Cultural Attire'),
(9, 'Formal');

-- sample data: posts
INSERT INTO post_types (name) VALUES
('News'), ('Tradition'), ('Food'), ('Activity');

INSERT INTO posts (creator_id, type_id, title, content, country, theme, created_at) VALUES
(5, 2, 'Moroccan late evening tea', 'A post about the famous Moroccan mint tea and its cultural significance.', 'Morocco', 'Traditions', '2025-10-01 10:00:00'),
(6, 3, 'Kimchi Workshop', 'Learn how to prepare authentic Korean kimchi with traditional methods.', 'South Korea', 'Food', '2025-10-02 11:00:00'),
(5, 1, 'Upcoming Cultural Festival', 'Announcing the annual international cultural festival next month.', 'International', 'Campus', '2025-10-03 12:00:00'),
(7, 4, 'Cultural Dance Night', 'Students performing traditional dances from various countries.', 'Egypt', 'Performance', '2025-10-04 13:00:00'),
(6, 4, 'Cultural Quiz Night', 'Test your knowledge about world cultures in our fun quiz event.', 'Mexico', 'Quiz', '2025-10-05 14:00:00'),
(8, 2, 'Japanese Tea Ceremony', 'Experience the beautiful tradition of Japanese tea ceremonies.', 'Japan', 'Traditions', '2025-10-06 15:00:00');

-- sample data: RSVPs
INSERT INTO rsvp_status (status_id, code, is_confirmed) VALUES
(1, 'attending', TRUE),
(2, 'maybe', FALSE),
(3, 'not_attending', FALSE),
(4, 'waitlisted', FALSE);

INSERT INTO rsvp (user_id, event_id, status_id, email_confirmed, created_at) VALUES
(5, 1, 1, TRUE, '2025-10-10 10:00:00'),
(6, 1, 1, TRUE, '2025-10-10 11:00:00'),
(7, 1, 2, FALSE, '2025-10-11 09:00:00'),
(5, 2, 1, TRUE, '2025-10-09 14:00:00'),
(8, 2, 1, TRUE, '2025-10-11 16:00:00'),
(6, 3, 1, TRUE, '2025-10-12 10:00:00'),
(7, 4, 1, TRUE, '2025-10-08 15:00:00'),
(8, 6, 4, FALSE, '2025-10-13 12:00:00'),
(5, 7, 1, TRUE, '2025-10-14 13:00:00'),
(6, 8, 3, FALSE, '2025-10-07 11:00:00');

-- 12 QUERIES:

--USER AND USER ROLES

-- QUERY 1: Users with their assigned roles
SELECT
    u.user_id,
    u.name,
    u.email,
    u.status,
    GROUP_CONCAT(r.role_name ORDER BY ur.assigned_at SEPARATOR ', ') AS roles
FROM users AS u
LEFT JOIN user_roles AS ur ON ur.user_id = u.user_id
LEFT JOIN roles AS r ON r.role_id = ur.role_id
GROUP BY u.user_id, u.name, u.email, u.status
ORDER BY (u.status = 'active') DESC, u.created_at ASC;

-- QUERY 2: Active user count per role (with minimum threshold)
SELECT
    r.role_name,
    COUNT(DISTINCT u.user_id) AS active_user_count
FROM roles AS r
JOIN user_roles AS ur ON ur.role_id = r.role_id
JOIN users AS u ON u.user_id = ur.user_id
WHERE u.status = 'active'
GROUP BY r.role_id, r.role_name
HAVING COUNT(DISTINCT u.user_id) >= 1
ORDER BY active_user_count DESC, r.role_name;

-- QUERY 3: Latest role for each user
WITH ranked AS (
    SELECT
        u.user_id,
        u.name,
        r.role_name,
        ur.assigned_at,
        ROW_NUMBER() OVER (PARTITION BY u.user_id ORDER BY ur.assigned_at DESC) AS rn
    FROM users u
    JOIN user_roles ur ON ur.user_id = u.user_id
    JOIN roles r ON r.role_id = ur.role_id
)
SELECT user_id, name, role_name AS latest_role, assigned_at AS latest_assigned_at
FROM ranked
WHERE rn = 1
ORDER BY latest_assigned_at DESC, name;

--POST AND POST TYPE

-- QUERY 1: All posts with creator and post type information
SELECT
    p.post_id,
    p.title,
    pt.name AS post_type,
    u.name AS creator,
    p.country,
    p.theme,
    p.created_at
FROM posts p
JOIN post_types pt ON p.type_id = pt.type_id
JOIN users u ON p.creator_id = u.user_id
ORDER BY p.created_at ASC;

-- QUERY 2: Post count per post type
SELECT 
    pt.name AS post_type,
    COUNT(p.post_id) AS total_posts
FROM post_types pt
LEFT JOIN posts p ON pt.type_id = p.type_id
GROUP BY pt.name
ORDER BY total_posts DESC;

-- QUERY 3: Posts filtered by specific country
SELECT 
    p.title,
    p.content,
    u.name AS creator,
    pt.name AS post_type,
    p.country,
    p.created_at
FROM posts p
JOIN users u ON p.creator_id = u.user_id
JOIN post_types pt ON p.type_id = pt.type_id
WHERE p.country = 'Morocco'
ORDER BY p.created_at ASC;


-- EVENTS AND EVENT CATEGORIES:

-- QUERY 1: All published events with their categories
SELECT 
    e.event_id,
    e.name AS event_name,
    e.description,
    e.location,
    e.datetime,
    e.capacity,
    GROUP_CONCAT(c.name SEPARATOR ', ') AS categories
FROM Event e
LEFT JOIN EventCategory ec ON e.event_id = ec.event_id
LEFT JOIN Category c ON ec.category_id = c.category_id
WHERE e.is_published = TRUE
GROUP BY e.event_id
ORDER BY e.datetime;

-- QUERY 2: Events filtered by specific category
SELECT 
    e.event_id,
    e.name AS event_name,
    e.description,
    e.location,
    e.datetime,
    e.capacity
FROM Event e
JOIN EventCategory ec ON e.event_id = ec.event_id
JOIN Category c ON ec.category_id = c.category_id
WHERE c.name = 'Music Concerts'
AND e.is_published = TRUE
ORDER BY e.datetime;

-- QUERY 3: Most active event categories
SELECT 
    c.name AS category_name,
    COUNT(DISTINCT e.event_id) AS total_events
FROM Category c
LEFT JOIN EventCategory ec ON c.category_id = ec.category_id
LEFT JOIN Event e ON ec.event_id = e.event_id AND e.is_published = TRUE
GROUP BY c.category_id, c.name
ORDER BY total_events DESC;

--RSVP AND RSVP STATUS

-- QUERY 1: Event RSVP breakdown for specific event
SELECT 
    u.name AS attendee_name,
    rs.code AS rsvp_status,
    CASE WHEN r.email_confirmed = 1 THEN 'Yes' ELSE 'No' END AS email_confirmed,
    r.created_at AS responded_on
FROM rsvp r
JOIN users u ON r.user_id = u.user_id
JOIN rsvp_status rs ON r.status_id = rs.status_id
WHERE r.event_id = 1
ORDER BY rs.is_confirmed DESC, r.created_at ASC;

-- QUERY 2: User's upcoming confirmed events
SELECT 
    e.name AS event_name,
    e.datetime AS event_date,
    e.location,
    r.created_at AS rsvp_date
FROM rsvp r
JOIN event e ON r.event_id = e.event_id
WHERE r.user_id = 5
AND r.status_id = 1
AND e.datetime > NOW()
ORDER BY e.datetime ASC;

-- QUERY 3: Events needing more attendees (low attendance)
SELECT 
    e.event_id,
    e.name AS event_name,
    e.capacity,
    (SELECT COUNT(*) FROM rsvp r WHERE r.event_id = e.event_id AND r.status_id = 1) AS confirmed_attendees,
    (e.capacity - (SELECT COUNT(*) FROM rsvp r WHERE r.event_id = e.event_id AND r.status_id = 1)) AS remaining_spots
FROM event e
WHERE e.is_published = TRUE
AND e.datetime > NOW()
HAVING confirmed_attendees < 10
ORDER BY confirmed_attendees ASC, e.datetime ASC;