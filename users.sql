CREATE TABLE IF NOT EXISTS Users (uID int PRIMARY KEY NOT NULL, firstName varchar(100) NOT NULL, lastName varchar(100) NOT NULL, lat real, long real, fbID varchar(100));

INSERT INTO Users VALUES (1,'George','Michopoulos', 37.423046400000004, -122.168966, NULL);
INSERT INTO Users VALUES (2,'Shilpa','Dilip Apte', 37.42307, -122.168965, NULL);
INSERT INTO Users VALUES (3,'Dan','Schwartz', 37.423013, -122.16897, NULL);