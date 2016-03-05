/* WARNING: The script below is not multi-run safe!
   Do NOT run more than once. */

USE plannpla_db;


ALTER TABLE `Event` ADD Lat DOUBLE NULL DEFAULT 0;
ALTER TABLE `Event` ADD Lon DOUBLE NULL DEFAULT 0;

ALTER TABLE `User` CHANGE Radius Radius INT NULL DEFAULT 5;
