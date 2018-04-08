<html><body>Session Verified!</body></html>
<?php

session_start();
$connect = mysqli_connect('localhost', 'root', 'root', 'econ_data');
if(mysqli_connect_errno($connect)) {
  echo 'Failed to connect';
}

$userData = $_SESSION['userData'];
if(!isset($_SESSION['userData'])){

  header("location: index.php");
}

//ensure acct is linked to profile
$playerTable = mysqli_query($connect,"SELECT * FROM game1players WHERE id = '$userCheckID'");
if($playerCount != 1) {
  header("location: home.php")
}

?>