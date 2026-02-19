-- Add logo column to teams table
ALTER TABLE teams ADD COLUMN logo VARCHAR(255) AFTER coach_name;
