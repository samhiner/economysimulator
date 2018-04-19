<!DOCTYPE html>
<?php

include('verify.php');

$jsMakeString = NULL;

//goes to whichever tab they clicked on
if(isset($_POST['main'])){
	header("location:dashboard.php");
}
if(isset($_POST['trade'])){
	header("location:commoditiesmarket.php");
}
if(isset($_POST['stock'])){
	header("location:stockmarket.php");
}

function classItems($m1,$m2,$m3,$p) {
	$GLOBALS['material1'] = $m1;
	$GLOBALS['material2'] = $m2;
	$GLOBALS['material3'] = $m3;
	$GLOBALS['product'] = $p;
}

$playerData = mysqli_fetch_array($playerTable,MYSQLI_ASSOC);
$playerClass = $playerData['class'];

//gets materials + products based on user's class
$itemList = array
(
	array("Glass","Plastic","Aluminum","Bicycle","glass","plastic","alum","bike"),
	array("Glass","Plastic","Silicon","TV","glass","plastic","sili","tv"),
	array("Glass","Plastic","Steel","Shield","glass","plastic","steel","shield"),
	array("Glass","Aluminum","Silicon","Phone","glass","alum","sili","phone"),
	array("Glass","Aluminum","Steel","Car","glass","alum","steel","car"),
	array("Glass","Silicon","Steel","Laptop","glass","sili","steel","laptop"),
	array("Plastic","Aluminum","Silicon","Smart TV","plastic","alum","sili","smarttv"),
	array("Plastic","Aluminum","Steel","Dog Tags","plastic","alum","steel","dogtags"),
	array("Plastic","Silicon","Steel","Shaver","plastic","sili","steel","shaver"),
	array("Aluminum","Silicon","Steel","Blender","alum","sili","steel","blender")
);

classItems($itemList[$playerClass][4],$itemList[$playerClass][5],$itemList[$playerClass][6],$itemList[$playerClass][7]);

//

$timeQuery = mysqli_query($connect,"SELECT * FROM game1time WHERE id='$userCheckID'");
$timeArray = mysqli_fetch_array($timeQuery,MYSQLI_ASSOC);

//this variable signifies that manufacturing has started but no exchange has occured when true.
$makeBool = $timeArray['makeBool'];

//find current date and manufacturing date
$now = date_create();
$makeString = $timeArray['date'];
$makeWhen = date_create($makeString);
$timeDiff = date_diff($makeWhen, $now);
//^MAKE this go from an object to a number 

//if there is a queued exchange (makeBool = 1) and the manufacturing has finished, execute the exchange and set makeBool to false
if ($makeBool == 1) {
	echo "hello";
	if ($timeDiff <= 0) {
		mysqli_query($connect,"UPDATE game1players SET $material1 = $material1 - 1, $material2 = $material2 - 1, $material3 = $material3 - 1, $product = $product + 1 WHERE id='$userCheckID'");
		mysqli_query($connect,"UPDATE game1time SET makeBool = '0' WHERE id = '$userCheckID'");
		$makeScript = '<script>document.getElementById("make").disabled = false;</script>';
		echo "goodbye";
	} else {
		//disables manufactturing button and enables timer when manufacturing isn't finished
		$jsMakeString = $timeArray['date'];
		$makeScript = '<script>document.getElementById("make").disabled = true;</script>';
		$timerScript = '<script>document.getElementById("timer").disabled = false;</script>';
		echo "yo";
	}
} else {
	$makeScript = '<script>document.getElementById("timer").disabled = true;</script>';
	echo "wassup";
}

//start manufacturing process when button clicked
if (isset($_POST['make'])) {
	$numMaterial1 = $playerData[$material1];
	$numMaterial2 = $playerData[$material2];
	$numMaterial3 = $playerData[$material3];
	if(($numMaterial1 > 0) && ($numMaterial2 > 0) && ($numMaterial3 > 0)) {
		$addedDate = date("Y-m-d H:i:sa",strtotime('2 hour'));
		$finishDate = substr($addedDate,0,-2);
		mysqli_query($connect,"UPDATE game1time SET date='$finishDate', makeBool='1' WHERE id='$userCheckID'");
		echo "<meta http-equiv='refresh' content='0'>";
	} else {
		$message = "Not enough items";
		echo "<script>alert('$message');</script>";
	}
}

?><html><body>

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

<br><p>Insert more data.</p><br><br><br>

<h3>Your Factory:</h3><br>
Product: 1 <?php echo $itemList[$playerClass][3]; ?> <br>
Materials: 1 <?php echo $itemList[$playerClass][0]; ?>, 1 <?php echo $itemList[$playerClass][1]; ?>, and 1 <?php echo $itemList[$playerClass][3]; ?>

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
?>
<script>
//Finds time until item is made.
if (document.getElementById('timer').disabled = false) {
	var makeWhen = "<?php echo $jsMakeString; ?>";
	var makeDate = new Date(makeWhen).getTime();

	var x = setInterval (function() {
		var now = new Date().getTime();
		var timeLeft = makeDate - now;
	
		var hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		var minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
		var seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
	
		document.getElementById("timer").innerHTML = hours + " Hours, " + minutes + " Minutes, " + seconds + " Seconds";
		
		
	}, 1000);

	//refresh page when time is up to ensure PHP kicks in
	if (timeLeft <= 0) {
		echo "<meta http-equiv='refresh' content='0'>";
	}
}
</script>
</body></html>