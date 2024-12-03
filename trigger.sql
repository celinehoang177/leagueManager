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


-- trigger after insert on trade table 
DELIMITER $$

CREATE TRIGGER AfterTradeInsert
AFTER INSERT ON Trade
FOR EACH ROW
BEGIN
    DECLARE team1ID NUMERIC(8,0);
    DECLARE team2ID NUMERIC(8,0);
    DECLARE player1ID NUMERIC(8,0);
    DECLARE player2ID NUMERIC(8,0);
    
    -- Retrieve the data from the new column of trade
    SET team1ID = NEW.Team1_ID;
    SET team2ID = NEW.Team2_ID;
    SET player1ID = NEW.TradedPlayer1_ID;
    SET player2ID = NEW.TradedPlayer2_ID;

    -- Insert 2 row into Player_Trade
    INSERT INTO Player_Trade (Player_ID, Trade_ID)
    VALUES (player1ID, New.Trade_ID);

    INSERT INTO Player_Trade (Player_ID, Trade_ID)
    VALUES (player2ID, New.Trade_ID);


    -- Insert 2 row into Team_Trade
    INSERT INTO Team_Trade (Trade_ID, Team_ID)
    VALUES (New.Trade_ID, team1ID);

    INSERT INTO Team_Trade (Trade_ID, Team_ID)
    VALUES (New.Trade_ID, team2ID);

    -- Update the player's Team_ID to reflect the new team
    -- player 1 is traded to team 2
    -- player 2 is traded to team 1
    UPDATE Player
    SET Team_ID = team2ID
    WHERE Player_ID = player1ID;

    UPDATE Player
    SET Team_ID = team1ID
    WHERE Player_ID = player2ID ;

END $$

DELIMITER ;



-- data retrival for all match based on a user
DELIMITER $$
CREATE PROCEDURE GetMatchByUser(IN p_User_ID INT)
    BEGIN
    SELECT DISTINCT m.* 
    FROM MatchInfo m INNER JOIN Team t
    ON m.Team1_ID = t.Team_ID OR m.Team2_ID = t.Team_ID
    INNER JOIN Users u 
    ON t.Owner = u.User_ID
    WHERE u.User_ID = p_User_ID;
END $$
DELIMITER ;




DELIMITER $$
CREATE PROCEDURE GetPlayerFromUser(IN p_User_ID INT)
    BEGIN
    SELECT p.* 
    FROM Player p INNER JOIN Team t
    ON p.Team_ID = t.Team_ID
    WHERE t.owner = p_User_ID;
END $$
DELIMITER;

CALL GetPlayerFromUser(1);


DELIMITER $$
CREATE PROCEDURE GetTeamFromUser(IN p_User_ID INT)
BEGIN
SELECT *
FROM Team  
WHERE owner = p_User_ID;
END $$
DELIMITER ;

CALL GetTeamFromUser(1);



DELIMITER $$
CREATE PROCEDURE GetLeagueFromUser(IN p_User_ID INT)
BEGIN
    SELECT *
    FROM League
    WHERE User_ID = p_User_ID;
END $$

DELIMITER ;

CALL GetLeagueFromUser(1);