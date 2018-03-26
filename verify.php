<html><body>Session Verified!</body></html>
<?php
  session_start();
  $connect=mysqli_connect('localhost', 'root', 'root', 'econ_data');
  if(mysqli_connect_errno($connect)) {
  echo 'Failed to connect'; }

  //send user to log in page if the variable set during login is not set
  if(!isset($_SESSION['userData'])){
    header("index.php");
  }

  //assign acct data to variable
  $userData = $_SESSION['userData'];
  
  //search for an account which matches the login variable, if there is none send user to log in page (so old sessions know if acct is deleted)
  $userCheckID = $userData["id"];
  $result = mysqli_query($connect,"SELECT id FROM users WHERE id = '$userCheckID'");
  $count = mysqli_num_rows($result);
  if($count != 1) {
    header("index.php");
  }
?>