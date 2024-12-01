<?php include __DIR__ . '/header.php'; ?>
<h1>Signup</h1>
<form action="/leagueManager/signup.php" method="POST">
    <input type="text" name="fullname" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Signup</button>
</form>
<?php if (isset($error)) echo "<p>$error</p>"; ?>
<?php include __DIR__ . '/footer.php'; ?>
