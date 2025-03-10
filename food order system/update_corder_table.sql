-- Add sessionid column to corder table if it doesn't exist
ALTER TABLE corder
ADD COLUMN IF NOT EXISTS sessionid VARCHAR(255) NULL;
