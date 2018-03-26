<!DOCTYPE html>
<?php
  session_start();

  $connect=mysqli_connect('localhost', 'root', 'root', 'econ_data');
  if(mysqli_connect_errno($connect)) {
  echo 'Failed to connect'; }

  $username = dataCleaner($_POST['username']);
  $password = dataCleaner($_POST['password']);

  //function to clean data to prevent hacking
  function dataCleaner($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  $result = mysqli_query($connect,"SELECT * FROM users WHERE username = '$username' and password = '$password'");
  $count = mysqli_num_rows($result);

  //finds ID of the acct you logged into for session verification later
  $loginData = mysqli_fetch_array($result,MYSQLI_ASSOC);

  //if one database entry is found set the acct info to a variable and go to the dashboard
  if($count == 1) {
    $_SESSION['userData'] = $loginData;
    header("location: dashboard.php");
  } else {
    //if one acct not found and data was submitted give error message
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $errorMessage = "Wrong username or password.";
    }
  }
?>
<html>
<head>
<style>
body {font-family: sans-serif;}
/* means those fonts have no vertical padding */
h2{
    line-height:0px;
}
font{
    line-height:0px;
}
h3{
    line-height:0px;
}
</style>
</head>
<body>
<h2>Welcome to the Economy Simulator Beta - Version 0.1.0!</h2>
<font size="+1">Log in or register below.</font> <br><br><br>

<!-- Log in form -->
<form method="post" action="">
	Username: <br>
	<input type="text" name="username"> <br><br>
	Password: <br>
	<input type="password" name="password"> <br><br>
	<input type="submit" value="Submit">
    <a href="register.php">Register</a>
</form>
	
<?php echo $errorMessage; ?>

<br><br><br><br><br><h2>Release Notes</h2>
<br><h3>Version 0.1.0</h3>
<p>Enter Release Notes here.  </p>
<ul>
	<li>This</li>
	<li>That</li>
	<li>The Other</li>
</ul>

</body>
</html>
