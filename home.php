<!DOCTYPE html>
<?php
session_start();
$connect = mysqli_connect('localhost', 'root', 'root', 'econ_data');
if(mysqli_connect_errno($connect)) {
	echo 'Failed to connect';
}

//ensure they have acct (didn't just use verify.php bc that has profile stuff thats defined here)
$userData = $_SESSION['userData'];
$userCheckID = $userData['id'];
if(!isset($_SESSION['userData'])){
	header("location: login.php");
}

//send to game if they have profile
$userData = $_SESSION['userData'];
$userCheckID = $userData['id'];
$playerTable = mysqli_query($connect,"SELECT * FROM game1players WHERE id = '$userCheckID'");
$playerExist = mysqli_num_rows($playerTable);
if($playerExist == 1) {
	header("location: index.php");
}

//when submitted set up MYSQL entries with the class they chose
if ($_SERVER['REQUEST_METHOD'] == 'POST') {	
	$time = date('Y-m-d h:i:sa');
	for ($x = 0; $x <= 9; $x++) {
		if ($_POST['a'] == $x) {
			mysqli_query($connect,"INSERT INTO game1players(id,class,balance) VALUES('$userCheckID','$x','2000')");
			mysqli_query($connect,"INSERT INTO game1time(id,lastday) VALUES('$userCheckID','$time')");
		}
	}
	header("location:index.php");
}


?>
<html><body>
<h3>Choose which item you would like to produce.</h3>
<form method="post">
	<input type="radio" value="0" name="a"> Bicycles <br>
	<input type="radio" value="1" name="a"> Televisions <br>
	<input type="radio" value="2" name="a"> Riot Shields <br>
	<input type="radio" value="3" name="a"> Smartphones <br>
	<input type="radio" value="4" name="a"> Cars <br>
	<input type="radio" value="5" name="a"> Laptops <br>
	<input type="radio" value="6" name="a"> Smart TVs <br>
	<input type="radio" value="7" name="a"> Dog Tags <br>
	<input type="radio" value="8" name="a"> Electric Shavers <br>
	<input type="radio" value="9" name="a"> Blenders <br> <br>
	<input type="submit" value="Submit">
</form>
</body></html>