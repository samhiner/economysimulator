<!DOCTYPE html>
<?php

include('../logic/verify.php');

//goes to whichever tab they clicked on
if(isset($_POST['main'])){
	header('location:index');
}
if(isset($_POST['trade'])){
	header('location:commoditiesmarket');
}
if(isset($_POST['stock'])){
	header('location:stockmarket');
} 


//Fake Market

class orderManager {

	public $item;

	function __construct($yourItem) {
		$this->item = $yourItem;
	}

	function getViewOrders($type) {
		$item = $this->item;
		global $connect;

		if ($type == 1) {
			$orders = mysqli_query($connect,"SELECT price, amt FROM game1prodorders WHERE type='1' AND item='$item' ORDER BY price DESC, timestamp ASC");
		} else {
			//gets an array of 5 lowest unique prices and then gets an array including all orders in that price range
			$maxAsk = mysqli_fetch_array(mysqli_query($connect,"SELECT * FROM game1prodorders WHERE type='0' AND item='$item' GROUP BY price ORDER BY price ASC LIMIT 5"),MYSQLI_NUM)[4];
			$orders = mysqli_query($connect,"SELECT price, amt FROM game1prodorders WHERE type='0' AND item='$item' AND price<='$maxAsk' ORDER BY price DESC, timestamp ASC");
		}
		
		$orderRows = mysqli_num_rows($orders);

		//create table with query results
		while ($row = mysqli_fetch_array($orders,MYSQLI_ASSOC)) {
			$orderTable[] = $row;
		}

		//makes new table where price duplicates are merged
		$displayTable = array();
		for ($x = 0; $x < $orderRows; $x++) {
			//prevents 'undefined offset' error
			if (isset($displayTable[$orderTable[$x]['price']])) {
				$displayTable[$orderTable[$x]['price']] += $orderTable[$x]['amt'];
			} else {
				$displayTable[$orderTable[$x]['price']] = $orderTable[$x]['amt'];
			}
		}
		return $displayTable;
	}

	function displayOrders() {
		$table = '';

		//gnerate bids table then asks table
		for ($type = 0; $type < 2; $type++) {
			//create an array with either bids or asks
			$displayTable = $this->getViewOrders($type);
			//displays 5 highest bids as an html table
			for ($x = 0; $x < 5; $x++) {
				$tempPrice = key($displayTable);
				if (isset($displayTable[key($displayTable)])) {
					$tempAmt = $displayTable[key($displayTable)];
				} else {
					$tempAmt = '';
				}

				//if there are not five orders it will skip making rows that would otherwise be empty
				if ($tempAmt == '') {
					continue;
				} else {
					if ($type == 0) {
						$displayType = 'ask';
					} else {
						$displayType = 'bid';
					}
					$table .= '
					<tr class="' . $displayType . '">
						<td>' . $tempPrice . '</td>
						<td>' . $tempAmt . '</td>
					</tr>
					';
				}
				next($displayTable);
			}
		}
		//if there were no orders put "no orders" on the table
		if ($table == '') {
			$table = "
			<tr>
				<td colspan='2'>No Orders</td>
			</tr>";
		}
		return $table;
	}

	//
	// ^ done until I make it not bad
	//





	function completeOrder($trader2,$type) {
		global $connect;

		//find smaller order and completely fulfill that order
		if ($this->amt > $trader2['amt']) {
			$amtChange = $trader2['amt'];
		} else {
			$amtChange = $this->amt;
		}

		//amtDiff always given to current user and taken from old (balanceDiff is opposite) so if current user did an ask then the signs are switched 
		if ($type == 0) {
			$amtDiff = $amtChange * -1;
		} else {
			$amtDiff = $amtChange;
		}

		$balanceDiff = $trader2['price'] * $amtDiff;

		//edit old order to be up to date with transaction and delete it if it fulfilled the whole order
		$query = "UPDATE game1prodorders SET amt = amt - $amtChange WHERE id = '" . $trader2['id'] . "' AND timestamp = '" . $trader2['timestamp'] . "' LIMIT 1";
		mysqli_query($connect,$query);
		mysqli_query($connect,"DELETE FROM game1prodorders WHERE amt = '0'");

		//update local representation of your order (added to db if anything is left over after merging with other orders) to match the transaction
		$this->amt -= $amtChange;

		//change balance and amt of product for the original orderer based on transaction
		mysqli_query($connect,"UPDATE game1players SET $this->item = $this->item + $amtDiff, balance = balance - $balanceDiff WHERE id = '" . $this->id . "'");
		mysqli_query($connect,"UPDATE game1players SET $this->item = $this->item - $amtDiff, balance = balance + $balanceDiff WHERE id = '" . $trader2['id'] . "'");
	}

	//variables for your order which are set externally when you place the order
	public $amt;
	public $price;
	public $timestamp;
	public $id;

	function placeOrder($type) {
		global $connect;
		if ($type == 0) {
			$trades = mysqli_query($connect,"SELECT * FROM game1prodorders WHERE type='1' AND item='$this->item' ORDER BY price DESC, timestamp ASC");
		} else {
			$trades = mysqli_query($connect,"SELECT * FROM game1prodorders WHERE type='0' AND item='$this->item' ORDER BY price ASC, timestamp ASC");
		}
		
		//iterate through opposite kind of orders and merge orders which fall in constraints set by user (for bid must be less than or equal to price and ask is opposite)
		while ($row = mysqli_fetch_array($trades,MYSQLI_ASSOC)) {
			if ((($type == 1) and ($this->price <= $row['price'])) or (($type == 0) and ($this->price >= $row['price']))) {
				$this->completeOrder($row,$type);
			}
		}

		//if the order was not able to be competely fulfilled add it to the database
		if ($this->amt > 0) {
			mysqli_query($connect,"INSERT INTO game1prodorders(item,price,amt,id,type,timestamp) VALUES('$this->item','$this->price','$this->amt','$this->id','$type','$this->timestamp')");
		}

	}
}









$myOrder = new orderManager('bike');
echo $myOrder->item;

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

<div class="tab">
		<form method='post'>
		<input type='submit' name='main' value="Dashboard">
		<input type='submit' name='trade' value="Commodities Market">
		<input type='submit' name='stock' value="Stock Market">
	</form>
</div>

<p>Buying and selling stocks</p>
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

</body>
</html>