<?php
$connect=mysqli_connect('localhost','root','root','learning_over_here');

if(mysqli_connect_errno($connect))
{
		echo 'Failed to connect';
}

?>