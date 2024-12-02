<?php include __DIR__ . '/header.php'; ?>
<head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="style.css">
	<title></title>
</head>
<body>
    <div class="navbar">
        <a href="/leagueManager/dashboard.php">Home</a>
        <a href="/leagueManager/team.php">Team</a>
        <a href="/leagueManager/league.php">League</a>
        <a href="/leagueManager/player.php">Players</a>
        <a href="/leagueManager/match.php">Match</a>
        <div class="logout">
            <a href="/leagueManager/logout.php">Logout</a>
        </div>
    </div>
    <div class="container">
        <div class="myteam">
            <h2>My Team</h2>
            <div class="teamlist">
                <p>add team tables</p>
            </div>
            <a href="addteam.html"><button class="addteam">Add a team</button></a>
        </div>
    </div>
</body>
<?php include __DIR__ . '/footer.php'; ?>
