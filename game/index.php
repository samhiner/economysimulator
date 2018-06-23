<!DOCTYPE html>
<?php

include('../logic/verify.php');

$jsMakeString = NULL;

//goes to whichever tab they clicked on
if(isset($_POST['main'])){
	header('location:index');
}
if(isset($_POST['trade'])){
	header('location:commoditiesmarket');
}
if(isset($_POST['stock'])){
	header('location:stockmarket');
}

//this variable signifies that manufacturing has started but no exchange has occured when true.
$makeBool = $timeArray['makeBool'];

//find current date and manufacturing date
$now = strtotime("now");
$makeString = $timeArray['date'];
$makeWhen = (strtotime($makeString));
$timeDiff = $makeWhen - $now;

//manufactures when there is a queued order (makeBool) and the time is up
if ($makeBool == 1) {
	if ($timeDiff <= 0) {
		mysqli_query($connect,"UPDATE game1players SET $product = $product + 1 WHERE id='$userCheckID'");
		mysqli_query($connect,"UPDATE game1time SET makeBool = '0' WHERE id = '$userCheckID'");
		echo "<meta http-equiv='refresh' content='0'>";
	} else {
		//disables manufactturing button and enables timer when manufacturing isn't finished
		$makeScript = '<script>document.getElementById("make").disabled = true;</script>';
		$timerScript = '<script>document.getElementById("timer").disabled = false;</script>';
	}
} else {
	$timerScript = '<script>document.getElementById("make").disabled = false;</script>';
	$makeScript = '<script>document.getElementById("timer").disabled = true;</script>';
}

//start manufacturing process when button clicked
if (isset($_POST['make'])) {
	//make sure they have required materials
	if(($numMaterial1 > 0) && ($numMaterial2 > 0) && ($numMaterial3 > 0)) {
		//make sure they have daily supplies (prevents inspect element exploit on disabled button)
		if (($numSupply1 <= 0) && ($numSupply12 <= 1)) {
			$showSupply1 = $itemList[$playerClass][10];
			$showSupply2 = $itemList[$playerClass][11];
			$message = "Your factories are unable to produce anything until you have more $showSupply1 and/or $showSupply2";
			echo "<script>alert('$message');</script>";
		} else {
			$addedDate = date("Y-m-d H:i:sa",strtotime('2 hour'));
			$finishDate = substr($addedDate,0,-2);
			mysqli_query($connect,"UPDATE game1players SET $material1 = $material1 - 1, $material2 = $material2 - 1, $material3 = $material3 - 1 WHERE id='$userCheckID'");
			mysqli_query($connect,"UPDATE game1time SET date='$finishDate', makeBool='1' WHERE id='$userCheckID'");
			echo "<meta http-equiv='refresh' content='0'>";
		}
	} else {
		$message = "Not enough items";
		echo "<script>alert('$message');</script>";
	}
}

//disable make button if they do not have supplies
if(($numMaterial1 <= 0) && ($numMaterial2 <= 0) && ($numMaterial3 <= 0)) {
	$makeScript = '<script>document.getElementById("make").disabled = true;</script>';
	$errorMessage2 = "You do not have the required materials to execute this trade. You need more X, Y, and Z";
}

if (($numSupply1 <= 0) or ($numSupply2 <= 0)) {
	$makeScript = '<script>document.getElementById("make").disabled = true;</script>';
	$showSupply1 = $itemList[$playerClass][10];
	$showSupply2 = $itemList[$playerClass][11];
	$errorMessage = "Your factories are unable to produce anything until you have more $showSupply1 and/or $showSupply2";
}

?>
<html>
<head>

<title>Economy Simulator</title>

</head>
<body>

<div class="tab">
	<form method='post'>
		<input type='submit' name='main' value="Dashboard">
		<input type='submit' name='trade' value="Commodities Market">
		<input type='submit' name='stock' value="Stock Market">
	</form>
</div> <br>

<?php 
echo "You have $" . $playerData["balance"] . "<br>";
echo "You have " . $playerData[$material1] . " " . $itemList[$playerClass][0] . "<br>";
echo "You have " . $playerData[$material2] . " " . $itemList[$playerClass][1] . "<br>";
echo "You have " . $playerData[$material3] . " " . $itemList[$playerClass][2] . "<br>";
echo "You have " . $playerData[$product] . " " . $itemList[$playerClass][3];
?>

<br><p>*Insert more data.*</p><br><br><br>

<h3>Your Factory:</h3>
Product: 1 <?php echo $itemList[$playerClass][3]; ?> <br>
Materials: 1 <?php echo $itemList[$playerClass][0]; ?>, 1 <?php echo $itemList[$playerClass][1]; ?>, and 1 <?php echo $itemList[$playerClass][2]; ?>

<form method='post'>
	<input type='submit' name='make' value='Manufacture' id='make'>
</form>

<p id="timer"></p>

<?php
if (isset($timerScript)) {
	echo $timerScript;
}

if (isset($makeScript)) {
	echo $makeScript;
}

if (isset($errorMessage)) {
	echo $errorMessage;
}
?>

<script>
//Finds time until item is made.
if (document.getElementById('timer').disabled == false) {
	var makeWhen = "<?php echo $makeString; ?>";
	var makeDate = new Date(makeWhen).getTime();

	var x = setInterval (function() {
		var now = new Date()
		var utc = new Date(now.getTime() + now.getTimezoneOffset() * 60000);
		var timeLeft = makeDate - utc;
		
		var hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		var minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
		var seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
	
		document.getElementById("timer").innerHTML = hours + " Hours, " + minutes + " Minutes, " + seconds + " Seconds";
		
		//refresh page when time is up to ensure PHP kicks in
		if (timeLeft <= 0) {
			location.reload();
		}
	}, 1000);

}
</script>

<h3>Your Supplies:</h3>
You have:<br>

<?php 
echo $playerData[$itemList[$playerClass][8]] . " " . $itemList[$playerClass][10] . "s<br>";
echo $playerData[$itemList[$playerClass][9]] . " " . $itemList[$playerClass][11] . "s"; ?>

<br><br>Go to the Commodities Market to buy more of these items

</body>
</html>