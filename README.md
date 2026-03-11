# Cultura — Campus Community Platform

Cultura is a full-stack web application built for Constructor University that connects students, residential staff, and administrators through shared events, cultural posts, and community engagement. It features a normalized relational database, a PHP backend with session-based authentication, live autocomplete search, server log analytics, and geolocation support.

---

## Table of Contents

- [Project Overview](#project-overview)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Database Schema](#database-schema)
- [Features](#features)
- [Getting Started](#getting-started)
- [Database Setup](#database-setup)
- [Configuration](#configuration)
- [Pages & Routes](#pages--routes)
- [Authentication](#authentication)
- [Search & Autocomplete](#search--autocomplete)
- [Analytics Pipeline](#analytics-pipeline)
- [Geolocation](#geolocation)
- [Sample Data & Queries](#sample-data--queries)

---

## Project Overview

Cultura was designed to serve the multicultural student community at Constructor University. The platform allows:

- Students and staff to discover and RSVP to campus events
- Community members to share cultural stories, traditions, and posts
- Administrators to manage users, events, posts, categories, and RSVPs through a protected maintenance panel
- Anyone to search across events, posts, and users with live autocomplete

The application was built iteratively — starting from an ER diagram and relational schema, through PHP data-entry forms, search and detail views, authentication, log analytics, and finally autocomplete and geolocation.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Database | MySQL 8+ |
| Backend | PHP 8+ with PDO |
| Frontend | HTML5, CSS3, JavaScript |
| Autocomplete | jQuery 3.6, jQuery UI 1.13 |
| Analytics | Python 3, Pandas, Matplotlib, Seaborn |
| Geolocation | Browser Geolocation API |
| Server | Apache (production) / PHP built-in server (local dev) |

---

## Project Structure

```
Cultura/
├── index.html                  # Homepage / landing page
├── geo.html                    # Geolocation map page
├── imprint.html                # Imprint and disclaimer
├── maintenance.html            # Admin maintenance panel hub
├── style.css                   # Global stylesheet
│
├── cultura.sql                 # Main database schema (DDL)
├── authentication.sql          # Auth users table schema
│
├── db.php                      # Legacy DB connection (mysqli)
├── includes/
│   ├── database.php            # PDO DB connection (used by search pages)
│   ├── header.php              # Shared HTML header and nav
│   └── footer.php              # Shared HTML footer
│
├── — Auth —
├── login.php                   # Admin login form
├── do_login.php                # Login form handler
├── logout.php                  # Session destroy + redirect
├── auth_guard.php              # Middleware — redirects unauthenticated users
├── seedadmin.php               # One-time script to create the first admin account
│
├── — Search & Results —
├── event_search.php            # Event search form (with autocomplete)
├── event_results.php           # Event search results list
├── event_detail.php            # Single event detail view
├── post_search.php             # Post search form (with autocomplete)
├── post_results.php            # Post search results list
├── post_detail.php             # Single post detail view
├── user_search.php             # User search form (with autocomplete)
├── user_results.php            # User search results list
├── user_detail.php             # Single user detail view
├── rsvp_search.php             # RSVP search form
├── rsvp_results.php            # RSVP search results
├── rsvp_detail.php             # Single RSVP detail view
│
├── — Autocomplete Endpoints —
├── autocomplete_events.php     # JSON: event name suggestions
├── autocomplete_posts.php      # JSON: post title suggestions
├── autocomplete_users.php      # JSON: user name suggestions
├── autocomplete_locations.php  # JSON: event location suggestions
│
├── — Admin Data Entry Forms —
├── input_users.php             # Add new user
├── input_event.php             # Add new event
├── input_posts.php             # Add new post
├── input_category.php          # Add new event category
├── input_event_category.php    # Assign category to event
├── input_posttype.php          # Add new post type
├── input_rsvp.php              # Add new RSVP
├── input_rsvpstatus.php        # Add new RSVP status
├── list_users.php              # List all users
├── list_roles.php              # List all roles
├── user_create.php             # User creation handler
├── userrole_new.php            # Assign role to user (form)
├── userrole_create.php         # Assign role to user (handler)
│
├── — Feedback Pages —
├── eventfeedback.php
├── eventcategoryfeedback.php
├── categoryfeedback.php
├── postsfeedback.php
├── posttypefeedback.php
├── rsvpfeedback.php
├── rsvpstatusfeedback.php
│
├── analytics/
│   ├── analyze_logs.py         # Apache log parsing & visualization script
│   ├── requirements.txt        # Python dependencies
│   └── diagrams/               # Generated chart images
│       ├── top_pages.png
│       ├── access_timeline_daily.png
│       ├── access_timeline_hourly.png
│       ├── browser_distribution.png
│       ├── error_timeline_daily.png
│       ├── error_timeline_hourly.png
│       └── error_levels.png
│
└── docs/
    ├── cultura.sql                          # Schema (copy)
    ├── Updated ER Diagram.pdf               # Entity-Relationship diagram
    ├── Mapping Approach.pdf                 # ER-to-relational mapping explanation
    ├── Cultura_Assignment_3_sql_queries.sql # 12 sample queries with data
    ├── Cultura_Assignment_3_sql_outputs.txt # Query output results
    ├── Cultura_Assignment_3_desc.docx       # Query descriptions
    ├── design.pdf                           # UI/UX design document
    └── statistics.pdf                       # Log analytics report
```

---

## Database Schema

The database is defined in `cultura.sql`. It contains 12 tables across four domains:

### Users & Roles

```
users           — base user account (id, name, email, password, status, created_at)
students        — extends users (major, class_year)
residential_staff — extends users (college: College 3 | Mercator | Krupp | Nord)
admins          — extends users (marks admin accounts)
roles           — named roles (Student, ResidentialStaff, Admin)
user_roles      — many-to-many: users ↔ roles (with assigned_at timestamp)
```

Users, students, residential_staff, and admins use a shared-primary-key (specialization) pattern — subtype tables reference `users.user_id` as both PK and FK.

### Events

```
Event           — core event (name, description, location, capacity, is_published, datetime)
Category        — event categories (Music Concerts, Sports, Academic, Cultural, Art)
EventCategory   — many-to-many: events ↔ categories
Workshop        — event subtype (topic, duration)
SocialEvent     — event subtype (dress_code)
```

### Posts

```
post_types      — types: News, Tradition, Food, Activity
posts           — community posts (title, content, country, theme, attachments_url)
```

### RSVPs

```
rsvp_status     — statuses: attending, maybe, not_attending, waitlisted
rsvp            — user RSVP to event (UNIQUE constraint on event_id + user_id prevents duplicates)
```

### Authentication

Defined in `authentication.sql`:

```
auth_users      — admin login accounts (username, password_hash)
```

---

## Features

### Event Discovery
Browse and filter events by name, type (Workshop / Social / General), location, category, and date range. Only published events are shown to regular users. Each event has a detail page showing full description, capacity, categories, and RSVP count.

### RSVP System
Users can RSVP to events with a status of attending, maybe, not attending, or waitlisted. A unique constraint at the database level prevents any user from RSVPing to the same event twice. The RSVP search page lets users find and review their registrations.

### Cultural Post Feed
Community members post cultural stories, traditions, food guides, and news. Posts are categorized by type, tagged with country and theme, and searchable by title, content, type, and creator.

### Live Autocomplete Search
All major search forms have live autocomplete powered by jQuery UI and AJAX. As you type, suggestions are fetched from dedicated PHP endpoints:

- `autocomplete_events.php` — event names
- `autocomplete_posts.php` — post titles
- `autocomplete_users.php` — user names
- `autocomplete_locations.php` — event locations

Autocomplete activates after 2 characters with a 300ms debounce to minimize database load.

### Admin Maintenance Panel
Accessible at `maintenance.html` (protected by login). Admins can:
- Add and manage users, roles, and role assignments
- Create and publish events with categories
- Add post types and posts
- Manage RSVP statuses
- View all users and roles

### Authentication & Access Control
Admin pages are protected by `auth_guard.php` which checks for an active PHP session. Passwords are stored as bcrypt hashes. The login flow uses `login.php` → `do_login.php` → session set → redirect. Logout destroys the session.

### Server Log Analytics
The `analytics/analyze_logs.py` script parses Apache access and error logs using regex, loads them into Pandas DataFrames, and generates Matplotlib/Seaborn charts saved to `analytics/diagrams/`:

- Top pages by access count
- Access timeline (hourly and daily)
- Error timeline (hourly and daily)
- Error severity distribution
- Browser/user-agent distribution

### Geolocation
`geo.html` uses the browser Geolocation API to detect the user's current position and display it on an interactive map, providing location context for nearby campus events.

---

## Getting Started

### Prerequisites

- PHP 8.0 or higher
- MySQL 8.0 or higher
- Python 3.8+ (only needed for log analytics)

### Running Locally

1. Clone or download the project:

```bash
git clone https://github.com/yourusername/cultura.git
cd cultura
```

2. Start the PHP development server from inside the project folder:

```bash
php -S localhost:8000
```

3. Open your browser and go to:

```
http://localhost:8000/index.html
```

> Note: The homepage and static pages (index.html, geo.html, imprint.html) will load without a database. PHP pages that query the database (search, detail, admin forms) require MySQL to be running and configured — see Database Setup below.

---

## Database Setup

1. Log into MySQL:

```bash
mysql -u root -p
```

2. Create the database:

```sql
CREATE DATABASE cultura;
USE cultura;
```

3. Import the schema:

```bash
mysql -u root -p cultura < cultura.sql
```

4. Import the authentication table:

```bash
mysql -u root -p cultura < authentication.sql
```

5. (Optional) Load sample data and run the 12 example queries:

```bash
mysql -u root -p cultura < docs/Cultura_Assignment_3_sql_queries.sql
```

6. Create the first admin account by visiting (only do this once):

```
http://localhost:8000/seedadmin.php
```

---

## Configuration

### PHP Database Connection

There are two DB connection files used by different parts of the app:

**`includes/database.php`** — used by search pages (PDO):

```php
$host = 'localhost';
$dbname = 'cultura';       // Change to your DB name
$username = 'root';        // Change to your MySQL username
$password = '';            // Change to your MySQL password
```

**`db.php`** — used by admin/maintenance pages (also PDO, supports env vars):

```php
$DB_HOST = getenv('DB_HOST') ?: 'localhost';
$DB_NAME = getenv('DB_NAME') ?: 'cultura';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: '';
```

You can either edit the defaults directly, or set environment variables before running the server:

```bash
export DB_NAME=cultura
export DB_USER=root
export DB_PASS=yourpassword
php -S localhost:8000
```

### CSS Path Fix

If you see unstyled pages, open `includes/header.php` and make sure the stylesheet path is:

```html
<link rel="stylesheet" href="/style.css" />
```

---

## Pages & Routes

| URL | Description |
|---|---|
| `/index.html` | Homepage |
| `/geo.html` | Geolocation map |
| `/imprint.html` | Imprint and disclaimer |
| `/maintenance.html` | Admin panel hub |
| `/login.php` | Admin login |
| `/logout.php` | Logout |
| `/event_search.php` | Search events |
| `/event_results.php` | Event search results |
| `/event_detail.php?id=N` | Single event detail |
| `/post_search.php` | Search posts |
| `/post_results.php` | Post search results |
| `/post_detail.php?id=N` | Single post detail |
| `/user_search.php` | Search users |
| `/user_results.php` | User search results |
| `/user_detail.php?id=N` | Single user detail |
| `/rsvp_search.php` | Search RSVPs |
| `/rsvp_results.php` | RSVP results |
| `/rsvp_detail.php?id=N` | RSVP detail |

---

## Authentication

Admin authentication uses PHP sessions and bcrypt password hashing.

**Login flow:**
1. Visit `login.php`
2. Submit username and password
3. `do_login.php` verifies credentials against `auth_users` table using `password_verify()`
4. On success: session is created, user is redirected to maintenance panel
5. On failure: redirected back to `login.php?err=1`

**Protecting pages:**
Any page that requires admin access includes `auth_guard.php` at the top:

```php
require 'auth_guard.php';
```

This redirects unauthenticated visitors to the login page automatically.

**First-time setup:**
Visit `seedadmin.php` once to insert the initial admin account. Delete or disable this file after use.

---

## Search & Autocomplete

Search forms submit via GET to results pages. The query is built dynamically based on which parameters are present — empty fields are ignored so partial searches work.

Example event search query structure:

```sql
SELECT e.event_id, e.name, e.location, e.start_time, e.capacity,
       GROUP_CONCAT(c.name) AS categories
FROM Event e
LEFT JOIN EventCategory ec ON e.event_id = ec.event_id
LEFT JOIN Category c ON ec.category_id = c.category_id
WHERE e.is_published = 1
  AND (:name = '' OR e.name LIKE :name)
  AND (:location = '' OR e.location LIKE :location)
  AND (:date_from = '' OR e.start_time >= :date_from)
GROUP BY e.event_id
ORDER BY e.start_time ASC
```

Autocomplete endpoints return JSON arrays of matching strings. They accept a `term` GET parameter and query the database with a LIKE filter. Example (`autocomplete_events.php`):

```
GET /autocomplete_events.php?term=jazz
→ ["Jazz Night", "Jazz & Blues Evening"]
```

---

## Analytics Pipeline

The log analytics script is in `analytics/analyze_logs.py`.

### Install dependencies

```bash
cd analytics
pip install -r requirements.txt
```

### Run the script

```bash
python analyze_logs.py --access /path/to/access.log --error /path/to/error.log
```

### Output

Charts are saved to `analytics/diagrams/`:

- `top_pages.png` — bar chart of most visited URLs
- `access_timeline_daily.png` — daily traffic over time
- `access_timeline_hourly.png` — hourly traffic patterns
- `browser_distribution.png` — breakdown of user agents
- `error_timeline_daily.png` — daily error counts
- `error_timeline_hourly.png` — hourly error patterns
- `error_levels.png` — error severity distribution (warn, error, crit)

The script uses regex to parse standard Apache Combined Log Format for access logs and Apache error log format for error logs.

---

## Sample Data & Queries

`docs/Cultura_Assignment_3_sql_queries.sql` contains sample data and 12 SQL queries organized across the four main domains:

**Users & Roles**
1. All users with their assigned roles (GROUP_CONCAT)
2. Active user count per role with HAVING threshold
3. Latest role per user using ROW_NUMBER() window function

**Posts**
4. All posts with creator and post type (JOIN)
5. Post count per post type (LEFT JOIN + COUNT)
6. Posts filtered by country (WHERE)

**Events**
7. All published events with categories (GROUP_CONCAT + LEFT JOIN)
8. Events filtered by specific category (JOIN + WHERE)
9. Most active event categories (COUNT + ORDER BY)

**RSVPs**
10. RSVP breakdown for a specific event (JOIN + CASE)
11. User's upcoming confirmed events (WHERE + subquery)
12. Events with low attendance needing promotion (HAVING + correlated subquery)

---

## Notes

- All database queries use PDO prepared statements with parameterized inputs — no raw user input is ever interpolated into SQL strings.
- Passwords in the `auth_users` table are stored as bcrypt hashes using PHP's `password_hash()`.
- The `rsvp` table has a `UNIQUE(event_id, user_id)` constraint enforced at the database level to prevent duplicate bookings.
- The `Event` table has a `CHECK(capacity >= 0)` constraint to prevent negative capacities.
- Cascade rules: deleting a user cascades to their posts, RSVPs, and role assignments. Deleting an event cascades to its RSVPs and category links.