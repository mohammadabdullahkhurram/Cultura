-- ===== USERS & ROLES =====
CREATE TABLE users (
  user_id     BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(256) NOT NULL,
  email       VARCHAR(256) NOT NULL UNIQUE,
  password    VARCHAR(256) NOT NULL,
  status      ENUM('active','suspended','deactivated') NOT NULL DEFAULT 'active',
  created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE students (
  user_id BIGINT NOT NULL PRIMARY KEY,
  major VARCHAR(256),
  class_year SMALLINT,
  CONSTRAINT fk_students_user FOREIGN KEY (user_id)
    REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE residential_staff (
  user_id BIGINT NOT NULL PRIMARY KEY,
  college ENUM('College 3','Mercator','Krupp','Nord') NOT NULL,
  CONSTRAINT fk_staff_user FOREIGN KEY (user_id)
    REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE admins (
  user_id BIGINT NOT NULL PRIMARY KEY,
  CONSTRAINT fk_admins_user FOREIGN KEY (user_id)
    REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE roles (
  role_id     INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  role_name   VARCHAR(256) NOT NULL UNIQUE,
  description VARCHAR(256)
);

CREATE TABLE user_roles (
  user_id    BIGINT NOT NULL,
  role_id    INT NOT NULL,
  assigned_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id, role_id),
  CONSTRAINT fk_user_roles_user FOREIGN KEY (user_id)
    REFERENCES users(user_id) ON DELETE CASCADE,
  CONSTRAINT fk_user_roles_role FOREIGN KEY (role_id)
    REFERENCES roles(role_id) ON DELETE CASCADE
);

-- ===== POSTS =====
CREATE TABLE post_types (
  type_id INT AUTO_INCREMENT PRIMARY KEY,
  name    VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE posts (
  post_id    BIGINT AUTO_INCREMENT PRIMARY KEY,
  creator_id BIGINT NOT NULL,
  type_id    INT NOT NULL,
  title      VARCHAR(200) NOT NULL,
  content    TEXT NOT NULL,
  country    VARCHAR(200),
  theme      VARCHAR(200),
  attachments_url VARCHAR(500),
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP 
                 ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_post_creator FOREIGN KEY (creator_id)
    REFERENCES users(user_id)
      ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_post_type FOREIGN KEY (type_id)
    REFERENCES post_types(type_id)
      ON DELETE RESTRICT ON UPDATE CASCADE
);

-- ===== EVENTS =====
CREATE TABLE Event (
    event_id        INT AUTO_INCREMENT PRIMARY KEY,
    manager_user_id BIGINT,
    name            VARCHAR(255) NOT NULL,
    description     TEXT,
    event_poster_url VARCHAR(500),
    location        VARCHAR(255),
    start_time      DATETIME,
    end_time        DATETIME,
    capacity        INT CHECK (capacity >= 0),
    is_published    BOOLEAN DEFAULT FALSE,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    datetime         DATETIME,
    CONSTRAINT fk_event_manager FOREIGN KEY (manager_user_id)
      REFERENCES users(user_id) ON DELETE SET NULL
);

CREATE TABLE Category (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE EventCategory (
    event_id    INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (event_id, category_id),
    FOREIGN KEY (event_id) REFERENCES Event(event_id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES Category(category_id) ON DELETE CASCADE
);

CREATE TABLE Workshop (
    event_id INT PRIMARY KEY,
    topic    VARCHAR(255),
    duration INT,
    FOREIGN KEY (event_id) REFERENCES Event(event_id) ON DELETE CASCADE
);

CREATE TABLE SocialEvent (
    event_id   INT PRIMARY KEY,
    dress_code VARCHAR(100),
    FOREIGN KEY (event_id) REFERENCES Event(event_id) ON DELETE CASCADE
);

-- ===== RSVP =====
CREATE TABLE rsvp_status (
  status_id   TINYINT UNSIGNED PRIMARY KEY,
  code        VARCHAR(32) NOT NULL UNIQUE,
  is_confirmed BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE rsvp (
  rsvp_id       BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  event_id      INT NOT NULL,
  user_id       BIGINT NOT NULL,
  status_id     TINYINT UNSIGNED NOT NULL,
  email_confirmed BOOLEAN NOT NULL DEFAULT FALSE,
  created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT uq_rsvp_event_user UNIQUE (event_id, user_id),
  CONSTRAINT fk_rsvp_event FOREIGN KEY (event_id)
    REFERENCES Event(event_id) ON DELETE CASCADE,
  CONSTRAINT fk_rsvp_user FOREIGN KEY (user_id)
    REFERENCES users(user_id) ON DELETE CASCADE,
  CONSTRAINT fk_rsvp_status FOREIGN KEY (status_id)
    REFERENCES rsvp_status(status_id)
);
