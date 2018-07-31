<?php
class productDecay {

	public $newDecayDate;

	function checkProdDecay($supplyID,$supply) {
		global $timeArray;
		global $playerData;
		global $userCheckID;

		if ($playerData[$supply] > 0) {
			//if they just got a product from having none (would be from function running on commodmkt trade) update the db to be accurate and set prod to decay in a day
			if ($timeArray['haveSupply' . $supplyID] == '0') {
				$decayWhen = date('Y-m-d H:i:s', time() + (60*60*24));
				query("UPDATE game1time SET decayDate$supplyID='$decayWhen', haveSupply$supplyID='1' WHERE id='$userCheckID'");
			//if database is accurate and have product, decayDate has to have already been registered
			//so check if they have passed decayDate (and by how many days) and take according actions 
			} else {
				$decayString = $timeArray['decayDate' . $supplyID];
				$now = strtotime('now');
				$decayDate = strtotime($decayString);

				//if the current time is greater than the time the supply should decay
				if ($now - $decayDate >= 0) {
					$timeSince = $now - $decayDate;
					$daysSince = floor($timeSince / (60*60*24)) + 1;
					$newSupplies = limitZero($playerData[$supply] - $daysSince);

					//find the overdue time that was less than a day and create a new due date that is a day in advance but subtracted by that time
					//so someone can't go in every 36 hours and only lose half supplies bc unused overdue time is factored in.
					$leftover = $timeSince % 60*60*24;
					$newDecayDate = date('Y-m-d H:i:s', time() - $leftover + (60*60*24));

					$this->newDecayDate[$supplyID] = $newDecayDate;

					query("UPDATE game1players SET $supply = '$newSupplies' WHERE id='$userCheckID'");
					query("UPDATE game1time SET decayDate$supplyID = '$newDecayDate' WHERE id='$userCheckID'");

					if ($newSupplies == 0) {
						query("UPDATE game1time SET haveSupply$supplyID = '0'");
					}

					echo "<meta http-equiv='refresh' content='0'>";
					return $daysSince . ' ' . $supply;
				} else {
					$this->newDecayDate[$supplyID] = $decayDate;
				}
			}
		} else {
			//if they don't have product and the database says they do, update the database to be accurate
			if ($timeArray['haveSupply' . $supplyID] == '1') {
				query("UPDATE game1time SET haveSupply$supplyID='0' WHERE id='userCheckID'");
			}
		}
	}

	function manager($supply1,$supply2) {
		$decay1 = $this->checkProdDecay('1',$supply1);
		$decay2 = $this->checkProdDecay('2',$supply2);

		$takenAlert = '';
		
		if ($decay1 != NULL) {
			$takenAlert += '$decay1 has been used by your factory.\n';
		}
		if ($decay2 != NULL) {
			$takenAlert += '$decay2 has been used by your factory.';
		}

		if ($takenAlert != '') {
			echo "<script>alert('$takenAlert');</script>";
		}
	}

}

$globalDecay = new productDecay();
$globalDecay->manager($supply1,$supply2);

?>