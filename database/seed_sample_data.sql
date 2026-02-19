-- Sample Data Seeding for College Sports Management System

-- 1. Insert Sample Players
INSERT INTO players (name, register_number, dob, age, gender, department, year, mobile, email, status) VALUES
('John Smith', 'REG001', '2004-05-15', 21, 'Male', 'Computer Science', 'III', '9876543210', 'john@example.com', 'active'),
('Emma Watson', 'REG002', '2005-08-22', 20, 'Female', 'Electronics', 'II', '9876543211', 'emma@example.com', 'active'),
('Michael Jordan', 'REG003', '2003-02-10', 22, 'Male', 'Mechanical', 'IV', '9876543212', 'michael@example.com', 'active'),
('Serena Williams', 'REG004', '2006-11-30', 19, 'Female', 'Civil Engineering', 'I', '9876543213', 'serena@example.com', 'active'),
('Virat Kohli', 'REG005', '2004-12-05', 21, 'Male', 'Commerce', 'III', '9876543214', 'virat@example.com', 'active'),
('Cristiano Ronaldo', 'REG006', '1985-02-05', 38, 'Male', 'Physical Education', 'IV', '9876543215', 'cr7@example.com', 'active');

-- 2. Map Players to Sports
INSERT INTO player_sports (player_id, sport_id, experience_level) VALUES
(1, 1, 'advanced'), -- John -> Cricket
(5, 1, 'intermediate'), -- Virat -> Cricket
(6, 2, 'advanced'), -- Ronaldo -> Football
(2, 5, 'intermediate'), -- Emma -> Badminton
(4, 5, 'advanced'), -- Serena -> Badminton
(3, 3, 'advanced'); -- Michael -> Basketball

-- 3. Insert Sample Teams
INSERT INTO teams (team_name, sport_id, coach_name, matches_played, matches_won) VALUES
('Lions Cricket', 1, 'Coach Adam', 10, 8),
('Tigers Football', 2, 'Coach Bob', 12, 5),
('Hawks Basketball', 3, 'Coach Charlie', 8, 4),
('Eagles Badminton', 5, 'Coach David', 15, 12);

-- 4. Insert Sample Matches
INSERT INTO matches (sport_id, team1_id, team2_id, match_date, match_time, venue, status) VALUES
(1, 1, 1, '2026-02-01', '10:00:00', 'College Main Ground', 'scheduled'),
(2, 2, 2, '2026-02-05', '15:30:00', 'Football Turf', 'scheduled'),
(5, 4, 4, '2026-01-28', '11:00:00', 'Indoor Stadium', 'scheduled');

-- 5. Add some older matches for trend
INSERT INTO matches (sport_id, team1_id, team2_id, match_date, match_time, venue, status) VALUES
(1, 1, 1, '2025-12-15', '10:00:00', 'Ground A', 'completed'),
(2, 2, 2, '2025-11-20', '10:00:00', 'Ground B', 'completed'),
(3, 3, 3, '2025-10-10', '10:00:00', 'Gym', 'completed');

-- 6. Activity Logs
INSERT INTO activity_log (user_id, action_type, module, description) VALUES
(1, 'create', 'players', 'Added 6 new sample players'),
(1, 'create', 'teams', 'Created 4 athletic teams'),
(1, 'update', 'settings', 'Updated system preferences');
