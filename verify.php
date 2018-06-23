<html>
<body>
Session Verified!
</body>
</html>
<?php

session_start();
$connect = mysqli_connect('localhost', 'root', NULL, 'econ_data');
if (mysqli_connect_errno($connect)) {
	echo 'Failed to connect';
}

//make sure they are logged in or send to login page
$userData = $_SESSION['userData'];
$userCheckID = $userData['id'];
if (!isset($_SESSION['userData'])){
	header('location: login');
}

//ensure acct is linked to profile
$playerTable = mysqli_query($connect,"SELECT * FROM game1players WHERE id = '$userCheckID'");
$playerCount = mysqli_num_rows($playerTable);
if ($playerCount != 1) {
	header("location: home");
}

$playerData = mysqli_fetch_array($playerTable,MYSQLI_ASSOC);
$playerClass = $playerData['class'];

//KEY: 0, 1, 2: Visible Materials. 3: Visible Product. 4, 5, 6: Internal Materials. 7: Internal Product. 8, 9: Internal Supplies. 10, 11: Visible Supplies
$itemList = array (
	array("Glass","Plastic","Aluminum","Bicycle","glass","plastic","alum","bike","shaver","blender","Shaver","Blender"),
	array("Glass","Plastic","Silicon","TV","glass","plastic","sili","tv","car","bike","Car","Bike"),
	array("Glass","Plastic","Steel","Shield","glass","plastic","steel","shield","laptop","phone","Laptop","Phone"),
	array("Glass","Aluminum","Silicon","Phone","glass","alum","sili","phone","tv","smarttv","TV","Smart TV"),
	array("Glass","Aluminum","Steel","Car","glass","alum","steel","car","shaver","blender","Shaver","Blender"),
	array("Glass","Silicon","Steel","Laptop","glass","sili","steel","laptop","tv","smarttv","TV","Smart TV"),
	array("Plastic","Aluminum","Silicon","Smart TV","plastic","alum","sili","smarttv","car","bike","Car","Bike"),
	array("Plastic","Aluminum","Steel","Dog Tags","plastic","alum","steel","dogtags","laptop","phone","Laptop","Phone"),
	array("Plastic","Silicon","Steel","Shaver","plastic","sili","steel","shaver","dogtags","shield","Dog Tags","Shield"),
	array("Aluminum","Silicon","Steel","Blender","alum","sili","steel","blender","dogtags","shield","Dog Tags","Shield")
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

$timeQuery = mysqli_query($connect,"SELECT * FROM game1time WHERE id='$userCheckID'");
$timeArray = mysqli_fetch_array($timeQuery,MYSQLI_ASSOC);

function limitZero($num) {
	if ($num < 0) {
		$num = 0;
	}
	return $num;
}

//remove one of each product for every day since last login if you have enough products
if (($numSupply1 > 0) && ($numSupply2 > 0)) {
	$haveMaterial = $timeArray['haveMaterial'];
	$time = date('Y-m-d H:i:sa');
	$doneTime = substr($time,0,-2);
	//if the fact that you have products is not yet registered register it and start countdown again.
	if ($haveMaterial == '0') {
		mysqli_query($connect,"UPDATE game1time SET lastday='$time', haveMaterial='1' WHERE id='$userCheckID'");
	} else {
		$lastDate = date(strtotime($timeArray['lastday']));
		$lastDiff = time() - $lastDate;
		//60*60*24 = 86400
		$diffDays =  floor($lastDiff / 86400);
		echo $lastDate . ' ' . $lastDiff . ' ' . $diffDays . 'br';
		if ($diffDays >= 1) {
			$leftover = $lastDiff % 86400;
			$newLastDate = date('Y-m-d H:i:sa', time() - $leftover);
			echo time() . ' ' . $leftover . ' ' . $newLastDate . 'br';
			mysqli_query($connect,"UPDATE game1time SET lastday='$newLastDate' WHERE id='$userCheckID'");
			$newSupply1 = limitZero($numSupply1 - $diffDays);
			$newSupply2 = limitZero($numSupply2 - $diffDays);
			echo $newSupply1 . 'br';
			$string = "UPDATE game1players SET $supply1='$newSupply1', $supply2='$newSupply2' WHERE id='$userCheckID'";
			echo $string;
			mysqli_query($connect,$string);
			echo "<meta http-equiv='refresh' content='0'>";
		}
	}
} else {
	mysqli_query($connect,"UPDATE game1time SET haveMaterial='0' WHERE id='userCheckID'");
}

?>