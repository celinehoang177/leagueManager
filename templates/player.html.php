<?php include __DIR__ . '/header.php'; ?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>My Players</title>
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
        <div class="player">
            <br></br>
            <h2>My Players</h2>

            <!-- Search/See All Form -->
            <form action="<?php echo $search_mode ? 'player.php' : 'player.php?search_mode=1'; ?>" method="POST" style="margin-bottom: 20px;">
                <?php if (!$search_mode): ?>
                    <input type="text" name="search_team_id" placeholder="Enter Team ID to search" required>
                    <button type="submit">Search</button>
                <?php else: ?>
                    <button type="submit">See All</button>
                <?php endif; ?>
            </form>

            <!-- Error and Success Messages -->
            <?php if (!empty($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <p class="success"><?php echo htmlspecialchars($success); ?></p>
            <?php endif; ?>
            <br></br>

            <!-- Player Table -->
            <h3>Players</h3>
            <table border="1">
                <thead>
                    <tr>
                        <th>Player ID</th>
                        <th>Full Name</th>
                        <th>Sport</th>
                        <th>Position</th>
                        <th>Real Team</th>
                        <th>Fantasy Points</th>
                        <th>Availability</th>
                        <th>Team ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($players)): ?>
                        <?php foreach ($players as $player): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($player['Player_ID'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($player['FullName'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($player['Sport'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($player['Position'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($player['RealTeam'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($player['FantasyPoints'] ?? '0.00'); ?></td>
                                <td><?php echo htmlspecialchars($player['AvailabilityStatus'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($player['Team_ID'] ?? 'N/A'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">No players found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <br></br>

            <!-- Player Statistics Table -->
            <h3>Player Statistics</h3>
            <table border="1">
                <thead>
                    <tr>
                        <th>Statistic ID</th>
                        <th>Player ID</th>
                        <th>Game Date</th>
                        <th>Performance Stats</th>
                        <th>Injury Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($stats)): ?>
                        <?php foreach ($stats as $stat): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($stat['Statistic_ID'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($stat['Player_ID'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($stat['GameDate'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($stat['PerformanceStats'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($stat['InjuryStatus'] ?? 'N/A'); ?></td>
                                <td>
                                    <form action="player.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="delete_statistic_id" value="<?php echo htmlspecialchars($stat['Statistic_ID']); ?>">
                                        <button type="submit" onclick="return confirm('Are you sure you want to delete this statistic?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No statistics found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
<?php include __DIR__ . '/footer.php'; ?>
