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
<html>
<head>
<style>

.leftcol {
	float: left;
    width: 50%;
}
.rightcol {
	float: left;
    width: 50%;
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

<div class='leftcol'>
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

</body>
</html>