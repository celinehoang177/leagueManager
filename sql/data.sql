
--Create all the table
CREATE TABLE Users (
    User_ID NUMERIC(8, 0) PRIMARY KEY,
    FullName VARCHAR(50),
    Email VARCHAR(50) UNIQUE NOT NULL,
    Username VARCHAR(20) UNIQUE NOT NULL,
    Password VARCHAR(64) NOT NULL,
    ProfileSettings JSON
);

ALTER TABLE Users ADD Role ENUM('admin', 'user') DEFAULT 'user';

CREATE TABLE League (
    League_ID NUMERIC(8, 0) PRIMARY KEY, 
    LeagueName VARCHAR(30) NOT NULL, 
    LeagueType ENUM('P', 'R') NOT NULL, 
    User_ID NUMERIC(8, 0), 
    MaxTeams NUMERIC(2, 0) NOT NULL, 
    DraftDate DATE, 
    FOREIGN KEY (User_ID) REFERENCES User(User_ID) 
);

CREATE TABLE Team (
    Team_ID NUMERIC(8, 0) PRIMARY KEY,
    TeamName VARCHAR(25) NOT NULL,
    Owner NUMERIC(8, 0),
    League_ID NUMERIC(8, 0),
    TotalPoints NUMERIC(6, 2) DEFAULT 0.00,
    Ranking NUMERIC(3, 0),
    Status ENUM('A', 'I') NOT NULL DEFAULT 'A',
    FOREIGN KEY (Owner) REFERENCES User(User_ID),
    FOREIGN KEY (League_ID) REFERENCES League(League_ID)
);

CREATE TABLE Player (
    Player_ID NUMERIC(8, 0) PRIMARY KEY,
    FullName VARCHAR(50) NOT NULL,
    Sport CHAR(3) NOT NULL,
    Position CHAR(3),
    RealTeam VARCHAR(50),
    FantasyPoints NUMERIC(6, 2) DEFAULT 0.00,
    AvailabilityStatus ENUM('A', 'U') DEFAULT 'A',
    Team_ID NUMERIC(8, 0),
    FOREIGN KEY (Team_ID) REFERENCES Team(Team_ID)
);

CREATE TABLE MatchInfo (
    Match_ID NUMERIC(8, 0) PRIMARY KEY,
    Team1_ID NUMERIC(8, 0),
    Team2_ID NUMERIC(8, 0),
    MatchDate DATE,
    FinalScore VARCHAR(10),
    Winner NUMERIC(8, 0),
    FOREIGN KEY (Team1_ID) REFERENCES Team(Team_ID),
    FOREIGN KEY (Team2_ID) REFERENCES Team(Team_ID)
);

CREATE TABLE Draft (
    Draft_ID NUMERIC(8, 0) PRIMARY KEY,
    League_ID NUMERIC(8, 0),
    DraftDate DATE,
    DraftOrder ENUM('R', 'S'),
    DraftStatus ENUM('I', 'C') DEFAULT 'I',
    FOREIGN KEY (League_ID) REFERENCES League(League_ID)
);


CREATE TABLE Trade ( 
    Trade_ID NUMERIC(10, 0) PRIMARY KEY, 
    Team1_ID NUMERIC(8, 0), 
    Team2_ID NUMERIC(8, 0), 
    TradedPlayer1_ID NUMERIC(8, 0), 
    TradedPlayer2_ID NUMERIC(8, 0), 
    TradeDate DATE, 
    FOREIGN KEY (Team1_ID) REFERENCES Team(Team_ID), 
    FOREIGN KEY (Team2_ID) REFERENCES Team(Team_ID), 
    FOREIGN KEY (TradedPlayer1_ID) REFERENCES Player(Player_ID), 
    FOREIGN KEY (TradedPlayer2_ID) REFERENCES Player(Player_ID) 
);

CREATE TABLE Player_Trade (
    Player_ID NUMERIC(8, 0),
    Trade_ID NUMERIC(10, 0),
    PRIMARY KEY (Player_ID, Trade_ID),
    FOREIGN KEY (Player_ID) REFERENCES Player(Player_ID),
    FOREIGN KEY (Trade_ID) REFERENCES Trade(Trade_ID)
);

CREATE TABLE Waiver (
    Waiver_ID NUMERIC(8, 0) PRIMARY KEY,
    Team_ID NUMERIC(8, 0),
    Player_ID NUMERIC(8, 0),
    WaiverOrder NUMERIC(3, 0),
    WaiverStatus ENUM('P', 'A') NOT NULL DEFAULT 'P',
    WaiverPickupDate DATE,
    FOREIGN KEY (Team_ID) REFERENCES Team(Team_ID),
    FOREIGN KEY (Player_ID) REFERENCES Player(Player_ID)
);

CREATE TABLE Player_Statistic (
    Statistic_ID NUMERIC(10, 0) PRIMARY KEY,
    Player_ID NUMERIC(8, 0),
    GameDate DATE,
    PerformanceStats TEXT,
    InjuryStatus ENUM('Y', 'N') NOT NULL DEFAULT 'N',
    FOREIGN KEY (Player_ID) REFERENCES Player(Player_ID)
);

CREATE TABLE Match_Team (
    Match_ID NUMERIC(8, 0),
    Team_ID NUMERIC(8, 0),
    PRIMARY KEY (Match_ID, Team_ID),
    FOREIGN KEY (Match_ID) REFERENCES MatchInfo(Match_ID),
    FOREIGN KEY (Team_ID) REFERENCES Team(Team_ID)
);

CREATE TABLE Team_Trade (
    Trade_ID NUMERIC(10, 0),
    Team_ID NUMERIC(8, 0),
    PRIMARY KEY (Trade_ID, Team_ID),
    FOREIGN KEY (Trade_ID) REFERENCES Trade(Trade_ID),
    FOREIGN KEY (Team_ID) REFERENCES Team(Team_ID)
);

--Insert value to tables
INSERT INTO User (User_ID, FullName, Email, Username, Password, ProfileSettings)
VALUES
(1, 'John Doe', 'john@example.com', 'johndoe', 'password123', '{"theme":"dark","notifications":true}'),
(2, 'Jane Smith', 'jane@example.com', 'janesmith', 'password456', '{"theme":"light","notifications":false}'),
(3, 'Alex Johnson', 'alexj@example.com', 'alexjohnson', 'password789', '{"theme":"dark","notifications":true}'),
(4, 'Chris Lee', 'chris@example.com', 'chrislee', 'password101', '{"theme":"light","notifications":true}'),
(5, 'Pat Taylor', 'pat@example.com', 'pattaylor', 'password202', '{"theme":"dark","notifications":false}'),
(6, 'Jordan Brown', 'jordan@example.com', 'jordanbrown', 'password303', '{"theme":"light","notifications":true}'),
(7, 'Taylor White', 'taylor@example.com', 'twhite', 'password404', '{"theme":"dark","notifications":false}'),
(8, 'Morgan Green', 'morgan@example.com', 'morgangreen', 'password505', '{"theme":"light","notifications":true}'),
(9, 'Casey Kim', 'casey@example.com', 'caseykim', 'password606', '{"theme":"dark","notifications":false}'),
(10, 'Jamie Clark', 'jamie@example.com', 'jamieclark', 'password707', '{"theme":"light","notifications":true}');


INSERT INTO League (League_ID, LeagueName, LeagueType, User_ID, MaxTeams, DraftDate)
VALUES
(1, 'NBA', 'P', 1, 15, '2024-06-01'),

INSERT INTO Team (Team_ID, TeamName, Owner, League_ID, Status)
VALUES 
    (1, 'Team1', 1, 1, 'A'),
    (2, 'Team2', 2, 1, 'A'),
    (3, 'Team3', 3, 1, 'A'),
    (4, 'Team4', 4, 1, 'A');

INSERT INTO Player (Player_ID, FullName, Sport, Position, RealTeam, AvailabilityStatus, Team_ID)
VALUES
    (1, 'LeBron James', 'BAS', 'SF', 'Los Angeles Lakers', 'A', 1),
    (2, 'Anthony Davis', 'BAS', 'PF', 'Los Angeles Lakers', 'A', 1),
    (3, 'Austin Reaves', 'BAS', 'SG', 'Los Angeles Lakers', 'A', 1),
    (4, 'Angelo Russell', 'BAS', 'PG', 'Los Angeles Lakers', 'A', 1),
    (5, 'Rui Hachimura', 'BAS', 'PF', 'Los Angeles Lakers', 'A', 1),
    (6, 'Stephen Curry', 'BAS', 'PG', 'Golden State Warriors', 'A', 2),
    (7, 'Klay Thompson', 'BAS', 'SG', 'Golden State Warriors', 'A', 2),
    (8, 'Draymond Green', 'BAS', 'PF', 'Golden State Warriors', 'A', 2),
    (9, 'Andrew Wiggins', 'BAS', 'SF', 'Golden State Warriors', 'A', 2),
    (10, 'Kevon Looney', 'BAS', 'C', 'Golden State Warriors', 'A', 2),
    (11, 'Kevin Durant', 'BAS', 'PF', 'Phoenix Suns', 'A', 3),
    (12, 'Devin Booker', 'BAS', 'SG', 'Phoenix Suns', 'A', 3),
    (13, 'Bradley Beal', 'BAS', 'SG', 'Phoenix Suns', 'A', 3),
    (14, 'Deandre Ayton', 'BAS', 'C', 'Phoenix Suns', 'A', 3),
    (15, 'Chris Paul', 'BAS', 'PG', 'Phoenix Suns', 'A', 3),
    (16, 'Giannis Antetokounmpo', 'BAS', 'PF', 'Milwaukee Bucks', 'A', 4),
    (17, 'Khris Middleton', 'BAS', 'SF', 'Milwaukee Bucks', 'A', 4),
    (18, 'Jrue Holiday', 'BAS', 'PG', 'Milwaukee Bucks', 'A', 4),
    (19, 'Brook Lopez', 'BAS', 'C', 'Milwaukee Bucks', 'A', 4),
    (20, 'Bobby Portis', 'BAS', 'PF', 'Milwaukee Bucks', 'A', 4);

INSERT INTO MatchInfo (Match_ID, Team1_ID, Team2_ID, MatchDate, FinalScore, Winner)
VALUES
    (1, 1, 2, '2024-12-01', '21-15', 1),
    (2, 1, 3, '2024-12-03', '14-21', 3),
    (3, 1, 4, '2024-12-05', '21-18', 1),
    (4, 2, 3, '2024-12-07', '21-19', 2),
    (5, 2, 4, '2024-12-09', '17-21', 4),
    (6, 3, 4, '2024-12-11', '21-20', 3);

INSERT INTO Player_Statistic (Statistic_ID, Player_ID, GameDate, PerformanceStats, InjuryStatus)
VALUES
    -- Match 1: Team 1 (21) vs. Team 2 (15)
    (1, 1, '2024-12-01', 'Points: 6, Rebounds: 3, Assists: 2', 'N'),
    (2, 2, '2024-12-01', 'Points: 5, Rebounds: 2, Assists: 3', 'N'),
    (3, 3, '2024-12-01', 'Points: 7, Rebounds: 4, Assists: 1', 'N'),
    (4, 4, '2024-12-01', 'Points: 3, Rebounds: 1, Assists: 0', 'N'),
    (5, 5, '2024-12-01', 'Points: 0, Rebounds: 2, Assists: 1', 'N'), -- 0 points
    (6, 6, '2024-12-01', 'Points: 4, Rebounds: 3, Assists: 2', 'N'),
    (7, 7, '2024-12-01', 'Points: 5, Rebounds: 4, Assists: 1', 'N'),
    (8, 8, '2024-12-01', 'Points: 4, Rebounds: 2, Assists: 3', 'N'),
    (9, 9, '2024-12-01', 'Points: 2, Rebounds: 3, Assists: 0', 'N'), -- Remaining points for consistency
    (10, 10, '2024-12-01', 'Points: 0, Rebounds: 1, Assists: 2', 'N'), -- 0 points

    -- Match 2: Team 1 (14) vs. Team 3 (21)
    (11, 1, '2024-12-03', 'Points: 4, Rebounds: 3, Assists: 1', 'N'),
    (12, 2, '2024-12-03', 'Points: 3, Rebounds: 2, Assists: 2', 'N'),
    (13, 3, '2024-12-03', 'Points: 7, Rebounds: 4, Assists: 1', 'N'),
    (14, 4, '2024-12-03', 'Points: 0, Rebounds: 2, Assists: 0', 'N'), -- 0 points
    (15, 5, '2024-12-03', 'Points: 0, Rebounds: 1, Assists: 1', 'N'), -- 0 points
    (16, 11, '2024-12-03', 'Points: 5, Rebounds: 3, Assists: 1', 'N'),
    (17, 12, '2024-12-03', 'Points: 8, Rebounds: 2, Assists: 3', 'N'),
    (18, 13, '2024-12-03', 'Points: 8, Rebounds: 4, Assists: 2', 'N'),
    (19, 14, '2024-12-03', 'Points: 0, Rebounds: 3, Assists: 2', 'N'), -- Remaining players
    (20, 15, '2024-12-03', 'Points: 0, Rebounds: 1, Assists: 0', 'N'), -- 0 points

    -- Match 3: Team 1 (21) vs. Team 4 (18)
    (21, 1, '2024-12-05', 'Points: 7, Rebounds: 3, Assists: 2', 'N'),
    (22, 2, '2024-12-05', 'Points: 5, Rebounds: 2, Assists: 1', 'N'),
    (23, 3, '2024-12-05', 'Points: 9, Rebounds: 4, Assists: 3', 'N'),
    (24, 4, '2024-12-05', 'Points: 0, Rebounds: 2, Assists: 1', 'N'), -- 0 points
    (25, 5, '2024-12-05', 'Points: 0, Rebounds: 1, Assists: 0', 'N'), -- 0 points
    (26, 16, '2024-12-05', 'Points: 6, Rebounds: 5, Assists: 1', 'N'),
    (27, 17, '2024-12-05', 'Points: 7, Rebounds: 2, Assists: 2', 'N'),
    (28, 18, '2024-12-05', 'Points: 5, Rebounds: 1, Assists: 3', 'N'),
    (29, 19, '2024-12-05', 'Points: 0, Rebounds: 3, Assists: 0', 'N'), -- Remaining players
    (30, 20, '2024-12-05', 'Points: 0, Rebounds: 1, Assists: 2', 'N'), -- 0 points

    -- Match 4: Team 2 (21) vs. Team 3 (19)
    (31, 6, '2024-12-07', 'Points: 7, Rebounds: 2, Assists: 1', 'N'),
    (32, 7, '2024-12-07', 'Points: 8, Rebounds: 4, Assists: 3', 'N'),
    (33, 8, '2024-12-07', 'Points: 6, Rebounds: 3, Assists: 2', 'N'),
    (34, 9, '2024-12-07', 'Points: 0, Rebounds: 1, Assists: 1', 'N'), -- 0 points
    (35, 10, '2024-12-07', 'Points: 0, Rebounds: 2, Assists: 1', 'N'), -- 0 points
    (36, 11, '2024-12-07', 'Points: 5, Rebounds: 2, Assists: 1', 'N'),
    (37, 12, '2024-12-07', 'Points: 3, Rebounds: 4, Assists: 3', 'N'),
    (38, 13, '2024-12-07', 'Points: 1, Rebounds: 3, Assists: 2', 'N'),
    (39, 14, '2024-12-07', 'Points: 10, Rebounds: 1, Assists: 1', 'N'), -- 0 points
    (40, 15, '2024-12-07', 'Points: 0, Rebounds: 2, Assists: 1', 'N'), -- 0 points


    -- Match 5: Team 2 (17) vs. Team 4 (21)
    (41, 6, '2024-12-09', 'Points: 6, Rebounds: 2, Assists: 1', 'N'),
    (42, 7, '2024-12-09', 'Points: 7, Rebounds: 4, Assists: 2', 'N'),
    (43, 8, '2024-12-09', 'Points: 2, Rebounds: 3, Assists: 3', 'N'),
    (44, 9, '2024-12-09', 'Points: 2, Rebounds: 2, Assists: 1', 'N'),
    (45, 10, '2024-12-09', 'Points: 0, Rebounds: 4, Assists: 2', 'N'),
    (46, 16, '2024-12-09', 'Points: 8, Rebounds: 4, Assists: 2', 'N'),
    (47, 17, '2024-12-09', 'Points: 6, Rebounds: 2, Assists: 1', 'N'),
    (48, 18, '2024-12-09', 'Points: 3, Rebounds: 3, Assists: 1', 'N'),
    (49, 19, '2024-12-09', 'Points: 4, Rebounds: 2, Assists: 1', 'N'), 
    (50, 20, '2024-12-09', 'Points: 0, Rebounds: 3, Assists: 0', 'N'), 

    -- Match 6: Team 3 (21) vs. Team 4 (20)
    (51, 11, '2024-12-11', 'Points: 7, Rebounds: 2, Assists: 1', 'N'),
    (52, 12, '2024-12-11', 'Points: 3, Rebounds: 4, Assists: 2', 'N'),
    (53, 13, '2024-12-11', 'Points: 0, Rebounds: 3, Assists: 3', 'N'),
    (54, 14, '2024-12-11', 'Points: 6, Rebounds: 2, Assists: 1', 'N'),
    (55, 15, '2024-12-11', 'Points: 5, Rebounds: 4, Assists: 2', 'N'),
    (56, 16, '2024-12-11', 'Points: 2, Rebounds: 2, Assists: 1', 'N'),
    (57, 17, '2024-12-11', 'Points: 3, Rebounds: 4, Assists: 2', 'N'),
    (58, 18, '2024-12-11', 'Points: 10, Rebounds: 3, Assists: 3', 'N'),
    (59, 19, '2024-12-11', 'Points: 3, Rebounds: 2, Assists: 1', 'N'),
    (60, 20, '2024-12-11', 'Points: 2, Rebounds: 4, Assists: 2', 'N');
    
    
-- have to use trigger to change the rows in player's fk reference
INSERT INTO Trade (Trade_ID, Team1_ID, Team2_ID, TradedPlayer1_ID, TradedPlayer2_ID, TradeDate)
VALUES
    (1, 1, 2, 1, 6, '2024-12-12');

INSERT INTO Player_Trade (Player_ID, Trade_ID)
VALUES
    (1, 1), -- Player 1 traded in Trade 1
    (6, 1); -- Player 6 traded in Trade 1

INSERT INTO Team_Trade (Trade_ID, Team_ID)
VALUES
    (1, 1), -- Trade 1 involving Team 1
    (1, 2); -- Trade 1 involving Team 2


INSERT INTO Match_Team (Match_ID, Team_ID)
VALUES
    -- Match 1: Team 1 vs. Team 2
    (1, 1), -- Team 1 in Match 1
    (1, 2), -- Team 2 in Match 1

    -- Match 2: Team 1 vs. Team 3
    (2, 1), -- Team 1 in Match 2
    (2, 3), -- Team 3 in Match 2

    -- Match 3: Team 1 vs. Team 4
    (3, 1), -- Team 1 in Match 3
    (3, 4), -- Team 4 in Match 3

    -- Match 4: Team 2 vs. Team 3
    (4, 2), -- Team 2 in Match 4
    (4, 3), -- Team 3 in Match 4

    -- Match 5: Team 2 vs. Team 4
    (5, 2), -- Team 2 in Match 5
    (5, 4), -- Team 4 in Match 5

    -- Match 6: Team 3 vs. Team 4
    (6, 3), -- Team 3 in Match 6
    (6, 4); -- Team 4 in Match 6

-- Draft table
INSERT INTO Draft (Draft_ID, League_ID, DraftDate, DraftOrder, DraftStatus)
VALUES
    (1, 1, '2024-01-15', 'R', 'I'),
    (2, 2, '2024-02-01', 'S', 'C'),
    (3, 3, '2024-03-10', 'R', 'I'),
    (4, 4, '2024-04-20', 'S', 'C'),
    (5, 5, '2024-05-25', 'R', 'I'),
    (6, 6, '2024-06-30', 'S', 'C'),
    (7, 7, '2024-07-15', 'R', 'I'),
    (8, 8, '2024-08-05', 'S', 'C'),
    (9, 9, '2024-09-12', 'R', 'I'),
    (10, 10, '2024-10-01', 'S', 'C');

-- Trade table
INSERT INTO Trade (Trade_ID, Team1_ID, Team2_ID, TradedPlayer1_ID, TradedPlayer2_ID, TradeDate)
VALUES
    (1, 1, 2, 101, 201, '2024-01-10'),
    (2, 3, 4, 301, 401, '2024-02-15'),
    (3, 5, 6, 501, 601, '2024-03-20'),
    (4, 7, 8, 701, 801, '2024-04-25'),
    (5, 9, 10, 901, 1001, '2024-05-30'),
    (6, 2, 3, 202, 302, '2024-06-05'),
    (7, 4, 5, 402, 502, '2024-07-10'),
    (8, 6, 7, 602, 702, '2024-08-15'),
    (9, 8, 9, 802, 902, '2024-09-20'),
    (10, 10, 1, 1002, 102, '2024-10-01');

-- Insert Waiver table
INSERT INTO Waiver (Waiver_ID, Team_ID, Player_ID, WaiverOrder, WaiverStatus, WaiverPickupDate)
VALUES
    (1, 101, 1001, 1, 'P', NULL),
    (2, 102, 1002, 2, 'A', '2024-12-01'),
    (3, 101, 1003, 3, 'P', NULL),
    (4, 103, 1001, 1, 'A', '2024-11-30'),
    (5, 104, 1004, 4, 'P', NULL),
    (6, 102, 1005, 2, 'A', '2024-12-02'),
    (7, 105, 1002, 3, 'P', NULL),
    (8, 103, 1006, 1, 'A', '2024-12-01');
