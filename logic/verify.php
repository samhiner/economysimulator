<html>
<head>
	<link rel='stylesheet' type='text/css' href='http://localhost/economysimulator/styling/gamepages.css'>
</head>
<body>
<div class='tabBackground'>
	<div class='tabHolder'>
		<a href='http://localhost/economysimulator/game/index'>Dashboard</a>
		<a href='http://localhost/economysimulator/game/commoditiesmarket'>Commodities Market</a>
		<a href='http://localhost/economysimulator/game/stockmarket'>Stock Market</a>
	</div>
</div>
</body>
</html>
<?php

session_start();

function query($query) {
	$connect = mysqli_connect('localhost', 'root', NULL, 'econ_data');
	if (mysqli_connect_errno($connect)) {
		echo 'Failed to connect';
	}
	return mysqli_query($connect,$query);
}

//make sure they are logged in or send to login page
$userData = $_SESSION['userData'];
$userCheckID = $userData['id'];
if (!isset($_SESSION['userData'])){
	header('location: http://localhost/economysimulator/acct/login');
}

//ensure acct is linked to profile
$playerTable = query("SELECT * FROM game1players WHERE id = '$userCheckID'");
$playerCount = mysqli_num_rows($playerTable);
if ($playerCount != 1) {
	header("location: http://localhost/economysimulator/acct/home"); 
}

$playerData = mysqli_fetch_array($playerTable,MYSQLI_ASSOC);
$playerData = array_merge($playerData, mysqli_fetch_array(query("SELECT * FROM game1shares WHERE id='$userCheckID'"),MYSQLI_ASSOC));

/*
$wonGame = mysqli_fetch_array(query("SELECT * FROM gamewon"),MYSQLI_NUM)[0];

if ($wonGame) {
	$end = True;
} else if ($playerData['balance'] >= 10000) {
	query("UPDATE gamewon set won=1");
	$end = True;
} else {
	$end = False;
}

if ($end) {
	echo "<script>alert('The game is now over, you can continue to play to test it out if you would like.');</script>";
}*/

$playerClass = $playerData['class'];

//KEY: 0, 1, 2: Visible Materials. 3: Visible Product. 4, 5, 6: Internal Materials. 7: Internal Product. 8, 9: Internal Supplies. 10, 11: Visible Supplies
$itemList = array (
	array('Glass','Plastic','Aluminum','Bicycle','glass','plastic','alum','bike','shaver','blender','Shaver','Blender'),
	array('Glass','Plastic','Silicon','TV','glass','plastic','sili','tv','car','bike','Car','Bike'),
	array('Glass','Plastic','Steel','Shield','glass','plastic','steel','shield','laptop','phone','Laptop','Phone'),
	array('Glass','Aluminum','Silicon','Phone','glass','alum','sili','phone','tv','smarttv','TV','Smart TV'),
	array('Glass','Aluminum','Steel','Car','glass','alum','steel','car','shaver','blender','Shaver','Blender'),
	array('Glass','Silicon','Steel','Laptop','glass','sili','steel','laptop','tv','smarttv','TV','Smart TV'),
	array('Plastic','Aluminum','Silicon','Smart TV','plastic','alum','sili','smarttv','car','bike','Car','Bike'),
	array('Plastic','Aluminum','Steel','Dog Tags','plastic','alum','steel','dogtags','laptop','phone','Laptop','Phone'),
	array('Plastic','Silicon','Steel','Shaver','plastic','sili','steel','shaver','dogtags','shield','Dog Tags','Shield'),
	array('Aluminum','Silicon','Steel','Blender','alum','sili','steel','blender','dogtags','shield','Dog Tags','Shield')
);

$material1 = $itemList[$playerClass][4];
$material2 = $itemList[$playerClass][5];
$material3 = $itemList[$playerClass][6];
$product = $itemList[$playerClass][7];
$supply1 = $itemList[$playerClass][8];
$supply2 = $itemList[$playerClass][9];

//finds amt of commonly used items from array
$numMaterial1 = $playerData[$material1];
$numMaterial2 = $playerData[$material2];
$numMaterial3 = $playerData[$material3];
$numSupply1 = $playerData[$itemList[$playerClass][8]];
$numSupply2 = $playerData[$itemList[$playerClass][9]];

$timeQuery = query("SELECT * FROM game1time WHERE id='$userCheckID'");
$timeArray = mysqli_fetch_array($timeQuery,MYSQLI_ASSOC);

function limitZero($num) {
	if ($num < 0) {
		$num = 0;
	}
	return $num;
}

include('productdecay.php');

?>
