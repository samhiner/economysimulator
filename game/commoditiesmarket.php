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

	return array(substr($code, 1), $showTradeScript);
}

//default your tab to glass
$focusedItem = displayItem('Mglass'); //ISSUE: fix this so it defaults to tab you were last on. could do this using post value of buy/sell/bid/ask

//change your tab when you choose the item you want
if (isset($_POST['itemLookup'])) {
	$focusedItem = displayItem($_POST['itemLookup']);
}

$myOrder = new orderManager($focusedItem);

//processes trades
if (isset($_POST['amt'])) { //ISSUE test this
	//do work not specific to buying/selling (minimizes repeated lines)
	$amt = $_POST['amt'];
	$balance = $playerData['balance'];
	$price = 100 * $amt;
	
	if ((isset($_POST['buy'])) or (isset($_POST['sell']))) {
		if (isset($_POST['buy'])) {
			$itemName = $_POST['buy'];
			$youItems = $playerData[$itemName];
		
			$newYouItems = $youItems + $amt;
			$newBalance = $balance - $price;

			//ensures you have funds for trade
			if($newBalance >= 0) {
				mysqli_query($connect,"UPDATE game1players SET $itemName='$newYouItems',balance='$newBalance' WHERE id='$userCheckID'");
				//include('productdecay.php');
				//echo "<meta http-equiv='refresh' content='0'>";
			} else {
				echo "<script>alert('You do not have enough items to make this trade');</script>";
			}
		} elseif (isset($_POST['sell'])) {
			$itemName = $_POST['sell'];
			$youItems = $playerData[$itemName];
			
			$newYouItems = $youItems - $amt;
			$newBalance = $balance + $price;
			
			//ensures you have enough items for trade
			if ($newYouItems >= 0) {
				mysqli_query($connect,"UPDATE game1players SET $itemName='$newYouItems',balance='$newBalance' WHERE id='$userCheckID'");
				//include('productdecay.php'); ISSUE MAY TRIGGER BEFORE PLAYERDATA IS UPDATED CHEKC IT OUT
				echo "<meta http-equiv='refresh' content='0'>";
			} else {
				echo "<script>alert('You do not have enough items to make this trade');</script>";
			}
		}
	} elseif ((isset($_POST['bid'])) or (isset($_POST['ask']))) {
		$myOrder->amt = $_POST['amt'];
		$myOrder->price = $_POST['price'];
		$myOrder->timestamp = time();
		$myOrder->id = $userCheckID;

		if (isset($_POST['bid'])) {
			if ($playerData['balance'] >= $_POST['amt'] * $_POST['price']) {
				$myOrder->placeOrder('1');
			} else {
				echo 'need more money';
			}
		} elseif (isset($_POST['ask'])) {
			if ($playerData[$myOrder->item] >= $_POST['amt']) {
				$myOrder->placeOrder('0');
			} else {
				echo 'need more items';
			}
		}
	}
}

echo "You have $" . $playerData['balance'];
echo $playerData[$supply1];

?>
<html>
<head>
<style>

.leftcol {
	float: left;
	width: 45%;
	border-style: solid;
	border-color: black;
	border-width: 1%;
	border-right-color: white;
	padding: 2%;
}
.rightcol {
	float: left;
	width: 45%;
	border-style: solid;
	border-color: black;
	border-width: 1%;
	padding: 2%;
}
.center {
	display: block;
	margin-left: auto;
	margin-right: auto;
	padding: 0;
	text-align: center;
}
.noVis {
	display: none;
}
.x {
	border: solid;
	border-color: black;
	padding-bottom: 300px;
}
</style>

<title>Economy Simulator</title>

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

	<div id='allItems' class='x'>
		<div id='marketTrade'>
			<div id='<?php echo $focusedItem[0]; ?>'>
				<h3><span name='itemShowName'></span></h3>
				<div style='float:left;'>
					<h4>Limit Order</h4>
					<form method='post'>
						<input type='text' name='amt' placeholder='Amount'>
						<input type='text' name='price' placeholder='Price'><br>
						<button type='submit' name='bid' value='<?php echo $focusedItem[0]; ?>'>Bid</button>
						<button type='submit' name='ask' value='<?php echo $focusedItem[0]; ?>'>Ask</button>
					</form><br>
					
					<h4>Market Order (Out of Order)</h4>
					<form method='post'>
						<input type='text' name='amt' placeholder='Amount'><br>
						<button type='submit' name='buy' value='<?php echo $focusedItem[0]; ?>'>Buy</button>
						<button type='submit' name='sell' value='<?php echo $focusedItem[0]; ?>'>Sell</button>
					</form>
				</div>
				
				<br><table border='1'>
					<tr>
						<th>Price</th>
						<th>Amount</th>
					</tr>
					<?php echo $myOrder->displayOrders(); ?>
				</table><br>

				You have <?php echo $playerData[$focusedItem[0]]; ?> <span name='itemShowName'></span>. One <span name='itemShowName'></span> costs $100.<br><br>
			</div>
		</div>
		<div id='fixedTrade'>
			<div id='<?php echo $focusedItem[0]; ?>'>
				<h3><span name='itemShowName'></span></h3>
				<form method='post'>
					<input type='text' name='amt' value='Amount'><br>
					<button type='submit' name='buy' value='<?php echo $focusedItem[0]; ?>'>Buy</button>
					<button type='submit' name='sell' value='<?php echo $focusedItem[0]; ?>'>Sell</button>
				</form>
				You have <?php echo $playerData[$focusedItem[0]]; ?> <span name='itemShowName'></span>. One <span name='itemShowName'></span> costs $100.<br><br>
			</div>
		</div>
	</div>
</div>

<?php echo $focusedItem[1]; ?>

<script>

var itemName = document.getElementById('<?php echo $focusedItem[0]; ?>Choice').innerText

var showFields = document.getElementsByName('itemShowName');
for (x = 0; x < showFields.length; x++) {
	showFields[x].innerText = itemName;
}

</script>

</body>
</html>