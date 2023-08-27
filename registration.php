<?php
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Register to play!</title>
		<link rel="stylesheet" href="style.css">
		<script>
			function checkMatch() {
				if (document.getElementById("password").value == document.getElementById("confPassword").value) {
					return true;
				} else
				{
					document.getElementById("passwords-not-match").style.display = "block";
					return false;
				}
			}
		</script>
	</head>
	<body>
		<ul class="navbar">
			<li name="home"><a href="index.php">Home</a></li>
			<li name="leaderboard" id="right-side"><a href="leaderboard.php">Leaderboard</a></li>
			<li name="tetris" id="right-side"><a href="tetris.php">Play Tetris</a></li>
		</ul>
		<div class="main">
			<div id="registration">
				<h1>Sign up</h1>
				<form method="POST" action="index.php" onsubmit="return checkMatch();">
					<label for="firstName">First name:</label><br>
					<input type="text" name="firstName" id="firstName" placeholder="First name"><br>
					<label for="lastName">Last name:</label><br>
					<input type="text" name="lastName" id="lastName" placeholder="Last name"><br><br>
					<label for="username">Username:</label><br>
					<input type="text" name="username" id="username" placeholder="Username" required><br><br>
					<label for="password">Password:</label><br>
					<input type="password" name="password" id="password" placeholder="Password" required><br>
					<label for="password">Confirm password:</label><br>
					<input type="password" id="confPassword" placeholder="Confirm password" required><br><br>
					<label for="display">Display Scores on leaderboard?</label><br>
					<input type="radio" name="display" id="yes" value="yes" checked>
					<label for="yes">Yes</label><br>
					<input type="radio" name="display" id="no" value="no">
					<label for="no">No</label><br><br>
					<input type="submit" id="submit">
					<p class="output" id="passwords-not-match">Passwords don't match.</p>
				</form>
			</div>
		</div>
	</body>
</html>