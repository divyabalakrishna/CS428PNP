/* WARNING: The script below is not multi-run safe!
   Do NOT run more than once. */

USE plannpla_db;

ALTER TABLE `media` ADD Image VARCHAR(100) NULL;
ALTER TABLE `media` DROP `Link`;