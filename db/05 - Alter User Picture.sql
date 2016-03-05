/* WARNING: The script below is not multi-run safe!
   Do NOT run more than once. */

USE plannpla_db;

ALTER TABLE `User` CHANGE `Picture` `Picture` VARCHAR(100) NULL DEFAULT NULL;
