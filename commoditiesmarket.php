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

</style>

<title>Economy Simulator</title>

</head>
<body>

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

//processes trades
if ($_SERVER['REQUEST_METHOD'] == 'POST') {	
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
			echo "<meta http-equiv='refresh' content='0'>";
		} else {
			echo "<script>alert('You do not have enough items to make this trade');</script>";
		}
	}
}

echo "You have $" . $playerData['balance'];

?>


<div class="tab">
	<form method='post'>
		<input type='submit' name='main' value="Dashboard">
		<input type='submit' name='trade' value="Commodities Market">
		<input type='submit' name='stock' value="Stock Market">
	</form>
</div>

<h3>Choose which item you would like to trade here.</h3>
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
</select>

<div id='allItems'>
	<div id='glass'>
		<br><h3>Glass</h3>
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

	<div id='plastic'>
		<br><h3>Plastic</h3>
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

	<div id='alum'>
		<br><h3>Aluminum</h3>
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

	<div id='sili'>
		<br><h3>Silicon</h3>
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

	<div id='steel'>
		<br><h3>Steel</h3>
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

	<div id='bike'>
		<br><h3>Bicycle</h3>
		<form method='post'>
			<input type='text' name='amt' value='Amount'><br>
			<button type='submit' value='bike' name='buy'>Buy Bicycle</button>
		</form><br>
		<form method='post'>
			<input type='text' value='Amount' name='amt'><br>
			<button type='submit' name='sell' value='bike'>Sell Bicycle</button>
		</form>
		You have <?php echo $playerData['bike']; ?> Bicycle. One Bicycle costs $100.<br><br>
	</div>

	<div id='tv'>
		<br><h3>TV</h3>
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

	<div id='shield'>
		<br><h3>Riot Shield</h3>
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

	<div id='phone'>
		<br><h3>Phone</h3>
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

	<div id='car'>
		<br><h3>Car</h3>
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

	<div id='laptop'>
		<br><h3>Laptop</h3>
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

	<div id='smarttv'>
		<br><h3>Smart TV</h3>
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

	<div id='dogtags'>
		<br><h3>Dog Tags</h3>
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

	<div id='shaver'>
		<br><h3>Shaver</h3>
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