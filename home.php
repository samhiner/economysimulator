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
	header("location: index.php");
}

//send to game if they have profile
$userData = $_SESSION['userData'];
$userCheckID = $userData['id'];
$playerTable = mysqli_query($connect,"SELECT * FROM game1players WHERE id = '$userCheckID'");
$playerExist = mysqli_num_rows($playerTable);
if($playerExist == 1) {
	header("location: dashboard.php");
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {	
	if ($_POST['a'] == 'bike') {
		mysqli_query($connect,"INSERT INTO game1players(id,class,balance) VALUES('$userCheckID','0','2000')");
	} elseif ($_POST['a'] == 'tv') {
		mysqli_query($connect,"INSERT INTO game1players(id,class,balance) VALUES('$userCheckID','1','2000')");
	} elseif ($_POST['a'] == 'shield') {
		mysqli_query($connect,"INSERT INTO game1players(id,class,balance) VALUES('$userCheckID','2','2000')");
	} elseif ($_POST['a'] == 'phone') {
		mysqli_query($connect,"INSERT INTO game1players(id,class,balance) VALUES('$userCheckID','3','2000')");
	} elseif ($_POST['a'] == 'car') {
		mysqli_query($connect,"INSERT INTO game1players(id,class,balance) VALUES('$userCheckID','4','2000')");
	} elseif ($_POST['a'] == 'laptop') {
		mysqli_query($connect,"INSERT INTO game1players(id,class,balance) VALUES('$userCheckID','5','2000')");
	} elseif ($_POST['a'] == 'smarttv') {
		mysqli_query($connect,"INSERT INTO game1players(id,class,balance) VALUES('$userCheckID','6','2000')");
	} elseif ($_POST['a'] == 'dogtags') {
		mysqli_query($connect,"INSERT INTO game1players(id,class,balance) VALUES('$userCheckID','7','2000')");
	} elseif ($_POST['a'] == 'shaver') {
		mysqli_query($connect,"INSERT INTO game1players(id,class,balance) VALUES('$userCheckID','8','2000')");
	} elseif ($_POST['a'] == 'blender') {
		mysqli_query($connect,"INSERT INTO game1players(id,class,balance) VALUES('$userCheckID','9','2000')");
	}
	header("location:dashboard.php");
}

?>
<html><body>
<h3>Choose which item you would like to produce.</h3>
<form method="post">
	<input type="radio" value="bike" name="a"> Bicycles <br>
	<input type="radio" value="tv" name="a"> Televisions <br>
	<input type="radio" value="shield" name="a"> Riot Shields <br>
	<input type="radio" value="phone" name="a"> Smartphones <br>
	<input type="radio" value="car" name="a"> Cars <br>
	<input type="radio" value="laptop" name="a"> Laptops <br>
	<input type="radio" value="smarttv" name="a"> Smart TVs <br>
	<input type="radio" value="dogtags" name="a"> Dog Tags <br>
	<input type="radio" value="shaver" name="a"> Electric Shavers <br>
	<input type="radio" value="blender" name="a"> Blenders <br> <br>
	<input type="submit" value="Submit">
</form>
</body></html>