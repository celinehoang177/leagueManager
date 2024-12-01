DELIMITER $$

CREATE PROCEDURE AddUser(
    IN p_UserName VARCHAR(20),
    IN p_Email VARCHAR(50),
    IN p_Password VARCHAR(64),
    IN p_FullName VARCHAR(64)
)
BEGIN

    -- Check if the email already exists
    -- IF EXISTS (SELECT 1 FROM User WHERE Email = p_Email) THEN
        -- SIGNAL SQLSTATE '45000' 
        -- SET MESSAGE_TEXT = 'Email already exists. Please use a different email.';
    -- ELSE
        -- Insert the new user
        DECLARE next_ID NUMERIC(8,0);
        SELECT IFNULL(MAX(User_ID), 0) + 1 INTO next_ID FROM User;

        INSERT INTO User (User_ID, UserName, Email, Password, FullName, ProfileSettings)
        VALUES (next_ID, p_UserName, p_Email, p_Password, p_FullName, '{"theme":"light","notifications":false}');
    -- END IF;
END $$

DELIMITER ;


CALL AddUser('usertest','user@gmail.com','123456','User Test');


-- add match 


DELIMITER @@

CREATE TRIGGER BeforeMatchInsert
BEFORE INSERT ON `Match`
FOR EACH ROW
BEGIN
    DECLARE i INT DEFAULT 1;
    DECLARE randomScore INT;
    DECLARE randomAssists INT;
    DECLARE next_ID NUMERIC(10,0);
    SELECT IFNULL(MAX(Statistic_ID), 0) INTO next_ID FROM Player_Statistic;

    -- Loop to insert 10 random rows into PlayerStatistics for the new match
    WHILE i <= 10 DO
        -- Generate random data for PlayerID, Score, and Assists
        SET randomScore = FLOOR(RAND() * 8);           -- Random Score between 0 and 30
        SET randomAssists = FLOOR(RAND() * 3);         -- Random Assists between 0 and 10
        SET next_ID = next_ID + 1;

        -- Insert a row into PlayerStatistics
        INSERT INTO PlayerStatistics (MatchID, PlayerID, Score, Assists)
        VALUES (next_ID, , randomScore, randomAssists);

        -- Increment the loop counter
        SET i = i + 1;
    END WHILE;
END @@

DELIMITER ;


-- retrivier all player from the given team ID
DELIMITER $$
CREATE PROCEDURE GetPlayersByTeam(IN p_Team_ID INT)
BEGIN
SELECT Player.FullName, Player.Position, Player.FantasyPoints
FROM Player INNER JOIN Team 
ON Player.Team_ID = Team.Team_ID
WHERE  Team.Team_ID = p_Team_ID;
END $$
DELIMITER;


-- add a team give League ID and userID
DELIMITER $$

CREATE PROCEDURE AddTeam (
    IN p_TeamName VARCHAR(25),
    IN p_User_ID NUMERIC(8,0),
    IN p_League_ID VARCHAR(50)
)
BEGIN
    DECLARE next_ID NUMERIC(8,0);
    SELECT IFNULL(MAX(Team_ID), 0) + 1 INTO next_ID FROM Team;
    INSERT INTO Team (Team_ID,TeamName, Owner, League_ID, Status)
    -- team default status available with ranking NUll
    VALUES (next_ID,p_TeamName,p_User_ID, p_League_ID,'A');
END $$

DELIMITER ;

CALL AddTeam('teamtest',1,1);


