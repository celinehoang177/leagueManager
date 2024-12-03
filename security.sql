CREATE ROLE manager;
GRANT ALL ON *.*  TO manager;

CREATE ROLE player;
GRANT SELECT, INSERT ON Trade.* TO player;
GRANT SELECT, INSERT ON Player_Trade.* TO player;
GRANT SELECT, INSERT ON Team_Trade.* TO player;
GRANT SELECT ON Match_Team.* TO player;
GRANT SELECT ON Match_Info.* TO player;
GRANT All ON Player_Statistic.* TO player;
GRANT SELECT ON Player.* TO player;
GRANT SELECT ON Wavier.* TO player;
GRANT SELECT ON League.* TO player;
GRANT SELECT ON Draft.* TO player;
GRANT SELECT, INSERT ON Team.* TO player;

CREATE USER'superUser'@'localhost' IDENTIFIED BY 'pass';
GRANT manager TO 'superUser'@'localhost';

CREATE USER 'abc'@'%';
GRANT player TO 'abc'@'%';