<html><body>Session Verified!</body></html>
<?php

session_start();
$connect = mysqli_connect('localhost', 'root', 'root', 'econ_data');
if(mysqli_connect_errno($connect)) {
  echo 'Failed to connect';
}

//make sure they are logged in or send to login page
$userData = $_SESSION['userData'];
$userCheckID = $userData['id'];
if(!isset($_SESSION['userData'])){
  header("location: index.php");
}

//ensure acct is linked to profile
$playerTable = mysqli_query($connect,"SELECT * FROM game1players WHERE id = '$userCheckID'");
$playerCount = mysqli_num_rows($playerTable);
if($playerCount != 1) {
  header("location: home.php");
}

?>