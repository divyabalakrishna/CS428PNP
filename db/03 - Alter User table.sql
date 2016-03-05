/* WARNING: The script below is not multi-run safe!
   Do NOT run more than once. */

USE plannpla_db;
ALTER TABLE `User` ADD NickName VARCHAR(100) NULL;
ALTER TABLE `User` ADD Gender VARCHAR(1) NULL;
ALTER TABLE `User` ADD BirthDate DATE NULL;