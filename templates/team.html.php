<?php include __DIR__ . '/header.php'; ?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>My Teams</title>
</head>
<body>
    <div class="navbar">
        <div class="left">
            <a href="/leagueManager/dashboard.php">Home</a>
        </div>
        <div class="center">
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
        <div class="team">
            <br></br>
            <h2>My Teams</h2>

            <!-- Sorting Buttons -->
            <div class="sorting-buttons">
                <form action="team.php" method="GET" style="display: inline-block;">
                    <input type="hidden" name="sort_by" value="Team_ID">
                    <input type="hidden" name="sort_order" value="<?php echo $sort_column === 'Team_ID' && $sort_order === 'ASC' ? 'desc' : 'asc'; ?>">
                    <button type="submit">Sort by Team ID <?php echo $sort_column === 'Team_ID' ? ($sort_order === 'ASC' ? '↑' : '↓') : ''; ?></button>
                </form>

                <form action="team.php" method="GET" style="display: inline-block;">
                    <input type="hidden" name="sort_by" value="TotalPoints">
                    <input type="hidden" name="sort_order" value="<?php echo $sort_column === 'TotalPoints' && $sort_order === 'ASC' ? 'desc' : 'asc'; ?>">
                    <button type="submit">Sort by Total Points <?php echo $sort_column === 'TotalPoints' ? ($sort_order === 'ASC' ? '↑' : '↓') : ''; ?></button>
                </form>
            </div>

            <!-- Team Table -->
            <table border="1">
                <thead>
                    <tr>
                        <th>Team ID</th>
                        <th>Team Name</th>
                        <th>Total Points</th>
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
                                <td><?php echo htmlspecialchars($team['TotalPoints']); ?></td>
                                <td><?php echo htmlspecialchars($team['League_ID']); ?></td>
                                <td><?php echo htmlspecialchars($team['Status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No teams found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <br></br>

            <!-- Add Team Form -->
            <h3>Add a Team</h3>
            <form action="team.php" method="POST">
                <div class="inputs">
                    <input type="text" name="team_name" placeholder="Team Name" required>
                    <input type="number" name="league_id" placeholder="League ID" required>
                </div>
                <button type="submit">Add Team</button>
            </form>

            <!-- Error and Success Messages -->
            <?php if ($teamerror): ?>
                <p class="error"><?php echo htmlspecialchars($teamerror); ?></p>
            <?php endif; ?>
            <?php if ($teamsuccess): ?>
                <p class="success"><?php echo htmlspecialchars($teamsuccess); ?></p>
            <?php endif; ?>
            <br></br>
            
            <!-- Waiver Table -->
            <h2>My Waivers</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>Waiver ID</th>
                        <th>Team ID</th>
                        <th>Player ID</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th>Pickup Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($waivers)): ?>
                        <?php foreach ($waivers as $waiver): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($waiver['Waiver_ID']); ?></td>
                                <td><?php echo htmlspecialchars($waiver['Team_ID']); ?></td>
                                <td><?php echo htmlspecialchars($waiver['Player_ID']); ?></td>
                                <td><?php echo htmlspecialchars($waiver['WaiverOrder']); ?></td>
                                <td><?php echo htmlspecialchars($waiver['WaiverStatus']); ?></td>
                                <td><?php echo htmlspecialchars($waiver['WaiverPickupDate']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No waivers found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <br></br>

            <!-- Trade Table -->
            <h2>My Trades</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>Trade ID</th>
                        <th>Team 1 ID</th>
                        <th>Team 2 ID</th>
                        <th>Traded Player 1 ID</th>
                        <th>Traded Player 2 ID</th>
                        <th>Trade Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($trades)): ?>
                        <?php foreach ($trades as $trade): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($trade['Trade_ID']); ?></td>
                                <td><?php echo htmlspecialchars($trade['Team1_ID']); ?></td>
                                <td><?php echo htmlspecialchars($trade['Team2_ID']); ?></td>
                                <td><?php echo htmlspecialchars($trade['TradedPlayer1_ID']); ?></td>
                                <td><?php echo htmlspecialchars($trade['TradedPlayer2_ID']); ?></td>
                                <td><?php echo htmlspecialchars($trade['TradeDate']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No trades found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <br></br>

            <!-- Add Trade Form -->
            <h3>Add a Trade</h3>
            <form action="team.php" method="POST">
                <div class="inputs">
                    <input type="hidden" name="add_trade" value="1">
                    <input type="number" name="team1_id" placeholder="Team 1 ID" required>
                    <input type="number" name="team2_id" placeholder="Team 2 ID" required>
                    <input type="number" name="player1_id" placeholder="Player 1 ID" required>
                    <input type="number" name="player2_id" placeholder="Player 2 ID" required>
                    <input type="date" name="trade_date" placeholder="Trade Date" required>
                </div>
                <button type="submit">Add Trade</button>
            </form>
            <!-- Error and Success Messages -->
            <?php if ($tradeerror): ?>
                <p class="error"><?php echo htmlspecialchars($tradeerror); ?></p>
            <?php endif; ?>
            <?php if ($tradesuccess): ?>
                <p class="success"><?php echo htmlspecialchars($tradesuccess); ?></p>
            <?php endif; ?>
            <br></br>
            <br></br>
            <br></br>
        </div>
    </div>
</body>
<?php include __DIR__ . '/footer.php'; ?>