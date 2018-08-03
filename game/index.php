<!DOCTYPE html>
<?php

include('../logic/verify.php');
include('../logic/marketorders.php');

$jsMakeString = NULL;

//find manufacturing date
$makeString = $timeArray['makeDate'];

//looks into manufacturing if an order was queued (isset prevents automatically making products if a connection to database wasn't made)
if ((isset($makeString)) && ($makeString != '2000-01-01 00:00:00')) {
	$now = strtotime("now");
	$makeWhen = strtotime($makeString);
	$timeDiff = $makeWhen - $now;

	//means "if now is greater than makeWhen". if so the manufacturing date has passed and the item can be made
	if ($timeDiff <= 0) {
		mysqli_query($connect,"UPDATE game1players SET $product = $product + 1 WHERE id='$userCheckID'");
		mysqli_query($connect,"UPDATE game1time SET makeDate = '2000-01-01 00:00:00' WHERE id = '$userCheckID'");
		echo "<meta http-equiv='refresh' content='0'>";
	} else {
		//disables manufacturing button and enables timer when manufacturing isn't finished
		$makeScript = '<script>document.getElementById("make").disabled = true;</script>';
		$timerScript = '<script>document.getElementById("makeTimer").style.display = "block";</script>';
	}
} else {
	$makeScript = '<script>document.getElementById("make").disabled = false;</script>';
	$timerScript = '<script>document.getElementById("makeTimer").style.display = "none";</script>';
}

//start manufacturing process when button clicked
if (isset($_POST['make'])) {
	//make sure they have required materials
	if(($numMaterial1 > 0) && ($numMaterial2 > 0) && ($numMaterial3 > 0)) {
		//make sure they have daily supplies (prevents inspect element exploit on disabled button)
		if (($numSupply1 <= 0) && ($numSupply2 <= 0)) {
			$showSupply1 = $itemList[$playerClass][10];
			$showSupply2 = $itemList[$playerClass][11];
			echo "<script>alert('Your factories are unable to produce anything until you have more $showSupply1 and/or $showSupply2');</script>";
		} else {
			$addedDate = date("Y-m-d H:i:s",strtotime('2 hour'));
			mysqli_query($connect,"UPDATE game1players SET $material1 = $material1 - 1, $material2 = $material2 - 1, $material3 = $material3 - 1 WHERE id='$userCheckID'");
			mysqli_query($connect,"UPDATE game1time SET makeDate='$addedDate' WHERE id='$userCheckID'");
			echo "<meta http-equiv='refresh' content='0'>";
		}
	} else {
		echo "<script>alert('Not enough items');</script>";
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



$ordTable = removalCheck('prod') . removalCheck('sec');

if ($ordTable == '') {
	$ordTable = '<tr><td colspan="5" style="text-align: center;">No Orders</td></tr>';
}

?>
<html>
<head>

<title>Dashboard | Economy Simulator</title>

</head>
<body>
<div class='pageBody'>
	<h3 style='margin-top: 0;'>Your Company:</h3>

	<?php 
	echo "You have $" . $playerData["balance"] . "<br>";
	echo "You have " . $playerData[$material1] . " " . $itemList[$playerClass][0] . "<br>";
	echo "You have " . $playerData[$material2] . " " . $itemList[$playerClass][1] . "<br>";
	echo "You have " . $playerData[$material3] . " " . $itemList[$playerClass][2] . "<br>";
	echo "You have " . $playerData[$product] . " " . $itemList[$playerClass][3];
	?><br><br>


	<table border='1'>
		<tr>
			<th>Item</th>
			<th>Price</th>
			<th>Amount</th>
			<th>Type</th>
			<th>Delete</th>
		</tr>
		<?php echo $ordTable; ?>
	</table>

	<br><p>*Insert more data.*</p><br><br><br>

	<h3>Your Factory:</h3>
	Product: 1 <?php echo $itemList[$playerClass][3]; ?> <br>
	Materials: 1 <?php echo $itemList[$playerClass][0]; ?>, 1 <?php echo $itemList[$playerClass][1]; ?>, and 1 <?php echo $itemList[$playerClass][2]; ?>

	<form method='post'>
		<input type='submit' name='make' value='Manufacture' id='make'>
	</form>

	<p id="makeTimer">&infin; Hours, &infin; Minutes, &infin; Seconds</p>

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

	//find difference between end date and now and break down remaining into hrs mins secs
	function countDownClock(finWhen,clock) {
		var finDate = new Date(finWhen).getTime();
		var now = new Date();
		var utc = new Date(now.getTime() + now.getTimezoneOffset() * 60000);
		var timeLeft = finDate - utc;
		
		var hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		var minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
		var seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

		document.getElementById(clock).innerHTML = hours + " Hours, " + minutes + " Minutes, " + seconds + " Seconds";
		
		//refresh page when time is up to ensure PHP kicks in
		if (timeLeft <= 0) {
			location.reload();
		}
	}

	//Finds time until item is made.
	if (document.getElementById('makeTimer').style.display == 'block') {
		//turn the manufacturing time into a js time and run countdown function
		var makeDate = "<?php echo $makeString; ?>";
		//call it once the first time to prevent 1 sec delay from setinterval
		countDownClock(makeDate,'makeTimer')
		var makeTimerScript = setInterval( function() {countDownClock(makeDate,'makeTimer')}, 1000);
	}

	function startTimer(decayDate,detailsBox,clock) {
		//check if false bc this check runs before clicking the box changes "open" to true
		if (document.getElementById(detailsBox).open == false) {
			decayDate *= 1000;
			decayDate += 14400000;

			countDownClock(decayDate,clock)
			var decayTimerScript = setInterval( function() {countDownClock(decayDate,clock)}, 1000);
		}
	}

	</script>

	<h3>Your Supplies:</h3>
	You have:<br>


	<details id='prod1' onclick='startTimer(<?php 
	if (isset($globalDecay->newDecayDate[1])) {
		echo $globalDecay->newDecayDate[1];
	} else {
		echo NULL;
	}
	?>,"prod1","prod1CountDown")'>
		<summary><?php echo $numSupply1 . " " . $itemList[$playerClass][10] . "<br>"; ?></summary>
		<p>One product will be used up in </p>
		<p id='prod1CountDown'>&infin; Hours, &infin; Minutes, &infin; Seconds</p>
	</details>

	<details id='prod2' onclick='startTimer(<?php 
	if (isset($globalDecay->newDecayDate[2])) {
		echo $globalDecay->newDecayDate[2];
	} else {
		echo NULL;
	}
	?>,"prod2","prod2CountDown")'>
		<summary><?php echo $numSupply2 . " " . $itemList[$playerClass][11]; ?></summary>
		<p>One product will be used up in </p>
		<p id='prod2CountDown'>&infin; Hours, &infin; Minutes, &infin; Seconds</p>
	</details>


	<br><br>Go to the Commodities Market to buy more of these items
</div>
</body>
</html>