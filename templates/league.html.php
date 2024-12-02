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
        <div class="myleague">
            <h2>My League</h2>
            <div class="leaguelist">
                <p>add league tables</p>
			</div>
            <div class="leaguedetail">
                <button class="addleague">Add a league</button>
                <button class="joinleague">Join a league</button>
                <button class="drafts">Draft</button>
            </div>
        </div>
    </div>
</body>
<?php include __DIR__ . '/footer.php'; ?>
