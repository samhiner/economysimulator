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



// More Fake 





//general function for displaying bids
function viewOrders($type,$item) {
	global $connect;
	$bids = mysqli_query($connect,"SELECT price, amt from game1prodorders WHERE type='$type' AND item='$item' ORDER BY price DESC, timestamp ASC");
	$bidRows = mysqli_num_rows($bids); //do i need this line?
	
	//create table with query results
	while ($row = mysqli_fetch_array($bids,MYSQLI_ASSOC)) {
		$bidTable[] = $row;
	}
	
	//makes new table where price duplicates are merged
	$displayTable = array();
	for ($x = 0; $x <= $bidRows; $x++) {
		$displayTable[$bidTable[$x]['price']] += $bidTable[$x]['amt']; //
	}
	
	//displays 5 highest bids as an html table
	$table = '';
	if ($type == 0) {
		for ($x = 0; $x < 5; $x++) {
			$tempPrice = key($displayTable);
			$tempAmt = $displayTable[key($displayTable)];
			//if there are not five bids it will skip making rows that would otherwise be empty
			if ($tempAmt == '') {
				continue;
			} else {
				$table .= "
				<tr class='ask'>
					<td>" . $tempPrice . '</td>
					<td>' . $tempAmt . '</td> 
				</tr>
				'; 
			}
			next($displayTable);
		}
	} else {
		//i see this is duplicated but it should run faster than checking the type each iteration 
		for ($x = 0; $x < 5; $x++) {
			$tempPrice = key($displayTable);
			$tempAmt = $displayTable[key($displayTable)];
			if ($tempAmt == '') {
				continue;
			} else {
				$table .= "
				<tr class='bid'>
					<td>" . $tempPrice . '</td>
					<td>' . $tempAmt . '</td>
				</tr>
				';
			}
			next($displayTable);
		}
	}
	
	return $table;
}

function checkOrders($item,$price,$amt,$id,$type,$timestamp) {
	global $connect;
	if ($type == 0) {
		$trades = mysqli_query($connect,"SELECT * FROM game1prodorders WHERE type='1' AND item='$item' ORDER BY price DESC, timestamp ASC");
	} elseif (type == 1) {
		$trades = mysqli_query($connect,"SELECT * FROM game1prodorders WHERE type='0' AND item='$item' ORDER BY price DESC, timestamp ASC");
	}
	
	while ($row = mysqli_fetch_array($trades,MYSQLI_ASSOC)) {
		$bidTable[] = $row;
	}
	
	foreach ($bidTable as $row) {
		
	}
}	
	
checkOrders('bike',5,1,9,0,89);
	
if (isset($_POST['bid'])) {
	$amt = $_POST['amt'];
	$price = $_POST['price'];
	$item = $_POST['bid'];
	$timestamp = time();
	mysqli_query($connect,"INSERT INTO game1prodorders(item,price,amt,id,type,timestamp) VALUES('$item','$price','$amt','$userCheckID','1','$timestamp')");
}

if (isset($_POST['ask'])) {
	$amt = $_POST['amt'];
	$price = $_POST['price'];
	$item = $_POST['bid'];
	$timestamp = time();
	mysqli_query($connect,"INSERT INTO game1prodorders(item,price,amt,id,type,timestamp) VALUES('$item','$price','$amt','$userCheckID','0','$timestamp')");
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


<!--Fake Market-->


			<form method='post'>
				<input type='text' name='price' placeholder='Price'>
				<input type='text' name='amt' id='amt' placeholder='Amount'><br>
				<button type='submit' name='bid' value='bike'>Bid</button>
				<button type='submit' name='ask' value='bike'>Ask</button>
			</form>

<!--NEW-->


<br><table border='1'>
	<tr>
		<th>Price</th>
		<th>Amount</th>
	</tr>
	<?php echo viewOrders(0,'bike'); echo viewOrders(1,'bike'); ?>
</table>

<!---->


</body>
</html>