/* WARNING: The script below is not multi-run safe!
   Do NOT run more than once. */

USE plannpla_db;

ALTER TABLE `Event` DROP COLUMN City;
ALTER TABLE `Event` DROP COLUMN State;
ALTER TABLE `Event` DROP COLUMN Zipcode;

ALTER TABLE `Event` CHANGE Address Address VARCHAR(500) NULL;

DROP TABLE EventTag;
DROP TABLE State;

CREATE TABLE IF NOT EXISTS Tag (
	TagID BIGINT NOT NULL AUTO_INCREMENT,
	`Name` VARCHAR(100) NOT NULL,
	Icon VARCHAR(100) NULL,
	PRIMARY KEY (TagID)
) ENGINE=InnoDB;

/* Insert tag */
INSERT INTO Tag (`Name`)
VALUES ('American Football'),
('Archery'),
('Badminton'),
('Baseball'),
('Basketball'),
('Bowling'),
('Boxing'),
('Cricket'),
('Cycling'),
('Dodgeball'),
('Frisbee'),
('Golf'),
('Gymnastics'),
('Hiking'),
('Hockey'),
('Ice Skating'),
('Indoor Games'),
('Martial Arts'),
('Rock Climbing'),
('Rowing'),
('Running'),
('Skateboarding'),
('Snowsports'),
('Soccer'),
('Surfing'),
('Squash'),
('Swimming'),
('Table Tennis'),
('Tennis'),
('VolleyBall');

ALTER TABLE `Event` ADD TagID BIGINT NULL;
ALTER TABLE `Event` ADD FOREIGN KEY (TagID) REFERENCES Tag (TagID);

DROP TABLE UserTag;

CREATE TABLE IF NOT EXISTS UserTag (
	UserID BIGINT NOT NULL,
	TagID BIGINT NOT NULL,
	PRIMARY KEY (UserID, TagID),
	FOREIGN KEY (UserID) REFERENCES User (UserID),
	FOREIGN KEY (TagID) REFERENCES Tag (TagID)
) ENGINE=InnoDB;