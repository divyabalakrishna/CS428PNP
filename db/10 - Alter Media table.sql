/* WARNING: The script below is not multi-run safe!
   Do NOT run more than once. */

USE plannpla_db;

ALTER TABLE Media ADD Image VARCHAR(100) NULL;
ALTER TABLE Media DROP `Link`;