<?php include __DIR__ . '/header.php'; ?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>User Accounts</title>
</head>
<body>
    <div class="navbar">
        <a href="/leagueManager/dashboard.php">Home</a>
        <a href="/leagueManager/team.php">Team</a>
        <a href="/leagueManager/league.php">League</a>
        <a href="/leagueManager/player.php">Player</a>
        <a href="/leagueManager/match.php">Match</a>
        <a href="/leagueManager/logout.php">Logout</a>
    </div>

    <div class="container">
        <h2>User Accounts</h2>

        <!-- Error Message -->
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <!-- User Table -->
        <table border="1">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['User_ID']); ?></td>
                            <td><?php echo htmlspecialchars($user['FullName']); ?></td>
                            <td><?php echo htmlspecialchars($user['Email']); ?></td>
                            <td><?php echo htmlspecialchars($user['Username']); ?></td>
                            <td><?php echo htmlspecialchars($user['Role']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
<?php include __DIR__ . '/footer.php'; ?>
