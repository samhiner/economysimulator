<!DOCTYPE html>
<?php
  //defines connecting to database to a variable
  $connect=mysqli_connect('localhost','root','root','econ_data');
  //checks mysql database connection
  if(mysqli_connect_errno($connect)) {
    echo 'Failed to connect';
  }
  //defines the act of "putting the contents of the text boxes through the data cleaner" to a variable
  $cleanUsername = dataCleaner($_POST["username"]);
  $cleanEmail = dataCleaner($_POST["email"]);
  $cleanPassword = dataCleaner($_POST["password"]);
    
  //function to clean data to prevent hacking
  function dataCleaner($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  //sees if there is an account with your username
  $result = mysqli_query($connect,"SELECT * FROM users WHERE username = '$cleanUsername'");
  $count = mysqli_num_rows($result);

  //if the form was submitted and is not duplicate then send the data to the database
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if($count == 0) {
      mysqli_query($connect,"INSERT INTO users(username,password,email) VALUES('$cleanUsername','$cleanPassword','$cleanEmail')");
      header("location: registerthankyou.php");
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
    
    /* can make stuff invisible/not invisible */
    .hidden{
      display:none;
    }
  </style>
  <title>Register</title>
</head>
<body>
  <h2>Register</h2>
  
  <!--Enter credentials-->
  <form method="post" action="">
    Username:<br><input type="text" name="username" onkeyup="userVal()" onkeyup ="" required><br><br>
    Email Address: <br><input type="text" name="email" onkeyup="emailVal()" required><br><br>
    Password:<br><input type="password" name="password" onkeyup="passVal()" required><br><br>
    Confirm Password:<br><input type="password" name="passwordconf" onkeyup="passVal()" required><br><br>
    
    <input type="submit" value="Submit" disabled name="button1" id="submitButton">
  </form>
  
  <div id='errorField'><?php echo $errorMessage; ?></div>

  <!-- Where it is displayed that passwords do not match.-->
  <p id="noPassMatch"></p>
</body></html>
<script>
  /*Assigns variables to contents of text boxes*/
  var user = document.getElementsByName("username")[0];
  var email = document.getElementsByName("email")[0];  
  var pass = document.getElementsByName("password")[0];
  var confPass = document.getElementsByName("passwordconf")[0];
  
  /*disables submit button by default*/
  document.getElementById('submitButton').disabled = true;
  /*Hides and shows submit button/error message based on passwords being the same and not being empty*/
  function passVal() {
    if (pass.value != confPass.value) {
      document.getElementById('noPassMatch').innerHTML = "Passwords do not match";
      document.getElementById('submitButton').disabled = true;
      document.getElementById("noPassMatch").classList.remove('hidden');
    } else {
      document.getElementById("noPassMatch").classList.add('hidden');
      document.getElementById('submitButton').disabled = false;
    }
    if (pass.value.length < 1) {
      document.getElementById('submitButton').disabled = true;
    }
  }
  
  /*Hides and shows submit button based on fields being filled in. Also always hides error message.*/
  function userVal() {
    document.getElementById("errorField").classList.add('hidden');
    if (user.value.length < 1) {
      document.getElementById('submitButton').disabled = true;
    } else {
      passVal()
    }
  }
  function emailVal() {
    if (email.value.length < 1) {
      document.getElementById('submitButton').disabled = true;
    } else {
      passVal()
    }
  }
</script>
