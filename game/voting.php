<!DOCTYPE html>
<?php
	include('../logic/verify.php');

	$shareMeta = query("SELECT * FROM game1shares WHERE id='$userCheckID'");
	$userShares = mysqli_fetch_array($shareMeta,MYSQLI_ASSOC);

	foreach ($userShares as $key => $value) {
		if (($key == 'id') or ($value < 1)) {
			continue;
		}
		$proposals = mysqli_fetch_array(query("SELECT * FROM game1voting WHERE company='$key'"),MYSQLI_ASSOC);
		var_dump($proposals);
	}
?>
<html>
<body>

</body>
</html>