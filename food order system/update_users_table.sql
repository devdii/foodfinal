-- Add new columns to users table if they don't exist
ALTER TABLE users
ADD COLUMN IF NOT EXISTS Name VARCHAR(100) NULL,
ADD COLUMN IF NOT EXISTS Phone VARCHAR(15) NULL,
ADD COLUMN IF NOT EXISTS Address TEXT NULL,
ADD COLUMN IF NOT EXISTS dietary_preferences TEXT NULL,
ADD COLUMN IF NOT EXISTS delivery_instructions TEXT NULL;
