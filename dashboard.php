<!DOCTYPE html>
<?php
  include('verify.php');
  $username = $userData["username"];
?>
<html><body>
<p>Welcome <?php echo $username; ?>.</p>
<h3>Your Computers</h3>
<?php echo $godComp; ?>
<h3>God's Computers</h3>
<?php echo $yourComp; ?><br>

<form method='post' id='sell'>
  <input type="text" value="Amount"><br>
  <input type="submit" value="Sell to God"><br><br><br>
</form>

<form method='post' id='buy'>
  <input type="text" value="Amount"><br>
  <input type="submit" value="Buy from God">
</form>
</body></html>