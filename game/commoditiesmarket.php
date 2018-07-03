<!DOCTYPE html>
<?php

include('../logic/verify.php');

//goes to whichever tab they clicked on
if (isset($_POST['main'])) {
	header('location: http://localhost/economysimulator/game/index');
}
if(isset($_POST['trade'])){
	header('location: http://localhost/economysimulator/game/commoditiesmarket');
}
if (isset($_POST['stock'])) {
	header('location: http://localhost/economysimulator/game/stockmarket');
} 

//processes trades
if ($_SERVER['REQUEST_METHOD'] == 'POST') {	//ISSUE make it so this doesnt run on tab click
	//do work not specific to buying/selling (minimizes repeated lines)
	$amt = $_POST['amt'];
	$balance = $playerData['balance'];
	$price = 100 * $amt;
	
	if(isset($_POST['buy'])) {
		$itemName = $_POST['buy'];
		$youItems = $playerData[$itemName];
	
		$newYouItems = $youItems + $amt;
		$newBalance = $balance - $price;

		//ensures you have funds for trade
		if($newBalance >= 0) {
			mysqli_query($connect,"UPDATE game1players SET $itemName='$newYouItems',balance='$newBalance' WHERE id='$userCheckID'");
			//include('productdecay.php');
			echo "<meta http-equiv='refresh' content='0'>";
		} else {
			echo "<script>alert('You do not have enough items to make this trade');</script>";
		}
	} elseif (isset($_POST['sell'])) {
		$itemName = $_POST['sell'];
		$youItems = $playerData[$itemName];
		
		$newYouItems = $youItems - $amt;
		$newBalance = $balance + $price;
		
		//ensures you have enough items for trade
		if($newYouItems >= 0) {
			mysqli_query($connect,"UPDATE game1players SET $itemName='$newYouItems',balance='$newBalance' WHERE id='$userCheckID'");
			//include('productdecay.php'); ISSUE MAY TRIGGER BEFORE PLAYERDATA IS UPDATED CHEKC IT OUT
			echo "<meta http-equiv='refresh' content='0'>";
		} else {
			echo "<script>alert('You do not have enough items to make this trade');</script>";
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
body {
	font-family: sans-serif;
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

<div class="tab">
	<form method='post'>
		<input type='submit' name='main' value="Dashboard">
		<input type='submit' name='trade' value="Commodities Market">
		<input type='submit' name='stock' value="Stock Market">
	</form>
</div>

<h4>Choose which item you would like to trade here.</h4>
<select id='itemSelector'>
	<option style='background-color: #efefef; font-weight: bold' disabled>Materials</option>
	<option value='glass'>Glass</option>
	<option value='plastic'>Plastic</option>
	<option value='alum'>Aluminum</option>
	<option value='sili'>Silicon</option>
	<option value='steel'>Steel</option>
	<option style='background-color: #efefef; font-weight: bold;' disabled>Products</option>
	<option value='bike'>Bicycle</option>
	<option value='tv'>TV</option>
	<option value='shield'>Riot Shield</option>
	<option value='phone'>Phone</option>
	<option value='car'>Car</option>
	<option value='laptop'>Laptop</option>
	<option value='smarttv'>Smart TV</option>
	<option value='dogtags'>Dog Tags</option>
	<option value='shaver'>Shaver</option>
</select><br><br><br>

<div id='allItems' class='x'>
	<div id='glass' class='noVis'><!--remove novis from class-->
		<h3>Glass</h3>
		<form method='post'>
			<input type='text' name='amt' value='Amount'><br>
			<button type='submit' value='glass' name='buy'>Buy Glass</button>
		</form><br>
		<form method='post'>
			<input type='text' value='Amount' name='amt'><br>
			<button type='submit' name='sell' value='glass'>Sell Glass</button>
		</form>
		You have <?php echo $playerData['glass']; ?> Glass. One Glass costs $100.<br><br>
	</div>

	<div id='plastic' class='noVis'>
		<h3>Plastic</h3>
		<form method='post'>
			<input type='text' name='amt' value='Amount'><br>
			<button type='submit' value='plastic' name='buy'>Buy Plastic</button>
		</form><br>
		<form method='post'>
			<input type='text' value='Amount' name='amt'><br>
			<button type='submit' name='sell' value='plastic'>Sell Plastic</button>
		</form>
		You have <?php echo $playerData['plastic']; ?> Plastic. One Plastic costs $100.<br><br>
	</div>

	<div id='alum' class='noVis'>
		<h3>Aluminum</h3>
		<form method='post'>
			<input type='text' name='amt' value='Amount'><br>
			<button type='submit' value='alum' name='buy'>Buy Aluminum</button>
		</form><br>
		<form method='post'>
			<input type='text' value='Amount' name='amt'><br>
			<button type='submit' name='sell' value='alum'>Sell Aluminum</button>
		</form>
		You have <?php echo $playerData['alum']; ?> Aluminum. One Aluminum costs $100.<br><br>
	</div>

	<div id='sili' class='noVis'>
		<h3>Silicon</h3>
		<form method='post'>
			<input type='text' name='amt' value='Amount'><br>
			<button type='submit' value='sili' name='buy'>Buy Silicon</button>
		</form><br>
		<form method='post'>
			<input type='text' value='Amount' name='amt'><br>
			<button type='submit' name='sell' value='sili'>Sell Silicon</button>
		</form>
		You have <?php echo $playerData['sili']; ?> Silicon. One Silicon costs $100.<br><br>
	</div>

	<div id='steel' class='noVis'>
		<h3>Steel</h3>
		<form method='post'>
			<input type='text' name='amt' value='Amount'><br>
			<button type='submit' value='steel' name='buy'>Buy Steel</button>
		</form><br>
		<form method='post'>
			<input type='text' value='Amount' name='amt'><br>
			<button type='submit' name='sell' value='steel'>Sell Steel</button>
		</form>
		You have <?php echo $playerData['steel']; ?> Steel. One Steel costs $100.<br><br>
	</div>

<!---->	
	<div id='bike' class=''><!--add novis to class-->
		<h3>Bicycle</h3>
		<div style='float:left;'>
			<h4>Limit Order</h4>
			<form method='post'>
				<input type='text' name='amt' id='amt' placeholder='Amount'>
				<input type='text' name='price' placeholder='Price'><br>
				<button type='submit' name='bid' value='bike'>Bid</button>
				<button type='submit' name='ask' value='bike'>Ask</button>
			</form><br>
			
			<h4>Market Order</h4>
			<form method='post'>
				<input type='text' name='amt' id='amt' placeholder='Amount'><br>
				<button type='submit' name='buyMarket' value='bike'>Buy</button>
				<button type='submit' name='sellMarket' value='bike'>Sell</button>
			</form>
		</div>
		
		You have <?php echo $playerData['bike']; ?> Bicycles. One Bicycle costs $100.<br><br>
	</div>

<!---->
	
	<div id='tv' class='noVis'>
		<h3>TV</h3>
		<form method='post'>
			<input type='text' name='amt' value='Amount'><br>
			<button type='submit' value='tv' name='buy'>Buy TV</button>
		</form><br>
		<form method='post'>
			<input type='text' value='Amount' name='amt'><br>
			<button type='submit' name='sell' value='tv'>Sell TV</button>
		</form>
		You have <?php echo $playerData['tv']; ?> TV. One TV costs $100.<br><br>
	</div>

	<div id='shield' class='noVis'>
		<h3>Riot Shield</h3>
		<form method='post'>
			<input type='text' name='amt' value='Amount'><br>
			<button type='submit' value='shield' name='buy'>Buy Riot Shield</button>
		</form><br>
		<form method='post'>
			<input type='text' value='Amount' name='amt'><br>
			<button type='submit' name='sell' value='shield'>Sell Riot Shield</button>
		</form>
		You have <?php echo $playerData['shield']; ?> Riot Shield. One Riot Shield costs $100.<br><br>
	</div>

	<div id='phone' class='noVis'>
		<h3>Phone</h3>
		<form method='post'>
			<input type='text' name='amt' value='Amount'><br>
			<button type='submit' value='phone' name='buy'>Buy Phone</button>
		</form><br>
		<form method='post'>
			<input type='text' value='Amount' name='amt'><br>
			<button type='submit' name='sell' value='phone'>Sell Phone</button>
		</form>
		You have <?php echo $playerData['phone']; ?> Phone. One Phone costs $100.<br><br>
	</div>

	<div id='car' class='noVis'>
		<h3>Car</h3>
		<form method='post'>
			<input type='text' name='amt' value='Amount'><br>
			<button type='submit' value='car' name='buy'>Buy Car</button>
		</form><br>
		<form method='post'>
			<input type='text' value='Amount' name='amt'><br>
			<button type='submit' name='sell' value='car'>Sell Car</button>
		</form>
		You have <?php echo $playerData['car']; ?> Car. One Car costs $100.<br><br>
	</div>

	<div id='laptop' class='noVis'>
		<h3>Laptop</h3>
		<form method='post'>
			<input type='text' name='amt' value='Amount'><br>
			<button type='submit' value='laptop' name='buy'>Buy Laptop</button>
		</form><br>
		<form method='post'>
			<input type='text' value='Amount' name='amt'><br>
			<button type='submit' name='sell' value='laptop'>Sell Laptop</button>
		</form>
		You have <?php echo $playerData['laptop']; ?> Laptop. One Laptop costs $100.<br><br>
	</div>

	<div id='smarttv' class='noVis'>
		<h3>Smart TV</h3>
		<form method='post'>
			<input type='text' name='amt' value='Amount'><br>
			<button type='submit' value='smarttv' name='buy'>Buy Smart TV</button>
		</form><br>
		<form method='post'>
			<input type='text' value='Amount' name='amt'><br>
			<button type='submit' name='sell' value='smarttv'>Sell Smart TV</button>
		</form>
		You have <?php echo $playerData['smarttv']; ?> Smart TV. One Smart TV costs $100.<br><br>
	</div>

	<div id='dogtags' class='noVis'>
		<h3>Dog Tags</h3>
		<form method='post'>
			<input type='text' name='amt' value='Amount'><br>
			<button type='submit' value='dogtags' name='buy'>Buy Dog Tags</button>
		</form><br>
		<form method='post'>
			<input type='text' value='Amount' name='amt'><br>
			<button type='submit' name='sell' value='dogtags'>Sell Dog Tags</button>
		</form>
		You have <?php echo $playerData['dogtags']; ?> Dog Tags. One Dog Tags costs $100.<br><br>
	</div>

	<div id='shaver' class='noVis'>
		<h3>Shaver</h3>
		<form method='post'>
			<input type='text' name='amt' value='Amount'><br>
			<button type='submit' value='shaver' name='buy'>Buy Shaver</button>
		</form><br>
		<form method='post'>
			<input type='text' value='Amount' name='amt'><br>
			<button type='submit' name='sell' value='shaver'>Sell Shaver</button>
		</form>
		You have <?php echo $playerData['shaver']; ?> Shaver. One Shaver costs $100.<br><br>
	</div>
</div>

<script>

//sees when you choose an item from the dropdown and shows that div + hides other divs
itemSelector.addEventListener('input', function () {
	//make all children of 'allItems invisivle'
	var allItems = document.getElementById('allItems').children;
	for (x = 0; x < allItems.length; x++) {
		allItems[x].style.display = 'none';
	}
	
	//make selected div visible (undoes above for loop for that div)
	var showDiv = document.getElementById('itemSelector').value;
	document.getElementById(showDiv).style.display = 'block';
});

</script>

</body>
</html>