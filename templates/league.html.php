<?php include __DIR__ . '/header.php'; ?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Leagues</title>
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
        <div class="league">
            <br></br>
            <br></br>
            <h2 class="titlename">Leagues</h2>

            <?php if ($_SESSION['role'] === 'admin'): ?>
                <!-- Admin View -->
                <h3>All Leagues</h3>
                <table border="1">
                    <thead>
                        <tr>
                            <th>League ID</th>
                            <th>League Name</th>
                            <th>Type</th>
                            <th>Max Teams</th>
                            <th>Draft Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($owned_leagues as $league): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($league['League_ID']); ?></td>
                                <td><?php echo htmlspecialchars($league['LeagueName']); ?></td>
                                <td><?php echo htmlspecialchars($league['LeagueType']); ?></td>
                                <td><?php echo htmlspecialchars($league['MaxTeams']); ?></td>
                                <td><?php echo htmlspecialchars($league['DraftDate']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <br></br>
                <h3>All Drafts</h3>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Draft ID</th>
                            <th>League ID</th>
                            <th>Draft Date</th>
                            <th>Draft Order</th>
                            <th>Draft Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($drafts as $draft): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($draft['Draft_ID']); ?></td>
                                <td><?php echo htmlspecialchars($draft['League_ID']); ?></td>
                                <td><?php echo htmlspecialchars($draft['DraftDate']); ?></td>
                                <td><?php echo htmlspecialchars($draft['DraftOrder']); ?></td>
                                <td><?php echo htmlspecialchars($draft['DraftStatus']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <!-- User View -->
                <?php if ($error): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>

                <h3>Leagues I Own</h3>
                <?php if (!empty($owned_leagues)): ?>
                    <table border="1">
                        <thead>
                            <tr class="tablebg">
                                <th>League ID</th>
                                <th>League Name</th>
                                <th>Type</th>
                                <th>Max Teams</th>
                                <th>Draft Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($owned_leagues as $league): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($league['League_ID']); ?></td>
                                    <td><?php echo htmlspecialchars($league['LeagueName']); ?></td>
                                    <td><?php echo htmlspecialchars($league['LeagueType']); ?></td>
                                    <td><?php echo htmlspecialchars($league['MaxTeams']); ?></td>
                                    <td><?php echo htmlspecialchars($league['DraftDate']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>You don't currently own any leagues.</p>
                <?php endif; ?>

                <?php if ($show_joined): ?>
                    <br></br>
                    <h3>Leagues I Joined</h3>
                    <?php if (!empty($joined_leagues)): ?>
                        <table border="1">
                            <thead>
                                <tr class="tablebg">
                                    <th>League ID</th>
                                    <th>League Name</th>
                                    <th>Type</th>
                                    <th>Max Teams</th>
                                    <th>Draft Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($joined_leagues as $league): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($league['League_ID']); ?></td>
                                        <td><?php echo htmlspecialchars($league['LeagueName']); ?></td>
                                        <td><?php echo htmlspecialchars($league['LeagueType']); ?></td>
                                        <td><?php echo htmlspecialchars($league['MaxTeams']); ?></td>
                                        <td><?php echo htmlspecialchars($league['DraftDate']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>You haven't joined any leagues yet.</p>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Joined Leagues Toggle Button -->
                <form method="GET" action="league.php">
                    <button type="submit" name="show_joined" value="<?php echo $show_joined ? '0' : '1'; ?>">
                        <?php echo $show_joined ? 'Hide Joined Leagues' : 'Show Joined Leagues'; ?>
                    </button>
                </form>
                <br></br>

                <h3>My Drafts</h3>
                <?php if (!empty($drafts)): ?>
                    <table border="1">
                        <thead>
                            <tr class="tablebg">
                                <th>Draft ID</th>
                                <th>League ID</th>
                                <th>Draft Date</th>
                                <th>Draft Order</th>
                                <th>Draft Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($drafts as $draft): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($draft['Draft_ID']); ?></td>
                                    <td><?php echo htmlspecialchars($draft['League_ID']); ?></td>
                                    <td><?php echo htmlspecialchars($draft['DraftDate']); ?></td>
                                    <td><?php echo htmlspecialchars($draft['DraftOrder']); ?></td>
                                    <td><?php echo htmlspecialchars($draft['DraftStatus']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No draft information is available for your leagues.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
<?php include __DIR__ . '/footer.php'; ?>
