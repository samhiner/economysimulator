<?php

include('verify.php');

//goes to whichever tab they clicked on
if(isset($_POST['main'])){
  header("location:index.php");
}
if(isset($_POST['trade'])){
  header("location:commoditiesmarket.php");
}
if(isset($_POST['stock'])){
  header("location:stockmarket.php");
} 


//Fake Market

if (isset($_POST['bid'])) {
	echo 'wassup';
	$amt = $_POST['amt'];
	$price = $_POST['price'];
	$item = $_POST['bid'];
	$query =  "INSERT INTO game1prodorders(item,price,amt,id,type) VALUES('$item','$price','$amt','$userCheckID','1')";
	mysqli_query($connect,"INSERT INTO game1prodorders(item,price,amt,id,type) VALUES('$item','$price','$amt','$userCheckID','1')");
}

if (isset($_POST['ask'])) {
	$amt = $_POST['amt'];
	$price = $_POST['price'];
	$item = $_POST['bid'];
	mysqli_query($connect,"INSERT INTO game1prodorders(item,price,amt,id) VALUES('$item','$price','$amt','$userCheckID','0')");

}

// More Fake 

//general algorithm for finding and displaying bids

$bids = mysqli_query($connect,"SELECT price, amt from game1prodorders WHERE type='1' ORDER BY price DESC, timestamp ASC");
$bidRows = mysqli_num_rows($bids);

//create table with query results
while ($row = mysqli_fetch_array($bids,MYSQLI_ASSOC)) {
	$bidTable[] = $row;
}

//makes new table where price duplicates are merged
$displayTable = array();
for ($x = 0; $x < $bidRows; $x++) {
	$displayTable[$bidTable[$x]['price']] += $bidTable[$x]['amt'];
}

//displays 5 highest bids as an html table
for ($x = 0; $x < 5; $x++) {
	$tempPrice = key($displayTable);
	$tempAmt = $displayTable[key($displayTable)];
	$table .= '
	<tr>
		<td>' . $tempPrice . '</td>
		<td>' . $tempAmt . '</td>
	</tr>
	';
	next($displayTable);
}

/*when fulfilling order
$timestamp = time();
mysqli_query($connect,"UPDATE game1prodorders SET timestamp = '$timestamp' WHERE amt = '7'");*/

//


?>
<html>
<head>

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

<p>Buying and selling stocks</p>


<!--Fake Market-->


			<form method='post'>
				<input type='text' name='price' placeholder='Price'>
				<input type='text' name='amt' id='amt' placeholder='Amount'><br>
				<button type='submit' name='bid' value='bike'>Bid</button>
				<button type='submit' name='ask' value='bike'>Ask</button>
			</form>

<!--NEW-->


<table border='1'>
	<tr>
		<th>Price</th>
		<th>Amount</th>
	</tr>
	<?php echo $table; ?>
</table>

<!---->


</body>
</html>
