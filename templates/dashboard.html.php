<?php include __DIR__ . '/header.php'; ?>
<h1>Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</h1>
<a href="/leagueManager/logout.php">Logout</a>
<?php include __DIR__ . '/footer.php'; ?>
