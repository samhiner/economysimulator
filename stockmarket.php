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
	$amt = $_POST['amt'];
	$price = $_POST['price'];
	mysqli_query($connect,"INSERT INTO game1bids(price,amt,bidderID) VALUES('$price','$amt','$userCheckID')");
}

if (isset($_POST['ask'])) {
	
}

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

<br><p>Buying and selling stocks</p>


<!--Fake Market-->

<select id='hjk'>
	<option value='ay'>H</option>
	<option value='nay'>G</option>
</select>

<br><h3>Bicycle</h3>
<form method='post'>
	<input type='text' name='amt' id='amt' placeholder='Amount'>
	<input type='text' name='price' placeholder='Price'><br>
	<button type='submit' name='bid' value='bike'>Bid</button>
	<button type='submit' name='ask' value='bike'>Ask</button>
</form>

You have <?php echo $playerData['bike']; ?> Bicycles. One Bicycle costs $100.<br><br>

<div id='x'></div>

<!---->


</body>
</html>
<script>

//checks for you to choose an item from the dropdown and switches to that item
hjk.addEventListener('input', function () {
	if (document.getElementById('hjk').value == 'ay') {
		document.getElementById('x').innerHTML = 'yuh';
	} else if (document.getElementById('hjk').value == 'nay') {
		document.getElementById('x').innerHTML = 'nuh';
}
});

</script>