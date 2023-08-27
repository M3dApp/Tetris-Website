<?php
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Leaderboard</title>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<ul class="navbar">
			<li name="home"><a href="index.php">Home</a></li>
			<li name="leaderboard" id="right-side"><a href="leaderboard.php">Leaderboard</a></li>
			<li name="tetris" id="right-side"><a href="tetris.php">Play Tetris</a></li>
		</ul>
		<div class="main">
			<div id="leaderboard">
				<?php
					$host = "localhost";
					$user = "user";
					$pass = "password";
					$db = "tetris";
					$conn = mysqli_connect($host, $user, $pass, $db);
					if ($conn) {
						if (isset($_POST["score"]) and isset($_SESSION["username"]) and $_SESSION["display-scores"] == 1) {
							mysqli_query($conn, "INSERT INTO Scores VALUES (NULL, \"".$_SESSION["username"]."\", ".$_POST["score"].");");
						}
						$lb = mysqli_query($conn, "SELECT * FROM Scores;");
					?>
					<br><table>
						<tr>
							<th id="table-header">Username</th>
							<th id="table-header">Score</th>
						</tr>
						<?php
							if (mysqli_num_rows($lb) > 0) {
								while ($data = mysqli_fetch_assoc($lb)) {
						?>
									<tr>
										<td><?php echo $data["Username"]?></td>
										<td><?php echo $data["Score"]?></td>
									</tr>
						<?php
								}
							} else {
					?>
					<tr>
						<td colspan="2">No data found.</td>
					</tr>
					<?php
						}
					}
					mysqli_close($conn);
					?>
				</table>
			</div>
		</div>
	</body>
</html>