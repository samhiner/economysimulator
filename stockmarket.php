<!DOCTYPE html>
<?php

include('verify.php');

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
						$table .= '
						<tr class="ask">
							<td>' . $tempPrice . '</td>
							<td>' . $tempAmt . '</td> 
						</tr>
						'; 
					} else {
						$table .= '
						<tr class="bid">
							<td>' . $tempPrice . '</td>
							<td>' . $tempAmt . '</td>
						</tr>
						';	
					}
					
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
	// ^ done until I make it not **
	//

	function completeOrder($trader2,$amt,$type) {
		global $connect;

		$foreignID = $trader2['id'];
		$foreignTime = $trader2['timestamp'];
		$foreignAmt = $trader2['amt'];
		$price = $trader2['price'];

		//find smaller order and completely fulfill that order
		if ($amt > $foreignAmt) {
			$amtChange = $foreignAmt;
		} else {
			$amtChange = $amt;
		}

		if ($type == 1) {
			$newAmt = $amt + $amtChange;
			$newForeignAmt = $foreignAmt - $amtChange;
		} else {
			$newAmt = $amt - $amtChange;
			$newForeignAmt = $foreignAmt + $amtChange;
		}

		$theyOwe = $price * $amtChange;

		//find both orders and remove the transacted amount
		mysqli_query($connect,"UPDATE game1prodorders SET amt = amt - $amtChange WHERE id='" . $this->id . "' AND timestamp='$foreignTime' LIMIT 1");
		mysqli_query($connect,"UPDATE game1prodorders SET amt = amt - $amtChange WHERE id='$foreignID' AND timestamp='$foreignTime' LIMIT 1");

		//remove the noew zero amt order from the system
		mysqli_query($connect,"DELETE FROM game1prodorders WHERE amt = '0'");

		//change balance and amt of product based on transaction
		mysqli_query($connect,"UPDATE game1players SET amt = amt + $newAmt,price = price + $theyOwe WHERE id='$foreignID' AND timestamp='$foreignTime' LIMIT 1");
		mysqli_query($connect,"UPDATE game1players SET amt = amt + $newForeignAmt, price = price - $theyOwe WHERE id='$foreignID' AND timestamp='$foreignTime' LIMIT 1");
	}

	//variables for your order which are set externally when you place the order
	public $amt;
	public $price;
	public $timestamp;
	public $id;

	function checkOrders($type) {
		global $connect;
		if ($type == 0) {
			$trades = mysqli_query($connect,"SELECT * FROM game1prodorders WHERE type='1' AND item='" . $this->item . "' ORDER BY price DESC, timestamp ASC");
		} else {
			$trades = mysqli_query($connect,"SELECT * FROM game1prodorders WHERE type='0' AND item='" . $this->item . "' ORDER BY price ASC, timestamp ASC");
		}
		
		while ($row = mysqli_fetch_array($trades,MYSQLI_ASSOC)) {
			//sees if orders can be traded '<= for bid', '>= for ask'
			if ((($type == 1) and ($this->price <= $row['price'])) or (($type == 0) and ($this->price >= $row['price']))) {
				$this->completeOrder($row,$this->amt,$this->price,$type);
			}
		}
		/*
		foreach ($bidTable as $row) {
			//sees if orders can be traded '<= for bid', '>= for ask'
			if ((($type == 1) and ($this->price <= $row['price'])) or (($type == 0) and ($this->price >= $row['price']))) {
				$this->completeOrder($row,$this->amt,$this->price,$type);
			}
		}*/
	}

	function placeOrder($type) {
		global $connect;
		global $userCheckID; //ik its terrible i am purging all globals for attributes soon

		$amt = $this->amt;
		$price = $this->price;
		$timestamp = $this->timestamp; //WHOA WHOA WHOA SOMEWHERE UP THERE IT JUST USES 'PRICE' WHAT IF THEY SAID $5 AND THE LOWEST ASK IS $3 ** **
		$item = $this->item;
		mysqli_query($connect,"INSERT INTO game1prodorders(item,price,amt,id,type,timestamp) VALUES('$item','$price','$amt','$userCheckID','$type','$timestamp')");
		$this->checkOrders($type);
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
		echo 'need more money **';
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
		echo 'need more ** **';
	}
}

echo $playerData['bike'];

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
</table>

<!---->


</body>
</html>