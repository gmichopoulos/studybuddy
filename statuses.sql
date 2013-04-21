CREATE TABLE IF NOT EXISTS Statuses( uID int NOT NULL, cID int NOT NULL, status varchar(200), PRIMARY KEY (uID, cID));

INSERT INTO Statuses VALUES (1,1,"lets get this train rollin");
INSERT INTO Statuses VALUES (1,2,"kill me");
INSERT INTO Statuses VALUES (2,2,"kill me");
INSERT INTO Statuses VALUES (2,3,"this lab sucks");
INSERT INTO Statuses VALUES (3,1,"weeeee");
INSERT INTO Statuses VALUES (3,4,"merp");