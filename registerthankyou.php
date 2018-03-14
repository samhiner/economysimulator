<!DOCTYPE html><html>
<body>hey</body>
</html>
<?php
  //connect to the database
  $connect=mysqli_connect('localhost','root','root','econ_data');
  if(mysqli_connect_errno($connect))
  {
    echo 'Failed to connect';
  }
  //assign variable to contents of text boxes
  $username=$_POST['username'];
  $email=$_POST['email'];
  $password=$_POST['password'];
  //Send variable which equals contents of text boxes to database
  mysqli_query($connect,"INSERT INTO users(username,password,email) VALUES('$username','$password','$email')");
?>
