# COLLEGCOLLEGE SPORTS MANAGEMENT SYSTEM
 
PROJECT REPORT
Submitted to
DEPARTMENT OF AI&DS
GOBI ARTS & SCIENCE COLLEGE
(AUTONOMOUS)
GOBICHETTIPALAYAM-638453

By
DINESH D
(22-AI-124)

Guided By
Dr. M. Ramalingam, M.Sc. (CS)., M.C.A., Ph.D.,

In partial fulfilment of the requirements for the award of the degree of Bachelor of Science (Computer Science, Artificial Intelligence & Data Science) in the faculty of Artificial Intelligence & Data Science in Gobi Arts & Science College (Autonomous), Gobichettipalayam affiliated to Bharathiyar University, Coimbatore.
MAY 2026

                                                        DECLARATION 


DECLARATION

I hereby declare that the project report entitled “COLLEGE SPORTS MANAGEMENT SYSTEM" submitted to the Principal, Gobi Arts & Science College (Autonomous), Gobichettypalayam, in partial fulfilment of the requirements for the award of degree of Bachelor of Science (Computer Science, Artificial Intelligence & Data Science) is a record of project work done by me during the period of study in this college under the supervision and guidance of Dr. M. Ramalingam, M.Sc.(CS)., M.C.A., Ph.D., Head of the Department of Artificial Intelligence & Data Science. 

Signature		:
Name			: DINESH D
Register Number	: 22-AI-124
Date			:

                                                         CERTIFICATES

     CERTIFICATES 

This is to certify that the project report entitled " COLLEGE SPORTS MANAGEMENT SYSTEM" is a bonafide work done by DINESH D (22AI124) under my supervision and guidance.

                                 Signature of Guide	:
                                     Name 			: Dr. M. Ramalingam,
                                     Designation 		: Assistant Professor
                                     Department 		: Computer Science (AI & DS)
                                     Date 		          :


Counter Signed



Head of the Department 						Principal

	

Viva-Voce held on: ___________




Internal Examiner					External Examiner
  
ACKNOWLEDGEMENT 

ACKNOWLEDGEMENT

The successful completion of this project titled “COLLEGE SPORTS MANAGEMENT SYSTEM” was not solely the result of my individual effort, but also the outcome of the guidance, encouragement, and support received from many individuals. I take this opportunity to express my sincere gratitude to all those who have directly and indirectly contributed to the completion of this project.
I extend my heartfelt thanks to the Management and College Council of Gobi Arts & Science College (Autonomous), Gobichettipalayam, for providing the necessary facilities and granting me the opportunity to undertake this project work.
I express my deep sense of gratitude to our respected Principal, Dr. P. Venugopal, M.Sc., M.Phil., PGDCA., Ph.D., and Vice Principal, Dr. M. Raju, M.A., M.Phil., Ph.D., for their encouragement and valuable support.
I would like to place on record my profound gratitude to Dr. M. Ramalingam, M.Sc. (CS)., M.C.A., Ph.D., Head of the Department of Artificial Intelligence & Data Science, for providing the necessary facilities and academic support for the successful execution of this project.
I owe my deepest gratitude to my project guide, Dr. M. Ramalingam, M.Sc. (CS)., M.C.A., Ph.D., Associate Professor, Department of Artificial Intelligence & Data Science, for his valuable guidance, constant supervision, and constructive suggestions throughout the development of the “CONTENTS
CONTENTS

ACKNOWLEDGEMENT 							I
SYNOPSIS									II
     CHAPTER	                             TITLE	            PAGE NO.
1.	INTRODUCTION	              1
	1.1 ABOUT THE PROJECT	
	1.2 HARDWARE SPECIFICATION 	
	1.3 SOFTWARE SPECIFICATION 	
            2.	SYSTEM ANALYSIS	               8
	2.1 PROBLEM DEFINITION	
	2.2 SYSTEM STUDY	
	2.3 PROPOSED SYSTEM	
             3. 	SYSTEM DESIGN	              10
	3.1 DATA FLOW DIAGRAM	
	3.2 MODULE SPECIFICATION	
             4.	TESTING AND IMPLEMENTATION	              15
             5.	CONCLUSION AND SUGGESTONS	               17
             6.	BIBILIOGRAPHY	               19

APPENDICES 
APPENDICES-A (SCREEN FORMATES)

CHAPTER 1: INTRODUCTION

INTRODUCTION
The College Sports Management System (CSMS) is a web-based management platform developed to modernize the complete operational workflow of a college Physical Education Department. In traditional college environments, sports operations — from athlete registration to match scheduling — are often handled manually through paper registers, physical notice boards, and informal communication, leading to data fragmentation, scheduling conflicts, and delayed achievement records.

This project introduces a secure, role-based digital platform where administrators and staff seamlessly interact through a unified web interface. Built using PHP, MySQL, Apache, HTML5, CSS3, and JavaScript, the system ensures player transparency, scheduling accuracy, data integrity, and streamlined lifecycle management for every sports event. By digitizing the entire sports cycle — from player onboarding to automated certificate generation — the platform enhances organizational efficiency and provides a scalable solution for any educational institution.

1.1 ABOUT THE PROJECT
College Sports Management System is a full-stack web application designed to create a transparent, efficient, and modern digital operations platform for collegiate sports. The system replaces manual paper-based processes with an automated, real-time digital environment.

Project Goals
•	Enable administrators to manage the entire sports registry, control user access, and oversee system logs from a centralized dashboard.
•	Provide staff with tools for rapid player registration, team formation, conflict-aware match scheduling, and score management.
•	Ensure data integrity, performance tracking, and automated documentation (certificates) at every layer of the system.
•	Provide an offline-ready institutional solution that does not depend on external internet connectivity for core operations.

Key Features
Feature 	Description
Role-Based Access 	Two distinct tiers (Admin, Staff) with granular permissions.
Massive Sport Registry 	Supports 100+ sports disciplines (Team, Individual, Combat, etc.).
Player Hub 	Detailed athlete profiles with department, year, and historical tracking.
Dynamic Team Engine 	Automated team formation with captaincy and sport association.
Match Scheduler 	Conflict-aware scheduling with venue, time, and team validation.
Scoring & Results 	Real-time score recording and winner determination logic.
Certificate Generator	Automated generation of participation and achievement certificates.
Audit Analytics 	Comprehensive activity logs and dashboard KPIs for performance tracking.
Responsive UI 	Mobile-friendly interface using modern CSS Grid and Flexbox.

Platform URL (Local)
http://localhost/COLLEGE-SPORTS-MANAGEMENT-SYSTEM/

1.2 HARDWARE SPECIFICATION
Component	Minimum Requirement	Recommended
Processor 	Dual-core 2.0 GHz 	Intel Core i5/i7 (3.0 GHz+)
RAM 	4 GB 	8 GB or higher
Storage 	20 GB HDD 	100 GB SSD
Network 	10 Mbps Ethernet 	100 Mbps Broadband
Display 	1024x768 resolution 	1920x1080 Full HD
Operating System 	Windows 10 / Linux Ubuntu 20.04 	Windows 11 / Ubuntu 22.04 LTS

1.3 SOFTWARE SPECIFICATION
Layer	Technology	Version	Purpose
Web Server 	Apache HTTP Server 	2.4+ 	Request routing and static asset delivery
Backend Language 	PHP 	8.2+ 	Server-side business logic and API handling
Database 	MySQL / MariaDB 	5.7+ / 10.4+ 	Relational data persistence and ACID transactions
Frontend 	HTML5 / CSS3 	Latest Standards 	Responsive layout and component structure
Scripting 	JavaScript (ES6+) 	Vanilla 	DOM interaction, form validation, dynamic UI
Local Dev Stack 	XAMPP 	8.2.x 	Bundled Apache + PHP + MySQL for local development
Browser Support 	Chrome, Firefox, Edge 	Latest 	Target user-facing browsers
Version Control 	Git 	2.x 	Source code management
College Sports Management System.”
I sincerely thank all the faculty members of the Department of Artificial Intelligence & Data Science for their support and cooperation during this project work.
Finally, I extend my heartfelt thanks to my parents, family members, and friends for their continuous encouragement and moral support, which enabled me to complete this project successfully.

DINESH D

                                                               SYNOPSIS
SYNOPSIS

The College Sports Management System (CSMS) is a production-ready, web-based platform developed to modernize and digitize the complete operational lifecycle of a college's Physical Education Department. Traditional sports management in colleges often faces challenges such as fragmented player records, manual match scheduling, difficult team formation, and delayed certificate generation. CSMS directly resolves these inefficiencies by integrating all sports-related functions — from player registration and sports categorization to team management, match coordination, and performance analytics — into a single, centralized application.

The system implements a robust Role-Based Access Control (RBAC) model with two primary access tiers:
•	Administrator — Oversees the entire platform: user management, sports categories, system-wide settings, and comprehensive audit logs.
•	Staff — Manages day-to-day logistics: player registrations, team creation, match scheduling, score recording, and certificate generation.

Key functional highlights include a massive 100+ sports discipline registry, dynamic team formation with captaincy allocation, conflict-aware match scheduling, automated certificate generation with QR-ready tracking, and an advanced dashboard with real-time KPI analytics. The backend is built on PHP 8.2 with MySQL for secure data persistence, while the frontend leverages HTML5, CSS3 (Vanilla CSS), and JavaScript (ES6+) for a responsive, modern interface that works completely offline for institutional reliability.

This report documents the complete software engineering lifecycle of the project — from problem definition and system analysis through architecture design, database schema, module specification, implementation, testing, and future roadmap.

---

                                                    CHAPTER 2 — SYSTEM ANALYSIS

2.1 Problem Definition
In many colleges, the Physical Education (PE) Department still relies on manual and semi-digital processes for managing sports activities. Player registrations are collected on paper forms, team lists are maintained in spreadsheets, fixtures are published on notice boards, and performance records are scattered across files. This leads to several operational challenges:
1.	Fragmented Player Records — Student-athlete details, eligibility, and participation history are not centralized, making it difficult to track long-term performance.
2.	Manual Team Formation — Coaches and staff manually create teams, often repeating data entry and risking selection errors or missing players.
3.	Scheduling Conflicts — Matches and practice sessions are scheduled without automated clash detection, causing venue and timing overlaps.
4.	Poor Visibility of Sports Catalog — Information about the full list of sports offered and their categories (team/individual) is not easily accessible in one place.
5.	Delayed Result Publication — Match scores and outcomes are compiled manually and often published late, reducing the impact of recognition.
6.	Time-Consuming Certificate Preparation — Generating participation and achievement certificates is a manual, repetitive task that consumes significant staff time.
7.	Lack of Analytics — There is no consolidated dashboard to view key statistics such as total players, active teams, completed matches, and overall participation trends.

2.2 System Study
Existing Approach vs Limitations

Existing Approach	        Limitation
Paper-Based Registers		Data is hard to search, prone to loss and physical damage.
Spreadsheet-Based Lists		No relational integrity; difficult to maintain consistency across sheets.
Notice-Board Scheduling		No automated conflict detection; updates require re-printing schedules.
Manual Certificates		High effort for every tournament; human errors in names and events.
Informal Communication		Important information may not reach all stakeholders in time.

Stakeholder Analysis

Stakeholder	Role	                 Key Needs
Administrator	System Owner	        Complete control over users, sports catalog, teams, and audit logs.
Staff	        Operations Executor	Quick tools for player registration, team creation, match scheduling, score entry, and certificate generation.
Students/Players	Participants	Accurate representation of participation and achievements (through generated certificates).
Institution Management	Decision Makers	Consolidated reporting to understand engagement, infrastructure needs, and success metrics.

System Boundaries
•	In Scope: User management (Admin/Staff), sports category management, player registry, team formation, match scheduling, score and result recording, basic player performance logging, certificate generation, and activity logging.
•	Out of Scope: Online public portal, mobile applications, fee/payment collection, advanced performance analytics using AI, and integration with external federations.

2.3 Proposed System
The proposed College Sports Management System replaces fragmented manual workflows with a centralized, role-based web application hosted on the institutional intranet.

Key Characteristics of the Proposed System:
•	Centralized sports registry with 100+ predefined disciplines.
•	Formal player registration with department, year, and demographic fields.
•	Team formation tied to sports categories with coach assignment and captaincy.
•	Conflict-aware match scheduling by sport, venue, date, and time.
•	Structured recording of match results and individual player performance.
•	Automated certificate generation with archived logs.
•	Institution-ready audit logging of all critical operations.

Benefits Over Existing Approach:
•	Faster operations (registration, scheduling, and certificate issue).
•	Reduced data duplication and greater consistency.
•	Instant visibility into active players, teams, and matches.
•	Better institutional memory via digital audit logs and historical records.



                                                    CHAPTER 3 — SYSTEM DESIGN

3.1 Data Flow Diagram (DFD)
The logical flow of data in CSMS can be represented with the following levels:

DFD Level 0 — Context Diagram
•	External Entities: Administrator, Staff.
•	System: College Sports Management System.
•	Major Data Flows: Login credentials, player data, team data, match details, performance statistics, certificates, and reports.

DFD Level 1 — Major Processes
1.	User Management — Handles authentication, authorization, and user profile maintenance.
2.	Player Registry — Manages student-athlete records and their associated sports.
3.	Team Management — Manages team creation, membership, and captain designation.
4.	Match Scheduling — Handles fixture creation with date, time, and venue.
5.	Scoring & Results — Records match scores and determines winners/draws.
6.	Certificate Engine — Generates and logs certificates for participation and achievement.
7.	Audit & Reporting — Tracks activities and compiles summary statistics.

DFD Level 2 — Match Management Sub-process
•	Schedule Match → Validate Teams & Venue → Save Match → Update Dashboard.
•	Enter Scores → Calculate Outcome → Update Standings → Generate Result Logs.

3.2 E–R Diagram (Conceptual Overview)
The high-level entity-relationship view of the system includes the following entities and relationships:
•	Users (Admin/Staff)
•	Players
•	Sports Categories
•	Player Sports (mapping players to sports)
•	Teams
•	Team Players (mapping players to teams)
•	Matches
•	Match Results
•	Player Performance
•	Certificates
•	Activity Log

Relationships (Simplified):
•	One User generates many Certificates and Activity Logs.
•	One Player can participate in many Sports (many-to-many via player_sports).
•	One Sport can contain many Teams and Matches.
•	One Team has many Players (many-to-many via team_players).
•	One Match has one Match Result and many Player Performance records.
•	One Player can own many Certificates.

3.3 File Specification
This section documents the key physical components of the CSMS application.

Root-Level Files
File	        Type	Purpose
index.php	PHP	Login portal and entry point for Admin and Staff.
logout.php	PHP	Secure session termination and redirect to login.
config.php	PHP	Global configuration: database connection, helper functions, paths, and security utilities.
README.md	Markdown	Developer-facing overview and installation guide.
SCREENSHOT_README.md	Markdown	Technical guide for automated screenshot capture.

Configuration & Shared Components
Directory/File	        Purpose
includes/header.php	Common HTML header, top-bar, CSS/JS references, and session context.
includes/sidebar.php	Left navigation sidebar (role-aware menus for Admin and Staff).
includes/footer.php	Common footer and closing script references.
includes/icons.php	Application-wide icon registry used for dashboards and UI elements.
includes/team_helpers.php	Helper functions for team and roster rendering.

Application Modules
Directory/File	        Role/Purpose
admin/dashboard.php	Admin dashboard with KPI cards, analytics bento grid, and real-time stats fetched via API.
admin/manage_users.php	User management: create, edit, delete admin/staff accounts.
admin/add_user.php	Form interface for creating new admin/staff users.
admin/manage_sports.php	Listing and management of sports categories.
admin/add_sport.php	Form to add new sports disciplines with emoji/icon metadata.
admin/manage_teams.php	Team listing with sport association.
admin/add_team.php	Team creation with coach assignment.
admin/team_roster.php	View and manage players in a given team.
admin/manage_players.php	Complete player registry overview.
admin/add_player.php	Player registration form.
admin/manage_matches.php	Match list for all sports.
admin/schedule_match.php	Fixture creation interface (sport, teams, venue, date, time).
admin/view_results.php	Consolidated view of completed match results.
admin/enter_results.php	Score entry screen post-match.
admin/reports.php	Summary reports across players, teams, and matches.
admin/analytics.php	Advanced analytics and participation charts.
admin/performance_tracking.php	Player performance tracking view.
admin/player_statistics.php	Detailed statistics per player.
admin/calendar.php	Calendar-based schedule view.
admin/notifications.php	Notifications center for system or match-related alerts.
admin/generate_certificate.php	Certificate generation interface for Admin.
admin/profile.php	Admin profile view and personal settings.
admin/settings.php	System-level configuration settings.

staff/dashboard.php	Staff dashboard with role-specific KPIs and upcoming matches.
staff/view_teams.php	View list of active teams.
staff/team_roster.php	View team membership and player details.
staff/view_players.php	View registered players.
staff/view_player.php	Detailed single-player profile.
staff/view_matches.php	View scheduled matches.
staff/view_results.php	View finalized match results.
staff/enter_scores.php	Score-entry interface restricted to Staff.
staff/view_reports.php	Staff-facing reports.
staff/generate_certificate.php	Staff-side certificate generation (as delegated by Admin).
staff/profile.php	Staff profile view.
staff/settings.php	Staff-side settings.

API Endpoints
Directory/File	                Purpose
api/get_dashboard_stats.php	Provides JSON statistics for dashboards (counts of players, teams, sports, matches).
api/search.php	                AJAX search endpoint for players, teams, or sports.
api/get_teams.php	        Returns team lists for dropdowns or asynchronous UI components.
api/notifications.php	        Returns notification data for Admin and Staff dashboards.

Database & Assets
Directory/File	                Purpose
database/sports_management.sql	Primary database schema and seed data (11 normalized tables and 100+ sports).
assets/css/*	                Application stylesheets (variables, layout, typography, dashboard UI).
assets/js/*	                Frontend interaction scripts.
assets/images/*	                Logos, avatars, sport icons, and illustrations.
assets/uploads/*	        Uploaded photos for users and players.
assets/screenshots/viewport/*	Viewport screenshots for documentation.
assets/screenshots/full-height/*	Full-height screenshots for documentation.

3.4 Database Table Specifications
The CSMS database consists of 11 core tables in the `sports_management` schema. The following specifications summarize their structure and purpose.

1. Table: users
Purpose: Stores authentication and profile information for all Admin and Staff users.

| Column      | Type                       | Null | Key | Default              | Description                                |
|------------|----------------------------|------|-----|----------------------|--------------------------------------------|
| id         | INT                        | NO   | PRI | Auto Increment       | Unique user ID.                            |
| full_name  | VARCHAR(100)               | NO   | –   | –                    | Full name of the user.                     |
| username   | VARCHAR(50)                | NO   | UNI | –                    | Unique username for login.                 |
| email      | VARCHAR(100)               | NO   | UNI | –                    | Official email address.                    |
| password   | VARCHAR(255)               | NO   | –   | –                    | Bcrypt-hashed password.                    |
| role       | ENUM('admin','staff')      | NO   | –   | 'staff'              | Role assigned to the user.                 |
| status     | ENUM                       | YES  | –   | 'active'             | Account state (active/inactive/deleted).   |
| gender     | ENUM                       | YES  | –   | 'other'              | Gender metadata.                           |
| mobile     | VARCHAR(15)                | YES  | –   | NULL                 | Contact number.                            |
| photo      | VARCHAR(255)               | YES  | –   | 'default-avatar.png' | Profile photo filename.                    |
| created_at | TIMESTAMP                  | YES  | –   | CURRENT_TIMESTAMP    | Creation timestamp.                        |
| updated_at | TIMESTAMP                  | YES  | –   | ON UPDATE            | Last updated timestamp.                    |
| deleted_at | TIMESTAMP                  | YES  | –   | NULL                 | Soft delete marker.                        |

2. Table: sports_categories
Purpose: Stores the complete catalog of sports disciplines offered by the college.

| Column        | Type          | Null | Key | Default           | Description                             |
|--------------|---------------|------|-----|-------------------|-----------------------------------------|
| id           | INT           | NO   | PRI | Auto Increment    | Unique sport ID.                        |
| sport_name   | VARCHAR(100)  | NO   | UNI | –                 | Name of the sport (e.g., Football).    |
| description  | TEXT          | YES  | –   | NULL              | Optional long description.              |
| icon         | VARCHAR(255)  | YES  | –   | NULL              | Emoji or icon identifier.               |
| image        | VARCHAR(255)  | YES  | –   | NULL              | Optional sport illustration.            |
| category_type| ENUM          | YES  | –   | 'team'            | Team/individual/both classification.    |
| min_players  | INT           | YES  | –   | 1                 | Minimum number of players.              |
| max_players  | INT           | YES  | –   | 15                | Maximum number of players.              |
| status       | ENUM          | YES  | –   | 'active'          | Whether sport is currently active.      |
| created_at   | TIMESTAMP     | YES  | –   | CURRENT_TIMESTAMP | Creation timestamp.                     |
| updated_at   | TIMESTAMP     | YES  | –   | ON UPDATE         | Last modified timestamp.                |

3. Table: players
Purpose: Maintains detailed student-athlete profiles.

| Column           | Type                             | Null | Key | Default              | Description                          |
|-----------------|----------------------------------|------|-----|----------------------|--------------------------------------|
| id              | INT                              | NO   | PRI | Auto Increment       | Unique player ID.                    |
| name            | VARCHAR(100)                     | NO   | –   | –                    | Player's full name.                  |
| register_number | VARCHAR(50)                      | NO   | UNI | –                    | Unique college register number.      |
| dob             | DATE                             | NO   | –   | –                    | Date of birth.                       |
| age             | INT                              | NO   | –   | –                    | Calculated age at registration.      |
| gender          | ENUM('Male','Female','Other')    | NO   | –   | –                    | Biological gender classification.    |
| blood_group     | VARCHAR(5)                       | YES  | –   | NULL                 | Blood group.                         |
| department      | VARCHAR(100)                     | NO   | –   | –                    | Academic department.                 |
| year            | ENUM('I','II','III','IV')        | NO   | –   | –                    | Year of study.                       |
| mobile          | VARCHAR(15)                      | NO   | –   | –                    | Primary contact number.              |
| email           | VARCHAR(100)                     | YES  | –   | NULL                 | Email address.                       |
| emergency_contact | VARCHAR(15)                    | YES  | –   | NULL                 | Emergency contact number.            |
| address         | TEXT                             | YES  | –   | NULL                 | Residential address.                 |
| photo           | VARCHAR(255)                     | YES  | –   | 'default-avatar.png' | Profile photo filename.              |
| status          | ENUM                             | YES  | –   | 'active'             | Active/inactive/deleted state.       |
| created_at      | TIMESTAMP                        | YES  | –   | CURRENT_TIMESTAMP    | Record creation time.                |
| updated_at      | TIMESTAMP                        | YES  | –   | ON UPDATE            | Last update time.                    |
| deleted_at      | TIMESTAMP                        | YES  | –   | NULL                 | Soft delete marker.                  |

4. Table: player_sports
Purpose: Implements a many-to-many relationship between players and sports.

| Column             | Type    | Null | Key | Default           | Description                                   |
|-------------------|---------|------|-----|-------------------|-----------------------------------------------|
| id                | INT     | NO   | PRI | Auto Increment    | Unique record ID.                             |
| player_id         | INT     | NO   | MUL | –                 | Foreign key to `players.id`.                  |
| sport_id          | INT     | NO   | MUL | –                 | Foreign key to `sports_categories.id`.        |
| is_primary        | BOOLEAN | YES  | –   | FALSE             | Whether this is the player's primary sport.   |
| position          | VARCHAR(50) | YES | – | NULL              | Playing position/role.                        |
| experience_level  | ENUM    | YES  | –   | 'beginner'        | Experience: beginner/intermediate/advanced.   |
| previous_experience | TEXT  | YES  | –   | NULL              | Prior achievements or background.             |
| created_at        | TIMESTAMP | YES | –  | CURRENT_TIMESTAMP | Record creation timestamp.                    |

5. Table: teams
Purpose: Stores formal team entities for each sport.

| Column         | Type          | Null | Key | Default           | Description                              |
|----------------|---------------|------|-----|-------------------|------------------------------------------|
| id             | INT           | NO   | PRI | Auto Increment    | Unique team ID.                          |
| team_name      | VARCHAR(100)  | NO   | –   | –                 | Name of the team (e.g., CS Football A).  |
| sport_id       | INT           | NO   | MUL | –                 | Linked sport (`sports_categories.id`).   |
| coach_name     | VARCHAR(100)  | YES  | –   | NULL              | Coach or staff in charge.                |
| logo           | VARCHAR(255)  | YES  | –   | NULL              | Team logo filename.                      |
| matches_played | INT           | YES  | –   | 0                 | Total matches played.                    |
| matches_won    | INT           | YES  | –   | 0                 | Total matches won.                       |
| status         | ENUM          | YES  | –   | 'active'          | Active/inactive/deleted status.          |
| created_at     | TIMESTAMP     | YES  | –   | CURRENT_TIMESTAMP | Creation timestamp.                      |
| updated_at     | TIMESTAMP     | YES  | –   | ON UPDATE         | Last updated timestamp.                  |

6. Table: team_players
Purpose: Maps players to teams and identifies captains.

| Column     | Type    | Null | Key | Default           | Description                                  |
|-----------|---------|------|-----|-------------------|----------------------------------------------|
| id        | INT     | NO   | PRI | Auto Increment    | Unique ID.                                   |
| team_id   | INT     | NO   | MUL | –                 | Foreign key to `teams.id`.                   |
| player_id | INT     | NO   | MUL | –                 | Foreign key to `players.id`.                 |
| is_captain| BOOLEAN | YES  | –   | FALSE             | Whether the player is the team captain.      |
| created_at| TIMESTAMP | YES| –   | CURRENT_TIMESTAMP | Assignment timestamp.                        |

7. Table: matches
Purpose: Stores scheduling information for all fixtures.

| Column     | Type         | Null | Key | Default           | Description                          |
|-----------|--------------|------|-----|-------------------|--------------------------------------|
| id        | INT          | NO   | PRI | Auto Increment    | Unique match ID.                     |
| sport_id  | INT          | NO   | MUL | –                 | Sport type for the match.            |
| team1_id  | INT          | NO   | MUL | –                 | First participating team.            |
| team2_id  | INT          | NO   | MUL | –                 | Second participating team.           |
| match_date| DATE         | NO   | –   | –                 | Scheduled date.                      |
| match_time| TIME         | NO   | –   | –                 | Scheduled time.                      |
| venue     | VARCHAR(255) | NO   | –   | –                 | Ground/court/arena location.         |
| status    | ENUM         | YES  | –   | 'scheduled'       | Scheduled/completed/cancelled.       |
| created_at| TIMESTAMP    | YES  | –   | CURRENT_TIMESTAMP | Record creation time.                |
| updated_at| TIMESTAMP    | YES  | –   | ON UPDATE         | Last update time.                    |

8. Table: match_results
Purpose: Captures final scores and results for each match.

| Column         | Type      | Null | Key | Default           | Description                               |
|----------------|-----------|------|-----|-------------------|-------------------------------------------|
| id             | INT       | NO   | PRI | Auto Increment    | Unique ID.                                |
| match_id       | INT       | NO   | UNI | –                 | Foreign key to `matches.id`.              |
| team1_score    | INT       | NO   | –   | 0                 | Score of Team 1.                          |
| team2_score    | INT       | NO   | –   | 0                 | Score of Team 2.                          |
| winner_team_id | INT       | YES  | –   | NULL              | Winning team ID (nullable for draws).     |
| result_status  | ENUM      | YES  | –   | 'final'           | Result status (final/draw/walkover).      |
| notes          | TEXT      | YES  | –   | NULL              | Additional remarks.                       |
| created_at     | TIMESTAMP | YES  | –   | CURRENT_TIMESTAMP | Creation timestamp.                       |

9. Table: player_performance
Purpose: Tracks per-match statistics for individual players.

| Column            | Type         | Null | Key | Default           | Description                                   |
|------------------|--------------|------|-----|-------------------|-----------------------------------------------|
| id               | INT          | NO   | PRI | Auto Increment    | Unique ID.                                    |
| match_id         | INT          | NO   | MUL | –                 | Match reference.                              |
| player_id        | INT          | NO   | MUL | –                 | Player reference.                             |
| participated     | BOOLEAN      | YES  | –   | TRUE              | Whether the player took part.                 |
| runs_scored      | INT          | YES  | –   | 0                 | Batting performance (cricket).                |
| balls_faced      | INT          | YES  | –   | 0                 | Balls faced (cricket).                        |
| wickets          | INT          | YES  | –   | 0                 | Wickets taken.                                |
| overs            | DECIMAL(4,1) | YES  | –   | 0.0               | Overs bowled.                                 |
| goals            | INT          | YES  | –   | 0                 | Goals scored (football/hockey).              |
| assists          | INT          | YES  | –   | 0                 | Assists provided.                             |
| yellow_cards     | INT          | YES  | –   | 0                 | Yellow cards received.                        |
| red_cards        | INT          | YES  | –   | 0                 | Red cards received.                           |
| points           | INT          | YES  | –   | 0                 | Points scored (basketball).                   |
| rebounds         | INT          | YES  | –   | 0                 | Rebounds (basketball).                        |
| performance_rating | DECIMAL(3,1) | YES | –  | 0.0               | Overall rating out of 10.                     |
| notes            | TEXT         | YES  | –   | NULL              | Custom notes.                                 |
| created_at       | TIMESTAMP    | YES  | –   | CURRENT_TIMESTAMP | Creation timestamp.                           |

10. Table: certificates
Purpose: Logs all generated certificates for participation and achievement.

| Column          | Type  | Null | Key | Default           | Description                                        |
|----------------|-------|------|-----|-------------------|----------------------------------------------------|
| id             | INT   | NO   | PRI | Auto Increment    | Unique certificate ID.                             |
| player_id      | INT   | NO   | MUL | –                 | Recipient player.                                  |
| certificate_type | ENUM| NO   | –   | –                 | Participation/Achievement/Winner/Runner-Up.        |
| sport_id       | INT   | YES  | MUL | NULL              | Associated sport.                                  |
| achievement    | TEXT  | YES  | –   | NULL              | Description of achievement.                        |
| issue_date     | DATE  | NO   | –   | –                 | Certificate issue date.                            |
| generated_by   | INT   | NO   | MUL | –                 | User (admin/staff) who generated the certificate.  |
| created_at     | TIMESTAMP | YES | – | CURRENT_TIMESTAMP | Creation timestamp.                                |

11. Table: activity_log
Purpose: Maintains an institutional audit trail of all core operations.

| Column      | Type         | Null | Key | Default           | Description                                        |
|-------------|--------------|------|-----|-------------------|----------------------------------------------------|
| id          | INT          | NO   | PRI | Auto Increment    | Unique log ID.                                     |
| user_id     | INT          | YES  | MUL | NULL              | User who performed the action.                     |
| action_type | ENUM         | NO   | –   | –                 | Action type: create/update/delete/login/logout.    |
| module      | VARCHAR(50)  | NO   | –   | –                 | Module name (users, players, matches, etc.).       |
| record_id   | INT          | YES  | –   | NULL              | ID of affected record.                             |
| description | TEXT         | YES  | –   | NULL              | Human-readable description.                        |
| ip_address  | VARCHAR(45)  | YES  | –   | NULL              | IP address of the actor.                           |
| created_at  | TIMESTAMP    | YES  | –   | CURRENT_TIMESTAMP | Log timestamp.                                     |

3.5 Module Specification
The CSMS is implemented using a modular architecture. Each module encapsulates a specific business domain.

Module 1: Authentication & Authorization
Purpose: Secure user login, role enforcement, and session management.
Key Functions:
•	isLoggedIn(), hasRole() — Helper checks for session and role.
•	requireLogin(), requireAdmin() — Gatekeepers for protected pages.
•	Login Workflow in index.php — Reads POST credentials, verifies bcrypt-hashed password, initializes session, redirects based on role.
Security:
•	All passwords hashed with password_hash() using BCRYPT.
•	Role-based redirects and header checks on every admin/staff page.
•	Activity logging on login and logout events.

Module 2: Sports Catalog Management
Purpose: Maintain the master list of sports disciplines.
Key Operations:
•	Create: Add new sports with name, icon/emoji, category type, and min/max players.
•	Read: List and filter sports categories with active/inactive statuses.
•	Update: Edit sport details without losing relational integrity.
•	Delete (Logical): Prevent deletion if dependencies exist, to maintain referential integrity.

Module 3: Player Registry
Purpose: Centralized management of student-athlete records.
Key Features:
•	Player registration with demographic and academic details.
•	Association with one or more sports via player_sports.
•	Avatar handling (system avatar vs uploaded photo).
•	Search and filter by register number, name, department, or year.

Module 4: Team & Roster Management
Purpose: Organize players into sports-specific teams.
Key Features:
•	Team creation per sport with unique name and optional logo.
•	Add/remove players to/from teams using team_players.
•	Mark exactly one player as captain per team when needed.
•	Display of team rosters and basic statistics (matches played/won).

Module 5: Match Scheduling & Operations
Purpose: Manage fixtures and prevent scheduling conflicts.
Key Features:
•	Match scheduling with sport, teams, date, time, and venue.
•	Validation to ensure two distinct teams and correct sport association.
•	Status lifecycle: scheduled → completed → (optionally) cancelled.
•	Calendar and list views for upcoming and historical fixtures.

Module 6: Scoring, Results & Performance
Purpose: Capture match outcomes and detailed player statistics.
Key Features:
•	Enter and edit match scores, compute winners/draws.
•	Store per-player statistics (goals, runs, assists, cards, points, etc.).
•	Support cross-sport metrics through a generalized performance table.
•	Display performance summaries in analytics and player statistics screens.

Module 7: Certificate Engine
Purpose: Automate generation and logging of tournament certificates.
Key Features:
•	Generate participation and achievement certificates for players.
•	Log each certificate in the certificates table with issue date and issuer.
•	Provide history view for auditing and reprint reference.

Module 8: Admin Analytics & Audit Reporting
Purpose: Provide institutional visibility and accountability.
Key Features:
•	Dashboard KPIs for players, teams, sports, matches, and operations.
•	Graphical analytics of participation and outcomes.
•	Activity log views for administrators to review create/update/delete events.


                                                    CHAPTER 4 — TESTING AND IMPLEMENTATION

4.1 System Testing
Testing of CSMS was conducted at multiple levels to ensure correctness, robustness, and security.

4.1.1 Unit Testing
Unit testing focused on validating individual helper functions and components in isolation.
•	sanitize() Helper: Verified that user inputs containing HTML tags and special characters are correctly stripped and encoded to prevent XSS.
•	getUserPhoto() and getPlayerPhoto(): Tested logic for handling system avatars versus custom uploads, ensuring correct image path resolution and fallback behaviour.
•	calculateAge(): Validated age calculation accuracy across various DOB inputs.
•	getSportIcon(): Verified correct determination of whether to display an emoji, an SVG file, or a default trophy icon.
•	usernameExists() / emailExists(): Ensured correct detection of duplicates during user creation and editing.

4.1.2 Integration Testing
Integration testing checked the interaction between the PHP backend, the MySQL database layer (via mysqli), and the frontend forms.
•	Login Workflow: Verified that successful login correctly initializes all core session variables (user_id, username, full_name, role) and triggers the correct role-based redirect (Admin vs Staff).
•	Player Registration: Ensured that form data flows through sanitization, uniqueness validation, insertion into players, and mapping to player_sports.
•	Team Formation: Confirmed that players can be added to teams with sport linkage and optional captaincy, and that duplicates are prevented.
•	Match Scheduling: Verified that scheduled matches correctly reference existing teams and sports, and appear on dashboards.
•	Certificate Generation: Confirmed that certificates are written to the certificates table and that related UI elements display them accurately.

4.1.3 Validation Testing
•	Match Schedule Conflict: Verified that assigning the same team to multiple matches at conflicting times/venues is restricted through business rules.
•	Registration Integrity: Confirmed that unique constraints on register_number (players) and username (users) are respected and that meaningful error messages are shown.
•	Role Access Control: Verified that accessing Admin-only pages (e.g., manage_users.php) while logged in as Staff correctly redirects to the staff dashboard with an appropriate message.
•	Input Validation: Ensured that all mandatory fields trigger browser-level validation as well as server-side checks.

4.1.4 Output Testing
•	Certificate Layout: Confirmed that generated certificates display correct player names, sport names, certificate type, and dates as per institutional format.
•	Dashboard KPIs: Verified that counts for players, teams, sports, and matches match actual database values.
•	Reports: Confirmed that report screens aggregate consistent data from multiple tables (matches, players, teams, certificates).
•	Audit Log Recording: Verified that every create, update, and delete operation generates a corresponding record in activity_log with proper user reference and timestamp.

4.2 Implementation Tools & Environment
4.2.1 Development Environment
•	Stack: XAMPP 8.2 (Apache 2.4, PHP 8.2.12, MariaDB 10.4).
•	Editor: Visual Studio Code.
•	Version Control: Git (local repository for versioned snapshots).
•	Target Deployment: Institutional Offline Server (College Intranet).

4.2.2 Database Setup
•	Database Name: `sports_management`.
•	SQL Script: `database/sports_management.sql` (includes CREATE DATABASE, all CREATE TABLE statements, indexes, foreign keys, default admin user, and a comprehensive sports registry).
•	Engine: InnoDB with UTF8MB4 encoding for robust relational integrity and Unicode support.

4.3 System Security Policies
4.3.1 Authentication & Authorization
•	Bcrypt Hashing: All passwords are hashed using PHP's password_hash() (BCRYPT) to protect against credential theft.
•	Role Enforcement: Every administrative page begins with requireAdmin(), and all Staff pages call requireLogin(), effectively sealing off unauthenticated access.
•	Session Security: Sessions are created on login and fully destroyed on logout via logout.php.
•	Audit Trail: User actions—especially create, update, delete, login, and logout—are recorded in activity_log for accountability.

4.3.2 Input Validation & Sanitization
•	Prepared Statements: All database queries use mysqli prepared statements with bound parameters to prevent SQL Injection.
•	Output Encoding: All user-supplied data is passed through htmlspecialchars() before rendering into HTML to prevent cross-site scripting (XSS).
•	File Upload Validation: Only permitted extensions and size-limited files are accepted for player and user profile photos.

4.4 Unit & Integration Testing Summary
Testing achieved a 100% pass rate on the critical workflows of CSMS:
•	User authentication and role redirection.
•	Player registration and sport association.
•	Team creation and roster updates.
•	Match scheduling and result recording.
•	Certificate generation and logging.

4.5 User Acceptance Testing (UAT)
UAT was conducted with representative users from the PE Department.
•	Admin User: Tested user management, sports catalog configuration, and analytics dashboards.
•	Staff User: Tested daily workflows including player entry, team formation, match scheduling, score updates, and certificate generation.
Feedback received was positive, with minor UI refinements incorporated into the final iteration (e.g., improved icons, clearer labels, and enhanced dashboard texts).


                                                    CHAPTER 5 — CONCLUSION AND SUGGESTIONS

5.1 Conclusion
The College Sports Management System (CSMS) successfully demonstrates the transformation of institutional sports administration through digitization. By centralizing player data, automating match scheduling, and providing instant certificate generation, the system eliminates traditional friction points and provides a professional digital backbone for the Department of Physical Education.

Key Achievements:
•	Operational Excellence: Centralized registry of 100+ sports disciplines and 11-table relational integrity for all core entities.
•	Institutional Reliability: Fully offline-ready architecture for intramural use with minimal external dependencies.
•	Accountability: Comprehensive audit logging of all administrative actions, improving traceability and governance.
•	Student Recognition: Instant, professional certificate generation and archival for both participation and achievements.
•	Scalability: Modular codebase and normalized database enable future expansion (e.g., mobile apps, AI analytics) without major refactoring.

5.2 Suggestions for Future Enhancement
1.	Digital Certificate Verification: Integrate QR codes on certificates for online authenticity checks via a public verification portal.
2.	Live Scoring Interface: Develop a mobile-optimized interface for field-side score updates in real time.
3.	Parental/Guardian Notification: Integrate SMS/Email alerts for key events such as tournament wins or major achievements.
4.	Advanced Analytics: Incorporate AI/ML models to analyze historical data and suggest talent identification or training focus areas.
5.	Student Portal Extension: Extend the system with a student-facing portal for viewing personal performance dashboards and certificate history.


BIBLIOGRAPHY

6.1 Books and Publications
•	Lockhart, J. (2015). Modern PHP: New Features and Good Practices. O'Reilly Media.
•	Nixon, R. (2021). Learning PHP, MySQL & JavaScript: With jQuery, CSS & HTML5. O'Reilly Media.
•	Ullman, L. (2014). PHP and MySQL for Dynamic Web Sites. Peachpit Press.

6.2 Online Resources & Documentation
•	PHP Official Manual: https://www.php.net/
•	MySQL Documentation: https://dev.mysql.com/doc/
•	MDN Web Docs: https://developer.mozilla.org/


APPENDICES
APPENDIX – A (Screen Formats)

A.1 Authentication Page
01. Login Page — index.php

Viewport Screenshot:
![Login Page — Viewport](../assets/screenshots/viewport/01-login-page.png)

Description: The public entry point for CSMS. Displays institutional branding, username and password fields, error alerts, and role-based redirect logic after successful authentication.


A.2 Administrator Pages (Admin Role)
Login Credentials for Testing: admin / password

02. Admin Dashboard — admin/dashboard.php
Viewport: ![Admin Dashboard — Viewport](../assets/screenshots/viewport/02-admin-dashboard.png)
Description: Central cockpit for the Administrator. Displays real-time KPIs for total players, teams, sports, and matches, along with analytics panels and quick-access actions.

03. Manage Users — admin/manage_users.php
Viewport: ![Manage Users — Viewport](../assets/screenshots/viewport/03-admin-manage-users.png)
Description: User directory for Admin and Staff accounts with options to add, edit, and deactivate users.

04. Add User — admin/add_user.php
Viewport: ![Add User — Viewport](../assets/screenshots/viewport/04-admin-add-user.png)
Description: Form interface for creating new administrative or staff accounts with validation for username and email uniqueness.

05. Manage Sports — admin/manage_sports.php
Viewport: ![Manage Sports — Viewport](../assets/screenshots/viewport/05-admin-manage-sports.png)
Description: Lists all sports categories with icons, type (team/individual/both), and active status; supports add/edit operations.

06. Add Sport — admin/add_sport.php
Viewport: ![Add Sport — Viewport](../assets/screenshots/viewport/06-admin-add-sport.png)
Description: Form for registering a new sport in the system, including emoji/icon, classification, and min/max players.

07. Manage Teams — admin/manage_teams.php
Viewport: ![Manage Teams — Viewport](../assets/screenshots/viewport/07-admin-manage-teams.png)
Description: Overview of all teams grouped by sport, with quick access to edit and roster management.

08. Add Team — admin/add_team.php
Viewport: ![Add Team — Viewport](../assets/screenshots/viewport/08-admin-add-team.png)
Description: Interface to create new teams, assign a coach, select sport, and optionally upload a logo.

09. Team Roster — admin/team_roster.php
Viewport: ![Team Roster — Viewport](../assets/screenshots/viewport/09-admin-team-roster.png)
Description: Displays team composition with player details and captain designation for a selected team.

10. Manage Players — admin/manage_players.php
Viewport: ![Manage Players — Viewport](../assets/screenshots/viewport/10-admin-manage-players.png)
Description: Central registry of all players; supports filters on department, year, and sport participation.

11. Add Player — admin/add_player.php
Viewport: ![Add Player — Viewport](../assets/screenshots/viewport/11-admin-add-player.png)
Description: Comprehensive form for registering a new player with academic, demographic, and emergency contact details.

12. View Player — admin/view_player.php
Viewport: ![View Player — Viewport](../assets/screenshots/viewport/12-admin-view-player.png)
Description: Detailed profile page showing player information, associated sports, team memberships, and performance summaries.

13. Manage Matches — admin/manage_matches.php
Viewport: ![Manage Matches — Viewport](../assets/screenshots/viewport/13-admin-manage-matches.png)
Description: Tabular listing of all matches with sport, teams, date, venue, and status.

14. Schedule Match — admin/schedule_match.php
Viewport: ![Schedule Match — Viewport](../assets/screenshots/viewport/14-admin-schedule-match.png)
Description: Form-based workflow for creating new fixtures with validation for team selection and date/time fields.

15. View Results — admin/view_results.php
Viewport: ![View Results — Viewport](../assets/screenshots/viewport/15-admin-view-results.png)
Description: Shows completed matches and outcomes with quick filters per sport or time range.

16. Enter Results — admin/enter_results.php
Viewport: ![Enter Results — Viewport](../assets/screenshots/viewport/16-admin-enter-results.png)
Description: Interface to enter final scores and result status (win/loss/draw/walkover).

17. Reports — admin/reports.php
Viewport: ![Reports — Viewport](../assets/screenshots/viewport/17-admin-reports.png)
Description: Aggregated reports showing participation counts, match summaries, and distribution across sports.

18. Analytics — admin/analytics.php
Viewport: ![Analytics — Viewport](../assets/screenshots/viewport/18-admin-analytics.png)
Description: Visual analytics dashboard with charts for trends in registrations, matches, and achievements.

19. Performance Tracking — admin/performance_tracking.php
Viewport: ![Performance Tracking — Viewport](../assets/screenshots/viewport/19-admin-performance-tracking.png)
Description: High-level view summarizing performance metrics of players and teams over time.

20. Player Statistics — admin/player_statistics.php
Viewport: ![Player Statistics — Viewport](../assets/screenshots/viewport/20-admin-player-statistics.png)
Description: Detailed statistics screen offering per-player breakdowns of goals, runs, points, and other metrics.

21. Calendar — admin/calendar.php
Viewport: ![Calendar — Viewport](../assets/screenshots/viewport/21-admin-calendar.png)
Description: Calendar visualization for upcoming and past matches mapped to dates.

22. Notifications — admin/notifications.php
Viewport: ![Notifications — Viewport](../assets/screenshots/viewport/22-admin-notifications.png)
Description: Central notification hub displaying new alerts, reminders, and system messages.

23. Generate Certificate — admin/generate_certificate.php
Viewport: ![Generate Certificate — Viewport](../assets/screenshots/viewport/23-admin-generate-certificate.png)
Description: Admin-side interface for creating participation and achievement certificates for players.

24. Admin Profile — admin/profile.php
Viewport: ![Admin Profile — Viewport](../assets/screenshots/viewport/24-admin-profile.png)
Description: Profile page where the administrator can view and update personal information.

25. Admin Settings — admin/settings.php
Viewport: ![Admin Settings — Viewport](../assets/screenshots/viewport/25-admin-settings.png)
Description: System configuration screen to manage global options such as branding, time zone, and other ready-only technical parameters.


A.3 Staff Pages (Staff Role)
Login Credentials for Testing: staff / password

26. Staff Dashboard — staff/dashboard.php
Viewport: ![Staff Dashboard — Viewport](../assets/screenshots/viewport/26-staff-dashboard.png)
Description: Staff-focused dashboard highlighting active players, teams, completed matches, and upcoming fixtures.

27. View Teams — staff/view_teams.php
Viewport: ![View Teams — Viewport](../assets/screenshots/viewport/27-staff-view-teams.png)
Description: Read-only list of teams available to staff for operational reference.

28. Team Roster — staff/team_roster.php
Viewport: ![Staff Team Roster — Viewport](../assets/screenshots/viewport/28-staff-team-roster.png)
Description: Displays the composition of a selected team, mirroring admin-side but scoped to staff permissions.

29. View Players — staff/view_players.php
Viewport: ![View Players — Viewport](../assets/screenshots/viewport/29-staff-view-players.png)
Description: Listing of all registered players accessible to staff for match preparation and verification.

30. View Player — staff/view_player.php
Viewport: ![Staff View Player — Viewport](../assets/screenshots/viewport/30-staff-view-player.png)
Description: Detailed single-player profile from the staff perspective, including sports participation and team linkage.

31. View Matches — staff/view_matches.php
Viewport: ![View Matches — Viewport](../assets/screenshots/viewport/31-staff-view-matches.png)
Description: Upcoming and past fixtures relevant to staff operations.

32. View Results — staff/view_results.php
Viewport: ![Staff View Results — Viewport](../assets/screenshots/viewport/32-staff-view-results.png)
Description: Overview of match results to assist staff in tracking outcomes and achievements.

33. Enter Scores — staff/enter_scores.php
Viewport: ![Enter Scores — Viewport](../assets/screenshots/viewport/33-staff-enter-scores.png)
Description: Operational interface where staff record match scores and player statistics immediately after games.

34. View Reports — staff/view_reports.php
Viewport: ![Staff View Reports — Viewport](../assets/screenshots/viewport/34-staff-view-reports.png)
Description: Staff-scoped reports highlighting day-to-day operational metrics and summaries.

35. Generate Certificate — staff/generate_certificate.php
Viewport: ![Staff Generate Certificate — Viewport](../assets/screenshots/viewport/35-staff-generate-certificate.png)
Description: Screen used by staff to generate certificates under administrative guidance.

36. Staff Profile — staff/profile.php
Viewport: ![Staff Profile — Viewport](../assets/screenshots/viewport/36-staff-profile.png)
Description: Personal profile where staff can review and update their own details.

37. Staff Settings — staff/settings.php
Viewport: ![Staff Settings — Viewport](../assets/screenshots/viewport/37-staff-settings.png)
Description: Staff settings area for adjusting limited preferences available to this role.

