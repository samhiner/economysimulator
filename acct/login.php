<!DOCTYPE html>
<?php
	session_start();

	$connect = mysqli_connect('localhost', 'root', NULL, 'econ_data');
	if (mysqli_connect_errno($connect)) {
		echo 'Failed to connect';
	}

	//function to clean data to prevent hacking
	function dataCleaner($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	$errorMessage = "";

	//if one database entry is found set the acct info to a variable and go to home
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {	
		$username = dataCleaner($_POST['username']);
		$password = hash('ripemd160',dataCleaner($_POST['password']));

		$result = mysqli_query($connect,"SELECT * FROM users WHERE username = '$username' and password = '$password'");
		$count = mysqli_num_rows($result);
		$loginData = mysqli_fetch_array($result,MYSQLI_ASSOC);

		if($count == 1) {
			$_SESSION['userData'] = $loginData;
			header('location: home');
		} else {
			$errorMessage = 'Wrong username or password.';
		}
	}
?>
<html>
<head>
<style>
	body {
		font-family: sans-serif;
	}
	font {
		line-height:0px;
	}
	h3 {
		line-height:0px;
	}
</style>

<title>Economy Simulator</title>

</head>
<body>
<h2>Welcome to the Economy Simulator Beta - Version 0.1.0!</h2>
<font size='+1'>Log in or register below.</font> <br><br><br>

<!-- Log in form -->
<form method='post' action=''>
	Username: <br>
	<input type='text' name='username'> <br><br>
	Password: <br>
	<input type='password' name='password'> <br><br>
	<input type='submit' value='Log In'>
    <a href='register'>Register</a>
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
