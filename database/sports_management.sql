-- College Sports Management System Database Schema
-- Version 1.0
-- Created: January 2026

-- Drop database if exists and create fresh
DROP DATABASE IF EXISTS sports_management;
CREATE DATABASE sports_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sports_management;

-- ============================================
-- Table: users
-- Stores admin and staff user accounts
-- ============================================
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'staff') DEFAULT 'staff',
    status ENUM('active', 'inactive', 'deleted') DEFAULT 'active',
    gender ENUM('male', 'female', 'other') DEFAULT 'other',
    mobile VARCHAR(15),
    photo VARCHAR(255) DEFAULT 'default-avatar.png',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    INDEX idx_username (username),
    INDEX idx_role (role),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- ============================================
-- Table: sports_categories
-- Stores different sports offered by college
-- ============================================
CREATE TABLE sports_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sport_name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    icon VARCHAR(255),
    image VARCHAR(255),
    category_type ENUM('individual', 'team', 'both') DEFAULT 'team',
    min_players INT DEFAULT 1,
    max_players INT DEFAULT 15,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_sport_name (sport_name),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- ============================================
-- Table: players
-- Stores student/player registration details
-- ============================================
CREATE TABLE players (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    register_number VARCHAR(50) UNIQUE NOT NULL,
    dob DATE NOT NULL,
    age INT NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    blood_group VARCHAR(5),
    department VARCHAR(100) NOT NULL,
    year ENUM('I', 'II', 'III', 'IV') NOT NULL,
    mobile VARCHAR(15) NOT NULL,
    email VARCHAR(100),
    emergency_contact VARCHAR(15),
    address TEXT,
    photo VARCHAR(255) DEFAULT 'default-avatar.png',
    status ENUM('active', 'inactive', 'deleted') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    INDEX idx_register_number (register_number),
    INDEX idx_name (name),
    INDEX idx_department (department),
    INDEX idx_year (year),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- ============================================
-- Table: player_sports
-- Links players to sports (many-to-many)
-- ============================================
CREATE TABLE player_sports (
    id INT PRIMARY KEY AUTO_INCREMENT,
    player_id INT NOT NULL,
    sport_id INT NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    position VARCHAR(50),
    experience_level ENUM('beginner', 'intermediate', 'advanced') DEFAULT 'beginner',
    previous_experience TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (player_id) REFERENCES players(id) ON DELETE CASCADE,
    FOREIGN KEY (sport_id) REFERENCES sports_categories(id) ON DELETE CASCADE,
    UNIQUE KEY unique_player_sport (player_id, sport_id),
    INDEX idx_player (player_id),
    INDEX idx_sport (sport_id)
) ENGINE=InnoDB;

-- ============================================
-- Table: teams
-- Stores team information
-- ============================================
CREATE TABLE teams (
    id INT PRIMARY KEY AUTO_INCREMENT,
    team_name VARCHAR(100) NOT NULL,
    sport_id INT NOT NULL,
    coach_name VARCHAR(100),
    logo VARCHAR(255),
    matches_played INT DEFAULT 0,
    matches_won INT DEFAULT 0,
    status ENUM('active', 'inactive', 'deleted') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (sport_id) REFERENCES sports_categories(id) ON DELETE RESTRICT,
    UNIQUE KEY unique_team_sport (team_name, sport_id),
    INDEX idx_sport (sport_id),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- ============================================
-- Table: team_players
-- Links players to teams (many-to-many)
-- ============================================
CREATE TABLE team_players (
    id INT PRIMARY KEY AUTO_INCREMENT,
    team_id INT NOT NULL,
    player_id INT NOT NULL,
    is_captain BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (team_id) REFERENCES teams(id) ON DELETE CASCADE,
    FOREIGN KEY (player_id) REFERENCES players(id) ON DELETE CASCADE,
    UNIQUE KEY unique_team_player (team_id, player_id),
    INDEX idx_team (team_id),
    INDEX idx_player (player_id)
) ENGINE=InnoDB;

-- ============================================
-- Table: matches
-- Stores match scheduling information
-- ============================================
CREATE TABLE matches (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sport_id INT NOT NULL,
    team1_id INT NOT NULL,
    team2_id INT NOT NULL,
    match_date DATE NOT NULL,
    match_time TIME NOT NULL,
    venue VARCHAR(255) NOT NULL,
    status ENUM('scheduled', 'completed', 'cancelled') DEFAULT 'scheduled',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (sport_id) REFERENCES sports_categories(id) ON DELETE RESTRICT,
    FOREIGN KEY (team1_id) REFERENCES teams(id) ON DELETE RESTRICT,
    FOREIGN KEY (team2_id) REFERENCES teams(id) ON DELETE RESTRICT,
    INDEX idx_match_date (match_date),
    INDEX idx_sport (sport_id),
    INDEX idx_status (status),
    CHECK (team1_id != team2_id)
) ENGINE=InnoDB;

-- ============================================
-- Table: match_results
-- Stores match scores and outcomes
-- ============================================
CREATE TABLE match_results (
    id INT PRIMARY KEY AUTO_INCREMENT,
    match_id INT UNIQUE NOT NULL,
    team1_score INT NOT NULL DEFAULT 0,
    team2_score INT NOT NULL DEFAULT 0,
    winner_team_id INT,
    result_status ENUM('final', 'draw', 'walkover') DEFAULT 'final',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (match_id) REFERENCES matches(id) ON DELETE CASCADE,
    FOREIGN KEY (winner_team_id) REFERENCES teams(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================
-- Table: player_performance
-- Stores individual player statistics per match
-- ============================================
CREATE TABLE player_performance (
    id INT PRIMARY KEY AUTO_INCREMENT,
    match_id INT NOT NULL,
    player_id INT NOT NULL,
    participated BOOLEAN DEFAULT TRUE,
    -- Cricket stats
    runs_scored INT DEFAULT 0,
    balls_faced INT DEFAULT 0,
    wickets INT DEFAULT 0,
    overs DECIMAL(4,1) DEFAULT 0,
    -- Football/Hockey stats
    goals INT DEFAULT 0,
    assists INT DEFAULT 0,
    yellow_cards INT DEFAULT 0,
    red_cards INT DEFAULT 0,
    -- Basketball stats
    points INT DEFAULT 0,
    rebounds INT DEFAULT 0,
    -- General stats
    performance_rating DECIMAL(3,1) DEFAULT 0 COMMENT 'Out of 10',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (match_id) REFERENCES matches(id) ON DELETE CASCADE,
    FOREIGN KEY (player_id) REFERENCES players(id) ON DELETE CASCADE,
    UNIQUE KEY unique_match_player (match_id, player_id),
    INDEX idx_match (match_id),
    INDEX idx_player (player_id)
) ENGINE=InnoDB;

-- ============================================
-- Table: certificates
-- Logs generated certificates
-- ============================================
CREATE TABLE certificates (
    id INT PRIMARY KEY AUTO_INCREMENT,
    player_id INT NOT NULL,
    certificate_type ENUM('Participation', 'Achievement', 'Winner', 'Runner-Up') NOT NULL,
    sport_id INT,
    achievement TEXT,
    issue_date DATE NOT NULL,
    generated_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (player_id) REFERENCES players(id) ON DELETE CASCADE,
    FOREIGN KEY (sport_id) REFERENCES sports_categories(id) ON DELETE SET NULL,
    FOREIGN KEY (generated_by) REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_player (player_id)
) ENGINE=InnoDB;

-- ============================================
-- Table: activity_log
-- Tracks all system activities for audit
-- ============================================
CREATE TABLE activity_log (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action_type ENUM('create', 'update', 'delete', 'login', 'logout') NOT NULL,
    module VARCHAR(50) NOT NULL COMMENT 'e.g., users, players, teams, matches',
    record_id INT COMMENT 'ID of affected record',
    description TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_action_type (action_type),
    INDEX idx_module (module),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB;

-- ============================================
-- Insert Default Admin User
-- Username: admin, Password: admin123
-- ============================================
INSERT INTO users (full_name, username, email, password, role, status) 
VALUES (
    '\\dinesh', 
    'admin', 
    'admin@college.edu',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- Password: password
    'admin', 
    'active'
);

-- ============================================
-- Insert Comprehensive Sport Registry (100+ Disciplines)
-- ============================================
INSERT INTO sports_categories (sport_name, icon, category_type, min_players, max_players, status) VALUES
-- Ball & Team Sports
('Football/Soccer', '⚽', 'team', 11, 18, 'active'),
('Basketball', '🏀', 'team', 5, 12, 'active'),
('Cricket', '🏏', 'team', 11, 15, 'active'),
('Volleyball', '🏐', 'team', 6, 12, 'active'),
('Field Hockey', '🏑', 'team', 11, 16, 'active'),
('American Football', '🏈', 'team', 11, 22, 'active'),
('Rugby Union', '🏉', 'team', 15, 23, 'active'),
('Baseball', '⚾', 'team', 9, 15, 'active'),
('Softball', '🥎', 'team', 9, 15, 'active'),
('Handball', '🤾', 'team', 7, 14, 'active'),
('Water Polo', '🤽', 'team', 7, 13, 'active'),
('Beach Volleyball', '🏐', 'team', 2, 4, 'active'),
('Futsal', '⚽', 'team', 5, 12, 'active'),
('Netball', '🏀', 'team', 7, 12, 'active'),
('Gaelic Football', '🏐', 'team', 15, 20, 'active'),
('Hurling', '🏏', 'team', 15, 20, 'active'),
('Lacrosse', '🥍', 'team', 10, 15, 'active'),
('Australian Rules Football', '🏉', 'team', 18, 22, 'active'),
('Sepak Takraw', '🏐', 'team', 3, 5, 'active'),
('Ultimate Frisbee', '💿', 'team', 7, 12, 'active'),

-- Racket & String Sports
('Badminton', '🏸', 'both', 1, 4, 'active'),
('Tennis', '🎾', 'both', 1, 4, 'active'),
('Table Tennis', '🏓', 'both', 1, 4, 'active'),
('Squash', '🎾', 'individual', 1, 2, 'active'),
('Pickleball', '🏓', 'both', 1, 4, 'active'),
('Padel', '🎾', 'both', 1, 4, 'active'),
('Racquetball', '🎾', 'individual', 1, 2, 'active'),

-- Combat & Martial Arts
('Boxing', '🥊', 'individual', 1, 1, 'active'),
('Wrestling', '🤼', 'individual', 1, 1, 'active'),
('Karate', '🥋', 'individual', 1, 1, 'active'),
('Judo', '🥋', 'individual', 1, 1, 'active'),
('Taekwondo', '🥋', 'individual', 1, 1, 'active'),
('Fencing', '🤺', 'individual', 1, 1, 'active'),
('Mixed Martial Arts (MMA)', '🥋', 'individual', 1, 1, 'active'),
('Muay Thai', '🥋', 'individual', 1, 1, 'active'),
('Kickboxing', '🥊', 'individual', 1, 1, 'active'),
('Sumo Wrestling', '🥋', 'individual', 1, 1, 'active'),
('Kung Fu', '🥋', 'individual', 1, 1, 'active'),
('Brazilian Jiu-Jitsu', '🥋', 'individual', 1, 1, 'active'),
('Kendo', '🤺', 'individual', 1, 1, 'active'),
('Kabaddi', '🏃', 'team', 7, 12, 'active'),
('Sambo', '🥋', 'individual', 1, 1, 'active'),

-- Athletics, Track & Field
('Athletics (General)', '🏃', 'individual', 1, 50, 'active'),
('100m Sprint', '🏃‍♂️', 'individual', 1, 10, 'active'),
('200m Sprint', '🏃‍♀️', 'individual', 1, 10, 'active'),
('400m Dash', '🏃‍♂️', 'individual', 1, 10, 'active'),
('800m Run', '🏃‍♀️', 'individual', 1, 10, 'active'),
('1500m Run', '🏃‍♂️', 'individual', 1, 10, 'active'),
('Marathon', '🏃‍♀️', 'individual', 1, 10, 'active'),
('Relay Race 4x100m', '🏃‍♂️', 'team', 4, 6, 'active'),
('Relay Race 4x400m', '🏃‍♀️', 'team', 4, 6, 'active'),
('Hurdles', '🏃‍♂️', 'individual', 1, 10, 'active'),
('High Jump', '🏃‍♀️', 'individual', 1, 1, 'active'),
('Long Jump', '🏃‍♂️', 'individual', 1, 1, 'active'),
('Triple Jump', '🏃‍♀️', 'individual', 1, 1, 'active'),
('Pole Vault', '🏃‍♂️', 'individual', 1, 1, 'active'),
('Shot Put', '☄️', 'individual', 1, 1, 'active'),
('Discus Throw', '💿', 'individual', 1, 1, 'active'),
('Javelin Throw', '🔱', 'individual', 1, 1, 'active'),
('Hammer Throw', '☄️', 'individual', 1, 1, 'active'),
('Race Walking', '🏃', 'individual', 1, 10, 'active'),
('Cross Country', '🏃', 'individual', 1, 20, 'active'),

-- Water & Nautical Sports
('Swimming (Freestyle)', '🏊', 'individual', 1, 1, 'active'),
('Swimming (Backstroke)', '🏊‍♂️', 'individual', 1, 1, 'active'),
('Swimming (Breaststroke)', '🏊‍♀️', 'individual', 1, 1, 'active'),
('Swimming (Butterfly)', '🏊‍♂️', 'individual', 1, 1, 'active'),
('Diving', '🤿', 'individual', 1, 1, 'active'),
('Synchronized Swimming', '🏊‍♀️', 'team', 2, 8, 'active'),
('Rowing', '🚣', 'both', 1, 8, 'active'),
('Canoeing', '🚣‍♂️', 'individual', 1, 2, 'active'),
('Kayaking', '🚣‍♀️', 'individual', 1, 2, 'active'),
('Sailing', '⛵', 'both', 1, 5, 'active'),
('Surfing', '🏄', 'individual', 1, 1, 'active'),
('Windsurfing', '🏄‍♂️', 'individual', 1, 1, 'active'),
('Kitesurfing', '🏄‍♀️', 'individual', 1, 1, 'active'),
('Jet Skiing', '🚤', 'individual', 1, 1, 'active'),
('Dragon Boat Racing', '🚣', 'team', 10, 22, 'active'),

-- Indoor, Mind & Precision Sports
('Chess', '♟️', 'individual', 1, 1, 'active'),
('Carrom', '⭕', 'both', 2, 4, 'active'),
('Billiards', '🎱', 'individual', 1, 2, 'active'),
('Snooker', '🎱', 'individual', 1, 2, 'active'),
('Bowling', '🎳', 'individual', 1, 1, 'active'),
('Darts', '🎯', 'individual', 1, 1, 'active'),
('Archery', '🏹', 'individual', 1, 1, 'active'),
('Shooting (Air Rifle)', '🎯', 'individual', 1, 1, 'active'),
('Shooting (Skeet)', '🎯', 'individual', 1, 1, 'active'),
('Golf', '⛳', 'individual', 1, 1, 'active'),
('Bridge', '🃏', 'team', 4, 4, 'active'),
('Poker', '🃏', 'individual', 1, 10, 'active'),
('E-Sports (League of Legends)', '🎮', 'team', 5, 6, 'active'),
('E-Sports (Counter-Strike)', '🎮', 'team', 5, 6, 'active'),
('E-Sports (FIFA)', '🎮', 'individual', 1, 2, 'active'),

-- Gymnastics, Dance & Fitness
('Artistic Gymnastics', '🤸', 'individual', 1, 1, 'active'),
('Rhythmic Gymnastics', '🤸‍♀️', 'individual', 1, 1, 'active'),
('Trampolining', '🤸‍♂️', 'individual', 1, 1, 'active'),
('Aerobics', '🧘', 'individual', 1, 10, 'active'),
('Yoga', '🧘‍♂️', 'individual', 1, 20, 'active'),
('Pilates', '🧘‍♀️', 'individual', 1, 10, 'active'),
('Dance Sport (Ballroom)', '💃', 'team', 2, 2, 'active'),
('Breakdancing', '💃', 'individual', 1, 1, 'active'),
('Cheerleading', '📣', 'team', 6, 20, 'active'),
('Bodybuilding', '💪', 'individual', 1, 1, 'active'),
('Powerlifting', '💪', 'individual', 1, 1, 'active'),
('CrossFit', '💪', 'individual', 1, 1, 'active'),

-- Winter & Extreme Sports
('Ice Hockey', '🏒', 'team', 6, 20, 'active'),
('Figure Skating', '⛸️', 'individual', 1, 2, 'active'),
('Speed Skating', '⛸️', 'individual', 1, 1, 'active'),
('Skiing (Alpine)', '⛷️', 'individual', 1, 1, 'active'),
('Skiing (Cross-Country)', '⛷️', 'individual', 1, 1, 'active'),
('Snowboarding', '🏂', 'individual', 1, 1, 'active'),
('Curling', '🥌', 'team', 4, 4, 'active'),
('Bobsleigh', '🛷', 'team', 2, 4, 'active'),
('Cycling (Road)', '🚲', 'individual', 1, 10, 'active'),
('Cycling (Mountain)', '🚵', 'individual', 1, 1, 'active'),
('Cycling (Track)', '🚲', 'individual', 1, 1, 'active'),
('Skateboarding', '🛹', 'individual', 1, 1, 'active'),
('BMX', '🚲', 'individual', 1, 1, 'active'),
('Rock Climbing', '🧗', 'individual', 1, 10, 'active'),
('Mountaineering', '🧗‍♀️', 'individual', 1, 10, 'active'),
('Paragliding', '🪂', 'individual', 1, 1, 'active'),
('Skydiving', '🪂', 'individual', 1, 1, 'active'),

-- Others
('Motorsport (Formula 1)', '🏎️', 'individual', 1, 1, 'active'),
('Motorsport (MotoGP)', '🏍️', 'individual', 1, 1, 'active'),
('Horse Racing (Equestrian)', '🏇', 'individual', 1, 1, 'active'),
('Polo', '🐎', 'team', 4, 8, 'active'),
('Tug of War', '💪', 'team', 8, 10, 'active'),
('Bocce', '🏐', 'both', 1, 4, 'active'),
('Billiards (Casual)', '🎱', 'individual', 1, 2, 'active');

-- ============================================
-- Database Schema Complete
-- ============================================
