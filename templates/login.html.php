<?php include __DIR__ . '/header.php'; ?>
<head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="style.css">
	<title></title>
</head>
<body>
    <div class="container">
        <div class="login">
            <h2>Login</h2>
            <form id="loginform" action="/leagueManager/login.php" method="POST">
                <div class="form">
                    <label for="username">Username</label>
                    <input type="username" id="username" name="username" required>
                </div>
                <div class="form">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Login</button>
                <br></br>
                <p>Don't have an account?</p>
                <a href="/leagueManager/signup.php" style="color: #fff;"><p>Sign up here!</p>
            </form>
        </div>
    </div>
</body>

<?php if (isset($error)) echo "<p>$error</p>"; ?>
<?php include __DIR__ . '/footer.php'; ?>
