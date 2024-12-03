<?php include __DIR__ . '/header.php'; ?>
<head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="style.css">
	<title></title>
</head>
<body>
    <div class="container">
        <div class="signup">
            <h2>DataVision Sign Up</h2>
            <form id="signupform" action="/leagueManager/signup.php" method="POST">
                <div class="form">
                    <label for="fullname">Full Name</label>
                    <input type="fullname" id="fullname" name="fullname" required>
                </div>
                <div class="form">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form">
                    <label for="username">Username</label>
                    <input type="username" id="username" name="username" required>
                </div>
                <div class="form">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Sign Up</button>
                <br></br>
                <p>Have an account?</p>
                <a href="/leagueManager/login.php" style="color: #fff;"><p>Login here!</p>
            </form>
        </div>
    </div>
</body>

<?php if (isset($error)) echo "<p>$error</p>"; ?>
<?php include __DIR__ . '/footer.php'; ?>
