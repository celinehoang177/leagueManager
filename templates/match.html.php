<?php include __DIR__ . '/header.php'; ?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>My Matches</title>
</head>
<body>
    <div class="navbar">
        <div class="left">
            <a href="/leagueManager/dashboard.php">Home</a>
        </div>
        <div class="center">
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="/leagueManager/users.php">Users</a>
            <?php endif; ?>
            <a href="/leagueManager/team.php">Team</a>
            <a href="/leagueManager/league.php">League</a>
            <a href="/leagueManager/player.php">Player</a>
            <a href="/leagueManager/match.php">Match</a>
        </div>
        <div class="right">
            <a href="/leagueManager/logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="match">
            <br></br>
            <h2>
                <?php echo ($_SESSION['role'] === 'admin') ? 'All Matches' : 'My Matches'; ?>
            </h2>

            <!-- Error and Success Messages -->
            <?php if (!empty($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <p class="success"><?php echo htmlspecialchars($success); ?></p>
            <?php endif; ?>

            <!-- Search Form or See All Button -->
            <?php if (!$search_mode): ?>
                <form action="match.php?search_mode=1" method="POST" style="margin-bottom: 20px;">
                    <input type="number" name="search_team_id" placeholder="Enter Team ID to search" required>
                    <button type="submit">Search</button>
                </form>
            <?php else: ?>
                <form action="match.php" method="GET" style="margin-bottom: 20px;">
                    <button type="submit">See All Matches</button>
                </form>
            <?php endif; ?>

            <!-- Match Table -->
            <table border="1">
                <thead>
                    <tr>
                        <th>Match ID</th>
                        <th>Team 1 ID</th>
                        <th>Team 2 ID</th>
                        <th>Match Date</th>
                        <th>Final Score</th>
                        <th>Winner</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($matches)): ?>
                        <?php foreach ($matches as $match): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($match['Match_ID']); ?></td>
                                <td><?php echo htmlspecialchars($match['Team1_ID']); ?></td>
                                <td><?php echo htmlspecialchars($match['Team2_ID']); ?></td>
                                <td><?php echo htmlspecialchars($match['MatchDate']); ?></td>
                                <td><?php echo htmlspecialchars($match['FinalScore']); ?></td>
                                <td><?php echo htmlspecialchars($match['Winner']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No matches found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
<?php include __DIR__ . '/footer.php'; ?>
