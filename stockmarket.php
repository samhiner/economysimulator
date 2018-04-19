<!--<!DOCTYPE html>LESSTHAN?php

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

?><html><body>

<div class="tab">
  <form method='post'>
    <input type='submit' name='main' value="Dashboard">
    <input type='submit' name='trade' value="Commodities Market">
    <input type='submit' name='stock' value="Stock Market">
  </form>
</div>

<br><p>Buying and selling stocks</p>
</body></html>
-->
<!DOCTYPE HTML>
<?php
include('verify.php');
$makingBool = 1;

$timeQuery = mysqli_query($connect,"SELECT * FROM game1time WHERE id='$userCheckID'");
$timeArray = mysqli_fetch_array($timeQuery,MYSQLI_ASSOC);

$jsMakeString = $timeArray['month'] . "/" . $timeArray['day'] . "/" . $timeArray['year'] . " " . $timeArray['hour'] . ":" . $timeArray['minute'] . ":" . $timeArray['second'];

?>
<html>
<body>

<p id="demo"></p>
</body>

echo "
<script>
//Finds time until item is made.
var makeWhen = "<?php echo $jsMakeString; ?>";
var makeDate = new Date(makeWhen).getTime();

var x = setInterval (function() {
	var now = new Date().getTime();
	var timeLeft = makeDate - now;
	
	var hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
	var minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
	var seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
	
	document.getElementById("demo").innerHTML = hours + " Hours, " + minutes + " Minutes, " + seconds + " Seconds";
	//MAKE THIS if timer is zero refresh page so the php kicks in
}, 1000);
</script>";


</html>