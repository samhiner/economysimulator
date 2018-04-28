<!DOCTYPE html>
<?php
include('verify.php');

//goes to whichever tab they clicked on
if (isset($_POST['main'])) {
	header("location:index.php");
}
if(isset($_POST['trade'])){
	header("location:commoditiesmarket.php");
}
if (isset($_POST['stock'])) {
	header("location:stockmarket.php");
} 

$youQuery = mysqli_query($connect,"SELECT * FROM game1players WHERE id = '$userCheckID'");
$youArray = mysqli_fetch_array($youQuery,MYSQLI_ASSOC);

//fetches how many of item person has
function look($item) {
	global $youArray;
	echo $youArray["$item"];
}

function trade($itemTradeAmt, $item, $type) {
	global $connect;
	global $youArray;
	$youItems = $youArray["$item"];
	$balance = $youArray["balance"];

	//do calculations for amount owed in trade
	if ($type == 1) {
		$buyAmt = $_POST[$itemTradeAmt];
		$newYouItems = $youItems + $buyAmt;
		$cost = '100' * $buyAmt;
		$newBalance = $balance - $cost;
	} else {
		$sellAmt = $_POST[$itemTradeAmt];
		$newYouItems = $youItems - $sellAmt;
		$cost = '100' * $sellAmt;
		$newBalance = $balance + $cost;
	}
	//makes sure trade does not have any create any negative balances and make trade
	if(($newBalance >= 0) && ($newYouItems >= 0)) {
		global $userCheckID;
		mysqli_query($connect,"UPDATE game1players SET $item=$newYouItems WHERE id=$userCheckID");
		mysqli_query($connect,"UPDATE game1players SET balance=$newBalance WHERE id=$userCheckID");
		echo "<meta http-equiv='refresh' content='0'>";
	} else {
		$message = "Not enough items";
		echo "<script>alert('$message');</script>";
	}
}

//run trade function when user submits any trade
if (isset($_POST['glasssellamt'])) {
	trade(glasssellamt, glass, 0);
} elseif (isset($_POST['glassbuyamt'])) {
	trade(glassbuyamt, glass, 1);
} elseif (isset($_POST['plasticsellamt'])) {
	trade(plasticsellamt, plastic, 0);
} elseif (isset($_POST['plasticbuyamt'])) {
	trade(plasticbuyamt, plastic, 1);
} elseif (isset($_POST['alumsellamt'])) {
	trade(alumsellamt, alum, 0);
} elseif (isset($_POST['alumbuyamt'])) {
	trade(alumbuyamt, alum, 1);
} elseif (isset($_POST['silisellamt'])) {
	trade(silisellamt, sili, 0);
} elseif (isset($_POST['silibuyamt'])) {
	trade(silibuyamt, sili, 1);
} elseif (isset($_POST['steelsellamt'])) {
	trade(steelsellamt, steel, 0);
} elseif (isset($_POST['steelbuyamt'])) {
	trade(steelbuyamt, steel, 1);
} 

echo "You have $" . $youArray["balance"];

?>
<html><body>

<div class="tab">
	<form method='post'>
		<input type='submit' name='main' value="Dashboard">
		<input type='submit' name='trade' value="Commodities Market">
		<input type='submit' name='stock' value="Stock Market">
	</form>
</div>

<br><h3>Glass</h3>
<form method='post'>
	<input type="text" value="Amount" name='glassbuyamt'><br>
	<input type="submit" value="Buy Glass">
</form>
<br><form method='post'>
	<input type="text" value="Amount" name='glasssellamt'><br>
	<input type="submit" value="Sell Glass">
</form>
You have <?php look('glass'); ?> Glass. One Glass costs $100.<br><br>

<h3>Plastic</h3>
<form method='post'>
	<input type="text" value="Amount" name='plasticbuyamt'><br>
	<input type="submit" value="Buy Plastic">
</form>
<br><form method='post'>
	<input type="text" value="Amount" name='plasticsellamt'><br>
	<input type="submit" value="Sell Plastic">
</form>
You have <?php look('plastic'); ?> Plastic. One Plastic costs $100.<br><br>

<h3>Aluminum</h3>
<form method='post'>
	<input type="text" value="Amount" name='alumbuyamt'><br>
	<input type="submit" value="Buy Aluminum">
</form>
<br><form method='post'>
	<input type="text" value="Amount" name='alumsellamt'><br>
	<input type="submit" value="Sell Aluminum">
</form>
You have <?php look('alum'); ?> Aluminum. One Aluminum costs $100.<br><br>

<h3>Silicon</h3>
<form method='post'>
	<input type="text" value="Amount" name='silibuyamt'><br>
	<input type="submit" value="Buy Silicon">
</form>
<br><form method='post'>
	<input type="text" value="Amount" name='silisellamt'><br>
	<input type="submit" value="Sell Silicon">
</form>
You have <?php look('sili'); ?> Silicon. One Silicon costs $100.<br><br>

<h3>Steel</h3>
<form method='post'>
	<input type="text" value="Amount" name='steelbuyamt'><br>
	<input type="submit" value="Buy Steel">
</form>
<br><form method='post'>
	<input type="text" value="Amount" name='steelsellamt'><br>
	<input type="submit" value="Sell Steel">
</form>
You have <?php look(steel); ?> Steel. One Steel costs $100.<br><br>
</body></html>