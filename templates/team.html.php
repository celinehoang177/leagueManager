<?php include __DIR__ . '/header.php'; ?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>My Teams</title>
</head>
<body>
    <div class="navbar">
        <a href="/leagueManager/team.php">Team</a>
        <a href="/leagueManager/league.php">League</a>
        <a href="/leagueManager/player.php">Players</a>
        <a href="/leagueManager/match.php">Match</a>
        <a href="/leagueManager/logout.php">Logout</a>
    </div>

    <div class="container">
        <h2>My Teams</h2>
        <?php if ($error): ?>
            <p class="error" style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success" style="color: green;"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <table border="1">
            <thead>
                <tr>
                    <th>Team ID</th>
                    <th>Team Name</th>
                    <th>League ID</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($teams)): ?>
                    <?php foreach ($teams as $team): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($team['Team_ID']); ?></td>
                            <td><?php echo htmlspecialchars($team['TeamName']); ?></td>
                            <td><?php echo htmlspecialchars($team['League_ID']); ?></td>
                            <td><?php echo htmlspecialchars($team['Status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No teams found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h3>Add a Team</h3>
        <form action="team.php" method="POST">
            <input type="text" name="team_name" placeholder="Team Name" required>
            <input type="number" name="league_id" placeholder="League ID" required>
            <button type="submit">Add Team</button>
        </form>
    </div>
</body>
<?php include __DIR__ . '/footer.php'; ?>
