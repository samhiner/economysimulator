<!DOCTYPE html>
<?php

include('../logic/verify.php');
include('../logic/marketorders.php');

function displayItem($code) {
	if ($code[0] == 'P') {
		$showTradeScript = "<script>document.getElementById('marketTrade').style.display = 'block';
		document.getElementById('fixedTrade').style.display = 'none';</script>";
	} else {
		$showTradeScript = "<script>document.getElementById('marketTrade').style.display = 'none';
		document.getElementById('fixedTrade').style.display = 'block';</script>";
	}

	return array($code, $showTradeScript);
}

//look to see if you changed tab or recently submitted an order to change focus to. if none then default to glass
if (isset($_POST['itemLookup'])) {
	$focusedItem = displayItem($_POST['itemLookup']);
} elseif (isset($_POST['bid'])) {
	$focusedItem = displayItem($_POST['bid']);
} elseif (isset($_POST['ask'])) {
	$focusedItem = displayItem($_POST['ask']);
} elseif (isset($_POST['buy'])) {
	$focusedItem = displayItem($_POST['buy']);
} elseif (isset($_POST['sell'])) {
	$focusedItem = displayItem($_POST['sell']);
} elseif (isset($_POST['buyMarket'])) {
	$focusedItem = displayItem($_POST['buyMarket']);
} elseif (isset($_POST['sellMarket'])) {
	$focusedItem = displayItem($_POST['sellMarket']);
} else {
	$focusedItem = displayItem('Mglass');
}

$myOrder = new orderManager(substr($focusedItem[0], 1));

//processes trades
if (isset($_POST['amt'])) { //ISSUE test this
	//do work not specific to buying/selling (minimizes repeated lines)
	$amt = $_POST['amt'];
	$balance = $playerData['balance'];
	$price = 100 * $amt;
	
	if ((isset($_POST['buy'])) or (isset($_POST['sell']))) {
		if (isset($_POST['buy'])) {
			$itemName = substr($_POST['buy'],1);
			$youItems = $playerData[$itemName];
		
			$newYouItems = $youItems + $amt;
			$newBalance = $balance - $price;

			//ensures you have funds for trade
			if($newBalance >= 0) {
				query("UPDATE game1players SET $itemName='$newYouItems',balance='$newBalance' WHERE id='$userCheckID'");
				//include('productdecay.php');
				echo "<meta http-equiv='refresh' content='0'>";
			} else {
				echo "<script>alert('You do not have enough items to make this trade');</script>";
			}
		} elseif (isset($_POST['sell'])) {
			$itemName = substr($_POST['sell'],1);
			$youItems = $playerData[$itemName];
			
			$newYouItems = $youItems - $amt;
			$newBalance = $balance + $price;
			
			echo "UPDATE game1players SET $itemName='$newYouItems',balance='$newBalance' WHERE id='$userCheckID'";
			//ensures you have enough items for trade
			if ($newYouItems >= 0) {
				query("UPDATE game1players SET $itemName='$newYouItems',balance='$newBalance' WHERE id='$userCheckID'");
				//include('productdecay.php'); ISSUE MAY TRIGGER BEFORE PLAYERDATA IS UPDATED CHEKC IT OUT
				echo "<meta http-equiv='refresh' content='0'>";
			} else {
				echo "<script>alert('You do not have enough items to make this trade');</script>";
			}
		}
	}
}

orderCheck('prod',$myOrder);

echo "You have $" . $playerData['balance'];
echo $playerData[$supply1];
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

	<title>Commodities Market | Economy Simulator</title>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.js"></script>
	<link rel='stylesheet' type='text/css' href='../styling/marketpages.css'>
</head>
<body>
<div class='pageBody'>
	<h4 style='margin-top: 0px;'>Choose which item you would like to trade here.</h4>
	<form method='post'>
		<select name='itemLookup' id='itemSelector'>
			<option selected disabled hidden>Choose an Item</option>
			<option style='background-color: #efefef; font-weight: bold' disabled>Materials</option>
			<option id='glassChoice' value='Mglass'>Glass</option>
			<option id='plasticChoice' value='Mplastic'>Plastic</option>
			<option id='alumChoice' value='Malum'>Aluminum</option>
			<option id='siliChoice' value='Msili'>Silicon</option>
			<option id='steelChoice' value='Msteel'>Steel</option>
			<option style='background-color: #efefef; font-weight: bold;' disabled>Products</option>
			<option id='bikeChoice' value='Pbike'>Bicycle</option>
			<option id='tvChoice' value='Ptv'>TV</option>
			<option id='shieldChoice' value='Pshield'>Riot Shield</option>
			<option id='phoneChoice' value='Pphone'>Phone</option>
			<option id='carChoice' value='Pcar'>Car</option>
			<option id='laptopChoice' value='Plaptop'>Laptop</option>
			<option id='smarttvChoice' value='Psmarttv'>Smart TV</option>
			<option id='dogtagsChoice' value='Pdogtags'>Dog Tags</option>
			<option id='shaverChoice' value='Pshaver'>Shaver</option>
		</select><br>
		<input type='submit'>
	</form>
	<br><br><br>
	<hr>
	<div id='marketTrade' class='tradeHolder'>
		<div id='<?php echo substr($focusedItem[0], 1); ?>'>
			<h3><span name='itemShowName'></span></h3>
			<div class='leftCol' ='float:left;'>
				<h4 style='margin-top: 0%;'>Limit Order</h4>
				<form method='post'>
					<input type='text' name='price' placeholder='Price per Item'>
					<input type='text' name='amt' placeholder='Amount'><br>
					<button type='submit' name='bid' value='<?php echo $focusedItem[0]; ?>'>Bid</button>
					<button type='submit' name='ask' value='<?php echo $focusedItem[0]; ?>'>Ask</button>
				</form><br>
				
				<h4>Market Order</h4>
				<form method='post'>
					<input type='number' name='amt' placeholder='Amount'><br>
					<button type='submit' name='buyMarket' value='<?php echo $focusedItem[0]; ?>'>Buy</button>
					<button type='submit' name='sellMarket' value='<?php echo $focusedItem[0]; ?>'>Sell</button>
				</form>
			</div>
			<div class='rightCol'>
				<table border='1'>
					<tr>
						<th>Price</th>
						<th>Amount</th>
					</tr>
					<?php echo $myOrder->displayOrders('prod'); ?>
				</table><br>
				<canvas id='priceGraph'></canvas>
				You have <?php echo $playerData[substr($focusedItem[0], 1)]; ?> <span name='itemShowName'></span>.<br><br>
			</div>

		</div>
	</div>
	<div id='fixedTrade' class='tradeHolder'>
		<div id='<?php echo substr($focusedItem[0], 1); ?>'>
			<h3><span name='itemShowName'></span></h3>
			<form method='post'>
				<input type='number' name='amt' placeholder='Amount'><br>
				<button type='submit' name='buy' value='<?php echo $focusedItem[0]; ?>'>Buy</button>
				<button type='submit' name='sell' value='<?php echo $focusedItem[0]; ?>'>Sell</button>
			</form>
			You have <?php echo $playerData[substr($focusedItem[0], 1)]; ?> <span name='itemShowName'></span>. One <span name='itemShowName'></span> costs $100.<br><br>
		</div>
	</div>
</div>

<?php echo $focusedItem[1]; ?>

<script>

var itemName = document.getElementById('<?php echo substr($focusedItem[0], 1); ?>Choice').innerText

var showFields = document.getElementsByName('itemShowName');
for (var x = 0; x < showFields.length; x++) {
	showFields[x].innerText = itemName;
}

sizeData = <?php echo $myOrder->getHistory('prod'); ?>;
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