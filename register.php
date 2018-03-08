<!DOCTYPE html>
<html>
<head>
<style>
label{display:inline-block;width:100px;margin-bottom:10px;}
</style>


<title>Add Employee</title>
</head>
<body>

<form method="post" action="">
<label>First Name</label>
<input type="text" name="first_name" />
<br />
<label>Last Name</label>
<input type="text" name="last_name" />
<br />
<label>Department</label>
<input type="text" name="department" />
<br />
<label>Email</label>
<input type="text" name="email" />

<br />
<input type="submit" name="button1" value="Add Employee">
</form>

</body>
</html>
<?php
if (isset($_POST['button1']))
{
include 'database.php';

// create a variable
$username=$_POST['first_name'];
$password=$_POST['last_name'];
$user_email=$_POST['department'];
$first_name=$_POST['email'];

//Execute the query

mysqli_query($connect,"INSERT INTO users(username,password,user_email,first_name) VALUES('$username','$password','$user_email','$first_name')");
}
?>