<!DOCTYPE html>
<?php
  include('verify.php');
  $username = $userData["username"];

  //if their acct is not in this game's system, it is automatically added in. Then you are redirected to dashboard.
  $playerTable = mysqli_query($connect,"SELECT * FROM game1players WHERE id = '$userCheckID'");
  $playerCount = mysqli_num_rows($playerTable);
  if($playerCount != 1) {
    mysqli_query($connect,"INSERT INTO game1players(id,computers) VALUES('$userCheckID','20')");
    header("location: dashboard.php");
  } else {
    header("location: dashboard.php");
  }

?>
<html><body>
<p>PLACEHOLDER</p>
</body></html>