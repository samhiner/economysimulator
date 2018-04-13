<!DOCTYPE html><?php

include('verify.php');

//goes to whichever tab they clicked on
if(isset($_POST['main'])){
	header("location:dashboard.php");
}
if(isset($_POST['trade'])){
	header("location:commoditiesmarket.php");
}
if(isset($_POST['stock'])){
	header("location:stockmarket.php");
}

function classItems($m1,$m2,$m3,$p) {
	$GLOBALS['material1'] = $m1;
	$GLOBALS['material2'] = $m2;
	$GLOBALS['material3'] = $m3;
	$GLOBALS['product'] = $p;
}

$playerData = mysqli_fetch_array($playerTable,MYSQLI_ASSOC);
$playerClass = $playerData['class'];

//assign vars based on player's class
if ($playerClass == 1) {
	classItems(glass,plastic,alum,bike);
	//seriously??? fix this.
} elseif ($playerClass == 2) {
	classItems(glass,plastic,sili,tv);
	$showMaterial1 = 'Glass';
	$showMaterial2 = 'Plastic';
	$showMaterial3 = 'Silicon';
	$showProduct = 'TV';
} elseif ($playerClass == 3) {
	classItems(glass,plastic,steel,shield);
	$showMaterial1 = 'Glass';
	$showMaterial2 = 'Plastic';
	$showMaterial3 = 'Steel';
	$showProduct = 'Shield';
} elseif ($playerClass == 4) {
	classItems(glass,alum,sili,phone);
	$showMaterial1 = 'Glass';
	$showMaterial2 = 'Aluminum';
	$showMaterial3 = 'Silicon';
	$showProduct = 'Phone';
} elseif ($playerClass == 5) {
	classItems(glass,alum,steel,car);
	$showMaterial1 = 'Glass';
	$showMaterial2 = 'Aluminum';
	$showMaterial3 = 'Steel';
	$showProduct = 'Car';
} elseif ($playerClass == 6) {
	classItems(glass,sili,steel,laptop);
	$showMaterial1 = 'Glass';
	$showMaterial2 = 'Silicon';
	$showMaterial3 = 'Steel';
	$showProduct = 'Laptop';
} elseif ($playerClass == 7) {
	classItems(plastic,alum,sili,smarttv);
	$showMaterial1 = 'Plastic';
	$showMaterial2 = 'Aluminum';
	$showMaterial3 = 'Silicon';
	$showProduct = 'Smart TV';
} elseif ($playerClass == 8) {
	classItems(plastic,alum,steel,dogtags);
	$showMaterial1 = 'Plastic';
	$showMaterial2 = 'Aluminum';
	$showMaterial3 = 'Steel';
	$showProduct = 'Dog Tags';
} elseif ($playerClass == 9) {
	classItems(plastic,sili,steel,shaver);
	$showMaterial1 = 'Plastic';
	$showMaterial2 = 'Silicon';
	$showMaterial3 = 'Steel';
	$showProduct = 'Shaver';
} elseif ($playerClass == 10) {
	c1assItems(alum,sili,steel,blender);
	$showMaterial1 = 'Aluminum';
	$showMaterial2 = 'Silicon';
	$showMaterial3 = 'Steel';
	$showProduct = 'Blender';
}

if(isset($_POST['make'])) {
	//DO THIS make sure no values are less than 1 before executing query
	$makeQuery = "UPDATE game1players SET $material1 = $material1 - 1, $material2 = $material2 - 1, $material3 = $material3 - 1, $product = $product + 1 WHERE id='$userCheckID'";
	mysqli_query($connect,$makeQuery);
	echo "<meta http-equiv='refresh' content='0'>";

}

?><html><body>

<div class="tab">
	<form method='post'>
		<input type='submit' name='main' value="Dashboard">
		<input type='submit' name='trade' value="Commodities Market">
		<input type='submit' name='stock' value="Stock Market">
	</form>
</div>

<?php 
echo "You have $" . $playerData["balance"] . "<br>";
echo "You have " . $playerData[$material1] . " " . $showMaterial1 . "<br>";
echo "You have " . $playerData[$material2] . " " . $showMaterial2 . "<br>";
echo "You have " . $playerData[$material3] . " " . $showMaterial3 . "<br>";
echo "You have " . $playerData[$product] . " " . $showProduct;
?>

<br><p>Insert more data.</p><br><br><br>

<h3>Your Factory:</h3><br>
Product: 1 <?php echo $showProduct; ?> <br>
Materials: 1 <?php echo $showMaterial1; ?>, 1 <?php echo $showMaterial2; ?>, and 1 <?php echo $showMaterial3; ?>

<form method='post'>
	<input type='submit' name='make' value='Manufacture'>
</form>
</body></html>