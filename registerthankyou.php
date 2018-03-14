<!DOCTYPE html><html>
<body>hey</body>
</html>
<?php
  //defines connecting to database to a variable
  $connect=mysqli_connect('localhost','root','root','econ_data');

  //checks mysql database connection
  if(mysqli_connect_errno($connect)) {
    echo 'Failed to connect';
  }

  //defines the act of "putting the contents of the text boxes through the data cleaner" to a variable
  $cleanUsername = dataCleaner($_POST["username"])
  $cleanEmail = dataCleaner($_POST["email"])
  $cleanPassword = dataCleaner($_POST["password"])
    
  //function to clean data to prevent hacking
  function dataCleaner($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  //Send variable which equals contents of text boxes to database
  mysqli_query($connect,"INSERT INTO users(username,password,email) VALUES('$cleanUsername','$cleanPassword','$cleanEmail')");
?>
