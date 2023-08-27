<?php
session_start();
$host = "localhost";
$user = "user";
$pass = "password";
$db = "tetris";
if (isset($_POST["username"])) {
	$conn = mysqli_connect($host, $user, $pass, $db);
	$display = 0;
	if ($_POST["display"] == "yes") {
		$display = 1;
	}
	if ($conn and mysqli_query($conn, "INSERT INTO Users VALUES ('".$_POST["username"]."', '".$_POST["firstName"]."', '".$_POST["lastName"]."', '".$_POST["password"]."', ".$display.");")) {
		$_SESSION["username"] = $_POST["username"];
		$_SESSION["display-scores"] = $display;
	}
	mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Tetris</title>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<ul class="navbar">
			<li name="home"><a href="index.php">Home</a></li>
			<li name="leaderboard" id="right-side"><a href="leaderboard.php">Leaderboard</a></li>
			<li name="tetris" id="right-side"><a href="tetris.php">Play Tetris</a></li>
		</ul>
		<div class="main">
			<?php
				if (isset($_POST["loginUser"])) {
					$conn = mysqli_connect($host, $user, $pass, $db);
					if ($conn) {
						$users = mysqli_query($conn, "SELECT * FROM Users;");
						if (mysqli_num_rows($users) > 0) {
							$found = false;
							while ($data = mysqli_fetch_assoc($users)) {
								if ($data["UserName"] == $_POST["loginUser"]) {
									$found = true;
									if ($data["Password"] == $_POST["loginPass"]) {
										$_SESSION["username"] = $data["UserName"];
										$_SESSION["display-scores"] = $data["Display"];
									} else {
										echo "<style> p#incorrect-password {display:block;} </style>";
									}
									break;
								}
							}
							if (!$found) {
								echo "<style> p#user-not-found {display:block;} </style>";
							}
						}
					}
					mysqli_close($conn);
				}
				
				if (isset($_SESSION["username"])) {
					echo "<style> div#login {display:none;} </style>";
				}
				else {
					echo "<style> div#home {display:none;} </style>";
				}
			?>
			<div id="home">
				<h1 style="padding-top:80px;">Welcome to Tetris</h1>
				<a href="tetris.php" style="color:blue;text-decoration:none;">Click here to play</a><br><br><br>
			</div>
			<div id="login">
				<h1>Sign in</h1>
				<form action="index.php" method="POST">
					<label for="loginUser">Username:</label><br>
					<input type="text" name="loginUser" id="loginUser" placeholder="username"><br><br>
					<label for="loginPass">Password:</label><br>
					<input type="password" name="loginPass" id="loginPass" placeholder="password"><br>
					<p>Don't have a user account? <a href="registration.php" style="color:blue;text-decoration:none;">Register now</a></p>
					<input type="submit" id="submit">
					<p class="output" id="incorrect-password">Incorrect password.</p>
					<p class="output" id="user-not-found">User not found.</p>
				</form>
			</div>
		</div>
	</body>
</html>