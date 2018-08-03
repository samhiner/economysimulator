<?php

class orderManager {

	public $item;

	function __construct($yourItem) {
		$this->item = $yourItem;
	}

	function getMarketPrice($totalAmt,$security) {
		$totalPrice = 0;
		$orders = query("SELECT price, amt FROM game1" . $security . "orders WHERE type='1' AND item='$item' ORDER BY price DESC, timestamp ASC");
		while ($row = mysqli_fetch_array($orders,MYSQLI_ASSOC)) {
			$price = $row['price'];
			$amt = $row['amt'];
			if ($amt >= $totalAmt) {
				$totalPrice = $totalAmt * $price;
				break;
			} else {
				$totalAmt -= $amt;	
				$totalPrice += $amt * $price;
			}
		}

		return $totalPrice;
	}

	function getViewOrders($type,$security) {
		$item = $this->item;

		if ($type == 1) {
			$orders = query("SELECT price, amt FROM game1" . $security . "orders WHERE type='1' AND item='$item' ORDER BY price DESC, timestamp ASC");
		} else {
			//gets an array of 5 lowest unique prices and then gets an array including all orders in that price range
			$maxAsk = mysqli_fetch_array(query("SELECT * FROM game1" . $security . "orders WHERE type='0' AND item='$item' GROUP BY price ORDER BY price ASC LIMIT 5"),MYSQLI_NUM)[4];
			$orders = query("SELECT price, amt FROM game1" . $security . "orders WHERE type='0' AND item='$item' AND price<='$maxAsk' ORDER BY price DESC, timestamp ASC");
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

	function displayOrders($security) {
		$table = '';

		//gnerate bids table then asks table
		for ($type = 0; $type < 2; $type++) {
			//create an array with either bids or asks
			$displayTable = $this->getViewOrders($type,$security);
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

	function getHistory($security) {
		
		$time = '';
		$price = '';
		$rawHistory = query( "SELECT timestamp,price FROM game1" . $security . "history WHERE item='$this->item'");
		$numRows = $rawHistory->num_rows;

		if ($numRows > 0) {
			while ($row = mysqli_fetch_assoc($rawHistory)) {
				$history[] = $row;
			}	
		}

		for ($x = 0; $x < $numRows; $x++) {
			if ($x == $numRows - 1) {
				//didn't put "]" here bc if ther row has zero rows this whiule loop never happens and the array never gets closed
				$next = '';
			} else {
				$next = ',';
			}
			$time .= "'" . date('n/j/Y',$history[$x]['timestamp']) . "'" . $next;
			$price .= $history[$x]['price'] . $next;			
		}
		return '[[' . $time . '],[' . $price . ']]';
	}

	//variables for your order which are set externally when you place the order
	public $amt;
	public $price;
	public $timestamp;
	public $id;

	function placeOrder($type, $security, $isOrder = True) {
		if ($type == 0) {
			if ($security == 'prod') {
				$tableName = 'players';
			} else {
				$tableName = 'shares';
			}
			$trades = query("SELECT * FROM game1" . $security . "orders WHERE type='1' AND item='$this->item' ORDER BY price DESC, timestamp ASC");
			query("UPDATE game1$tableName SET $this->item=$this->item-$this->amt WHERE id='$this->id'");
			echo "<script>alert('$this->amt $this->item have been removed from your account to set up the order.');</script>";
		} else {
			$trades = query("SELECT * FROM game1" . $security . "orders WHERE type='0' AND item='$this->item' ORDER BY price ASC, timestamp ASC");
			$cost = $this->amt * $this->price;
			query("UPDATE game1players SET balance=balance-$cost WHERE id='$this->id'");
			echo "<script>alert('$cost dollars have been removed from your account to set up the order.');</script>";
		}
		
		//iterate through opposite kind of orders and merge orders which fall in constraints set by user (for bid must be less than or equal to price and ask is opposite)
		while ($row = mysqli_fetch_array($trades,MYSQLI_ASSOC)) {
			if ((($type == 1) and ($this->price >= $row['price'])) or (($type == 0) and ($this->price <= $row['price']))) {
				$this->completeOrder($row,$type,$security);
			}
		}

		//if the order was not able to be competely fulfilled add it to the database
		if (($this->amt > 0) and ($isOrder == True)) {
			query("INSERT INTO game1" . $security . "orders(item,price,amt,id,type,timestamp) VALUES('$this->item','$this->price','$this->amt','$this->id','$type','$this->timestamp')");
		}

	}

	function completeOrder($trader2,$type,$security) {

		//find smaller order and completely fulfill that order
		if ($this->amt > $trader2['amt']) {
			$amtChange = $trader2['amt'];
		} else {
			$amtChange = $this->amt;
		}

		$balanceDiff = $trader2['price'] * $amtChange;

		//edit old order to be up to date with transaction and delete it if it fulfilled the whole order
		$query = "UPDATE game1" . $security . "orders SET amt = amt - $amtChange WHERE id = '" . $trader2['id'] . "' AND timestamp = '" . $trader2['timestamp'] . "' LIMIT 1";
		query($query);
		query("DELETE FROM game1" . $security . "orders WHERE amt = '0'");

		//update local representation of your order (added to db if anything is left over after merging with other orders) to match the transaction
		$this->amt -= $amtChange;

		if ($security == 'prod') {
			$tableName = 'players';
		} else {
			$tableName = 'shares';
		}

		//change balance and amt of product for the original orderer based on transaction
		query("UPDATE game1$tableName SET $this->item = $this->item + $amtChange WHERE id = '" . $this->id . "'");
		query("UPDATE game1players SET balance = balance + $balanceDiff WHERE id = '" . $trader2['id'] . "'");

		//add order to history (for graphs)
		query("INSERT INTO game1" . $security . "history(item,timestamp,price) VALUES('$this->item','$this->timestamp','" . $trader2['price'] . "')");
	}
}

function getUserOrders($id, $security) {
	
	$table = '';
	$removeList = [];
	$orders = query( "SELECT * FROM game1" . $security . "orders WHERE id='$id'");
	while ($row = mysqli_fetch_array($orders)) {
		if ($row['type'] == 0) {
			$type = 'Ask';
		} else {
			$type = 'Bid';
		}

		$table .= "<tr>
			<td>" . $row['item'] . "</td>
			<td>" . $row['price'] . "</td>
			<td>" . $row['amt'] . "</td>
			<td>$type</td>
			<td>
				<form method='post'>
					<button type='submit' name='removeOrder' value='" . $row['timestamp'] . "'>Remove</button>
				</form>
			</td>
		</tr>";

		$removeList[] = $row['timestamp'];

	}

	return [$table, $removeList];
}

//sees if you clicked to remove anything and gets a table of all of your orders
function removalCheck($security) {
	global $userCheckID;

	$ordArray = getUserOrders($userCheckID, $security);
	$ordTable = $ordArray[0];
	$remove = $ordArray[1];

	if (isset($_POST['removeOrder'])) {
		for ($x = 0; $x < count($remove); $x++) {
			if ($_POST['removeOrder'] == $remove[$x]) {
				$timestamp = $remove[$x];
				$orderInfo = mysqli_fetch_assoc(query("SELECT * FROM game1" . $security . "orders WHERE id='$userCheckID' AND timestamp='$timestamp'"));
				$cost = $orderInfo['amt'] * $orderInfo['price'];
				$amt = $orderInfo['amt'];
				$item = $orderInfo['item'];

				query("DELETE FROM game1" . $security . "orders WHERE timestamp='$timestamp' AND id='$userCheckID'");

				if ($security == 'prod') {
					$tableName = 'players';
				} else {
					$tableName = 'shares';
				}

				if ($orderInfo['type'] == '1') {
					query("UPDATE game1players SET balance=balance+$cost WHERE id='$userCheckID'");
					echo "<script>alert('You have recieved $cost dollars.')</script>";
					echo "<meta http-equiv='refresh' content='0'>";
				} elseif ($orderInfo['type'] == '0') {
					query("UPDATE game1$tableName SET $item=$item+$amt WHERE id='$userCheckID'");
					echo "<script>alert('You have recieved $amt $item.')</script>";
					echo "<meta http-equiv='refresh' content='0'>";					
				}
			}
		}	
	}
	return $ordTable;
}

function orderCheck($secType,$order) {
	global $userCheckID;
	global $playerData;
	//processes trades
	if (isset($_POST['amt'])) { //ISSUE test this
		//do work not specific to buying/selling (minimizes repeated lines)
		if ((isset($_POST['bid'])) or (isset($_POST['ask'])) or (isset($_POST['buyMarket'])) or (isset($_POST['sellMarket']))) {
			$order->amt = $_POST['amt'];
			$order->timestamp = time();
			$order->id = $userCheckID;

			if (isset($_POST['bid'])) {
				$order->price = $_POST['price'];
				if ($playerData['balance'] >= $_POST['amt'] * $_POST['price']) {
					$order->placeOrder('1', $secType);
					echo "<meta http-equiv='refresh' content='0'>";
				} else {
					echo '<script>alert("need more money");</script>';
				}
			} elseif (isset($_POST['ask'])) {
				$order->price = $_POST['price'];
				if ($playerData[$order->item] >= $_POST['amt']) {
					$order->placeOrder('0', $secType);
					echo "<meta http-equiv='refresh' content='0'>";
				} else {
					echo '<script>alert("need more items");</script>';
				}
			} elseif (isset($POST['buyMarket'])) {
				$order->price = INF;
				if ($playerData['balance'] >= $order->getMarketPrice($_POST['amt'],$secType)) {
					$order->placeOrder('1', $secType, False);
					echo "<meta http-equiv='refresh' content='0'>";
				} else {
					echo '<script>alert("need more money");</script>';
				}
			} elseif (isset($_POST['sellMarket'])) {
				$order->price = -INF;
				if ($playerData[$order->item] >= $_POST['amt']) {
					$order->placeOrder('0', $secType, False);
					echo "<meta http-equiv='refresh' content='0'>";
				} else {
					echo '<script>alert("need more items");</script>';
				}
			}
		}
	}
	return $order;
}

?>