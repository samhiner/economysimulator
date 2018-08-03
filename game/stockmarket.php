<!DOCTYPE html>
<?php

include('../logic/verify.php');
include('../logic/marketorders.php');

$userStocks = mysqli_fetch_array(query("SELECT * FROM game1shares WHERE id='$userCheckID'"),MYSQLI_ASSOC);

$companyInfo = query("SELECT column_name FROM information_schema.columns WHERE table_schema='econ_data' AND table_name='game1shares' AND column_name != 'id'");

//look to see if you changed tab or recently submitted an order to change focus to. if none then default to glass
//TODO: make this a ternary expression
if (isset($_POST['itemLookup'])) {
	$focusedItem = $_POST['itemLookup'];
} elseif (isset($_POST['bid'])) {
	$focusedItem = $_POST['bid'];
} elseif (isset($_POST['ask'])) {
	$focusedItem = $_POST['ask'];
} elseif (isset($_POST['buyMarket'])) {
	$focusedItem = $_POST['buyMarket'];
} elseif (isset($_POST['sellMarket'])) {
	$focusedItem = $_POST['sellMarket'];
} else {
	$focusedItem = mysqli_fetch_array(query("SELECT column_name FROM information_schema.columns WHERE table_schema='econ_data' AND table_name='game1shares' AND column_name != 'id'"),MYSQLI_NUM)[0];
	echo $focusedItem;
}

$myOrder = new orderManager($focusedItem);

orderCheck('sec',$myOrder);

echo "You have $" . $playerData['balance'];

?>
<html>
<head>
	<style>
		.leftCol {
			float: left;
		}
		.rightCol {
			margin-left: 5%;
			float: left;
		}
	</style>

	<title>Stock Market | Economy Simulator</title>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.js"></script>
	<link rel='stylesheet' type='text/css' href='../styling/marketpages.css'>
</head>
<body>
<div class='pageBody'>
	<h4 style='margin-top: 0px;'>Choose which item you would like to trade here.</h4>
	<form method='post'>
		<select name='itemLookup' id='itemSelector'>
			<option selected disabled hidden>Choose a Stock</option>
			<?php
			while ($row = mysqli_fetch_array($companyInfo,MYSQLI_NUM)):
			?>
				<?php $choice = "'" . $row[0] . "'"; ?>
				<option id=<?php echo $choice; ?> value=<?php echo $choice; ?>><?php echo $row[0]; ?></option>
			<?php endwhile; ?>
		</select><br>
		<input type='submit'>
	</form>
	<br><br><br>
	<hr>
	<div id='marketTrade' class='tradeHolder'>
		<div id='<?php echo $focusedItem; ?>'>
			<h3><span name='itemShowName'></span>'s Company</h3>
			<div class='leftCol' ='float:left;'>
				<h4 style='margin-top: 0%;'>Limit Order</h4>
				<form method='post'>
					<input type='text' name='price' placeholder='Price per Share'>
					<input type='text' name='amt' placeholder='Amount'><br>
					<button type='submit' name='bid' value='<?php echo $focusedItem; ?>'>Bid</button>
					<button type='submit' name='ask' value='<?php echo $focusedItem; ?>'>Ask</button>
				</form><br>
				
				<h4>Market Order</h4>
				<form method='post'>
					<input type='text' name='amt' placeholder='Amount'><br>
					<button type='submit' name='buyMarket' value='<?php echo $focusedItem; ?>'>Buy</button>
					<button type='submit' name='sellMarket' value='<?php echo $focusedItem; ?>'>Sell</button>
				</form>
			</div>
			<div class='rightCol'>
				<table border='1'>
					<tr>
						<th>Price</th>
						<th>Amount</th>
					</tr>
					<?php echo $myOrder->displayOrders('sec'); ?>
				</table><br>
				<canvas id='priceGraph'></canvas>
				You have <?php echo $userStocks[$focusedItem]; ?> stocks in <span name='itemShowName'></span>'s company.<br><br>
			</div>

		</div>
	</div>
</div>
<script>

var itemName = document.getElementById('<?php echo $focusedItem; ?>').innerText

var showFields = document.getElementsByName('itemShowName');
for (var x = 0; x < showFields.length; x++) {
	showFields[x].innerText = itemName
}

sizeData = <?php echo $myOrder->getHistory('sec'); ?>;
for (var x = 0; x < 2; x++) {
	if (sizeData[x] == null) {
		sizeData[x] = [0]
	}
}

canvas = document.getElementById('priceGraph').getContext('2d');
myChart = new Chart(canvas, {
	type: 'line',
	data: {
		labels: sizeData[0],
		datasets: [
			{
				data: sizeData[1],
				fill: false,
			}
		]
	},
	options: {
		legend: {
			display: false
		},
	}
});

</script>

</body>
</html>