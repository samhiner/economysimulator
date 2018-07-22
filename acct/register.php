<!DOCTYPE html>
<?php
	$connect = mysqli_connect('localhost', 'root', NULL, 'econ_data');
	if (mysqli_connect_errno($connect)) {
		echo 'Failed to connect';
	}
    
	$errorMessage = NULL;
	$hideAll = NULL;
	$hideThank = NULL;

	//function to clean data to prevent hacking
	function dataCleaner($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	//if the form was submitted and is not duplicate then send the data to the database
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		//"clean" all inputs to prevent sql injection and hash password
		$cleanUsername = dataCleaner($_POST["username"]);
		$cleanPassword = hash('ripemd160',dataCleaner($_POST["password"]));

		$result = mysqli_query($connect,"SELECT * FROM users WHERE username = '$cleanUsername'");
		$count = mysqli_num_rows($result);

		if($count == 0) {
			mysqli_query($connect,"INSERT INTO users(username,password) VALUES('$cleanUsername','$cleanPassword')");
			$hideAll = "<script>document.getElementById('all').style.display = 'none';</script>";
			$hideThank = "<script>document.getElementById('thank').style.display = 'block';</script>";
		} else {
			$errorMessage = "An account with this username already exists. Please choose another username.";
		}
	}
?>
<html>
<head>
<style>
	body {
		font-family: sans-serif;
	}
	.noVis {
		display: none;
	}
</style>

<title>Register | Economy Simulator</title>

</head>
<body>

<div id='all'>
  <h2>Register</h2>
  
  <!--Enter credentials-->
  <form method="post" action="">
    Username:<br><input type="text" name="username" onkeyup="genVal('user')" onkeyup ="" required><br><br>
    Password:<br><input type="password" name="password" onkeyup="passVal()" required><br><br>
    Confirm Password:<br><input type="password" name="passwordconf" onkeyup="passVal()" required><br><br>
    
    <input type="submit" value="Submit" disabled name="button1" id="submitButton">
  </form>
</div>

<div id='errorField'><?php echo $errorMessage; ?></div>

<!-- Where it is displayed that passwords do not match.-->
<p id="noPassMatch" class='noVis'>Passwords do not match</p>
  
<div id='thank' class='noVis'>
  <p>Thank you for registering for The Economy Simulator. <a href='http://localhost/economysimulator/acct/login.php'>Log in</a> using the credentials you entered here.</p>
</div>

<?php
echo $hideAll;
echo $hideThank;
?>

</body>
</html>
<script>
//disables submit button by default
document.getElementById('submitButton').disabled = true;
//Hides and shows submit button/error message based on passwords being the same and not being empty
function passVal() {
	var pass = document.getElementsByName("password")[0];
	var confPass = document.getElementsByName("passwordconf")[0];
	if (pass.value != confPass.value) {
		document.getElementById('submitButton').disabled = true;
		document.getElementById("noPassMatch").style.display = 'block';
	} else {
		document.getElementById("noPassMatch").style.display = 'none';
		document.getElementById('submitButton').disabled = false;
	}
	if (pass.value.length < 1) {
		document.getElementById('submitButton').disabled = true;
	}
}

/*Hides and shows submit button based on fields being filled in. Also hides error message by default.*/
function genVal(type) {
	document.getElementById("errorField").classList.add('hidden');
	if (type.value.length < 1) {
		document.getElementById('submitButton').disabled = true;
	} else {
		passVal()
	}
}

</script>
