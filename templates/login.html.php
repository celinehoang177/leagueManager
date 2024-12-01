<?php include __DIR__ . '/header.php'; ?>
<h1>Login</h1>
<form action="/leagueManager/login.php" method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>

<div class="signup-container">
    <p>Don't have an account?</p>
    <a href="/leagueManager/signup.php"><button type="button">Signup</button></a>
</div>

<?php if (isset($error)) echo "<p>$error</p>"; ?>
<?php include __DIR__ . '/footer.php'; ?>
