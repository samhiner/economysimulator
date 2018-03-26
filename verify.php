<html><body>Session Verified!</body></html>
<?php
  session_start();
  $connect=mysqli_connect('localhost', 'root', 'root', 'econ_data');
  if(mysqli_connect_errno($connect)) {
  echo 'Failed to connect'; }

  //assign acct data to variable
  $userData = $_SESSION['userData'];

  //send user to log in page if the variable set during login is not set
  if(!isset($_SESSION['userData'])){

    header("location: index.php");
  }
  
  //search for an account which matches the login variable, if there is none send user to log in page (so old sessions know if acct is deleted)
  $userCheckID = $userData["id"];
  $userTable = mysqli_query($connect,"SELECT * FROM users WHERE id = '$userCheckID'");
  $userCount = mysqli_num_rows($userTable);
  if($userCount != 1) {
    header("location: index.php");
  }
?>