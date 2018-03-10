<!DOCTYPE html><html>
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
    Username:<br><input type="text" name="username" onkeyup="userVal()" required><br><br>
    Email Address: <br><input type="text" name="email" onkeyup="emailVal()" required><br><br>
    Password:<br><input type="password" name="password" onkeyup="passVal()" required><br><br>
    Confirm Password:<br><input type="password" name="passwordconf" onkeyup="passVal()" required><br><br>
    
    <input type="submit" value="Submit" disabled name="button1" id="submitButton">
  </form>
  
  <!-- Where it is displayed that passwords do not match.-->
  <p id="noPassMatch"></p>
</body>
</html>
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
  
  /*Hides and shows submit button based on fields being filled in*/
  function userVal() {
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
<?php

//when submit button is pressed
if (isset($_POST['button1']))
{

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

}
?>
