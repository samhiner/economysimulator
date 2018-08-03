<!DOCTYPE html>
<?php
session_start();
$connect = mysqli_connect('localhost', 'root', NULL, 'econ_data');
if (mysqli_connect_errno($connect)) {
	echo 'Failed to connect';
}

//ensure they have logged in (didn't just use verify.php bc that has profile stuff that is created here for new accts)
if (!isset($_SESSION['userData'])){
	header('location: http://localhost/economysimulator/acct/login');
}

$userData = $_SESSION['userData'];
$userCheckID = $userData['id'];
$username = $userData['username'];

//send to game if they have profile
$playerTable = mysqli_query($connect,"SELECT * FROM game1players WHERE id = '$userCheckID'");
$playerExist = mysqli_num_rows($playerTable);
if ($playerExist == 1) {
	header('location: http://localhost/economysimulator/game/index');
}

//when submitted set up MYSQL entries with the class they chose
if (isset($_POST['a'])) {
	$time = time();
	for ($x = 0; $x <= 9; $x++) {
		if ($_POST['a'] == $x) {
			$remShares = $_POST['shares'];
			$shares = 100 - $remShares;
			$seedCap = 1000 + 5000 - ($remShares * 50);

			mysqli_query($connect,"INSERT INTO game1players(id,class,balance) VALUES('$userCheckID','$x','$seedCap')");
			mysqli_query($connect,"INSERT INTO game1time(id) VALUES('$userCheckID')");
			mysqli_query($connect, "ALTER TABLE game1shares ADD $username INT(11) DEFAULT 0");
			mysqli_query($connect, "INSERT INTO game1shares(id,$username) VALUES('$userCheckID', '$shares')");
			mysqli_query($connect, "INSERT INTO game1secorders(item,type,price,amt,timestamp,id) VALUES('$username','0','50,'$remShares','$time','0')");
		}
	}
	header('location: http://localhost/economysimulator/game/index');
}

?>
<html>
<head>
<title>Economy Simulator</title>
</head>
<body>
	<h3>Choose which item you would like to produce.</h3>
	<form method='post'>
		<input type='radio' value='0' name='a'> Bicycles<br>
		<input type='radio' value='1' name='a'> Televisions<br>
		<input type='radio' value='2' name='a'> Riot Shields<br>
		<input type='radio' value='3' name='a'> Smartphones<br>
		<input type='radio' value='4' name='a'> Cars<br>
		<input type='radio' value='5' name='a'> Laptops<br>
		<input type='radio' value='6' name='a'> Smart TVs<br>
		<input type='radio' value='7' name='a'> Dog Tags<br>
		<input type='radio' value='8' name='a'> Electric Shavers<br>
		<input type='radio' value='9' name='a'> Blenders<br><br>
		<h3>Percent Ownership of Your Company:</h3>
		<input type='range' min='0' max='100' value='50' id='shares' name='shares'><br>
		You have chosen to sell <span id='sellShares'>50</span>% of your shares to venture capital companies<br>
		and will recieve $<span id='bonus'>2500</span> at the start of the game.<br><br>
		<input type='submit' value='Submit'>
	</form>
	<script>
		document.getElementById('shares').oninput = function() {
			document.getElementById('sellShares').innerHTML = 100 - this.value;
			document.getElementById('bonus').innerHTML = 5000 - (this.value * 50);
		}
	</script>
</body>
</html>