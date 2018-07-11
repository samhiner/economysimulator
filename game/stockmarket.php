<!DOCTYPE html>
<?php

include('../logic/verify.php');
include('../logic/marketorders.php');

$myOrder = new orderManager('bike');


//place order when button is pressed
if (isset($_POST['bid'])) {
	if ($playerData['balance'] >= $_POST['amt'] * $_POST['price']) {
		$myOrder->amt = $_POST['amt'];
		$myOrder->price = $_POST['price'];
		$myOrder->timestamp = time();
		$myOrder->id = $userCheckID;
		$myOrder->placeOrder('1');
	} else {
		echo 'need more money';
	}
}

if (isset($_POST['ask'])) {
	if ($playerData[$myOrder->item] >= $_POST['amt']) {
		$myOrder->amt = $_POST['amt'];
		$myOrder->price = $_POST['price'];
		$myOrder->timestamp = time();
		$myOrder->id = $userCheckID;
		$myOrder->placeOrder('0');
	} else {
		echo 'need more items';
	}
}

?>
<html>
<head>

<title>Economy Simulator</title>

</head>

<!--TAKE ME WITH YOU-->
<style>
.bid {
	background-color: green;
}
.ask {
	background-color: red;
}
</style>
<!---->

<body>


<div class='pageBody'>
	<p style='margin-top: 0px;'>Buying and selling stocks</p>
	<hr><br>

	<!--Fake Market-->


	<form method='post'>
		<input type='text' name='price' placeholder='Price'>
		<input type='text' name='amt' id='amt' placeholder='Amount'><br>
		<button type='submit' name='bid' value='bike'>Bid</button>
		<button type='submit' name='ask' value='bike'>Ask</button>
	</form>

	<!--New Fancy Table-->


	<br><table border='1'>
		<tr>
			<th>Price</th>
			<th>Amount</th>
		</tr>
		<?php echo $myOrder->displayOrders(); ?>
	</table><br>

	<!-- Stats -->

	<?php echo $playerData['bike']; ?> bikes<br>
	<?php echo $playerData['balance']; ?> moneys

	<!---->
</div>
</body>
</html>