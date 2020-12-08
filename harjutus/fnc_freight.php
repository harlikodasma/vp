<?php
	function sendAllFreights() {
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT reg_nr, enter_mass, exit_mass FROM harjutus WHERE exit_mass IS NOT NULL");
		echo $conn->error;
		$stmt->bind_result($regnr, $entermass, $exitmass);
		$stmt->execute();
		$lines = null;
		$totalmass = null;
		while($stmt->fetch()) {
			$currentmass = $entermass - $exitmass;
			$totalmass = $totalmass + $currentmass;
			
			$lines .= "<tr> \n";
			$lines .= "\t <td>" .$regnr ."</td> \n";
			$lines .= "<td>" .$entermass ." kg</td>";
			$lines .= "<td>" .$exitmass ." kg</td> \n";
			$lines .= "<td>" .$currentmass ." kg</td> \n";
			$lines .= "</tr> \n";
		}
		if(!empty($lines)) {
			$freighthtml = "<table> \n";
			$freighthtml .= "<tr> \n";
			$freighthtml .= '<th>Sõiduki numbrimärk</th>' ."\n";
			$freighthtml .= '<th>Sisenemiskaal</th>' ."\n";
			$freighthtml .= '<th>Väljumiskaal</th>' ."\n";
			$freighthtml .= '<th>Koorma kaal (sisenemiskaal miinus väljumiskaal)</th>' ."\n";
			$freighthtml .= "</tr> \n";
			$freighthtml .= $lines;
			$freighthtml .= "</table> \n <br>";
			$freighthtml .= "Veetud vilja kogumass: " .$totalmass ." kg.\n";
		}
		echo $stmt->error;
		$stmt->close();
		$conn->close();
		
		return $freighthtml;
	}
	
	function sendVehicleDropdown() {
		$vehicledropdown = null;
		
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT DISTINCT reg_nr FROM harjutus WHERE exit_mass IS NOT NULL");
		echo $conn->error;
		$stmt->bind_result($regnr);
		$stmt->execute();
		while($stmt->fetch()) {
			$vehicledropdown .= "\n \t \t" .'<option value="' .$regnr .'">' .$regnr .'</option>';
		}
		echo $stmt->error;
		$stmt->close();
		$conn->close();
		
		return $vehicledropdown;
	}
	
	function sendSpecificFreights($givenregnr) {
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT reg_nr, enter_mass, exit_mass FROM harjutus WHERE reg_nr = ? AND exit_mass IS NOT NULL");
		echo $conn->error;
		$stmt->bind_param("s", $givenregnr);
		$stmt->bind_result($regnr, $entermass, $exitmass);
		$stmt->execute();
		$lines = null;
		$totalmass = null;
		while($stmt->fetch()) {
			$currentmass = $entermass - $exitmass;
			$totalmass = $totalmass + $currentmass;
			
			$lines .= "<tr> \n";
			$lines .= "\t <td>" .$regnr ."</td> \n";
			$lines .= "<td>" .$entermass ." kg</td>";
			$lines .= "<td>" .$exitmass ." kg</td> \n";
			$lines .= "<td>" .$currentmass ." kg</td> \n";
			$lines .= "</tr> \n";
		}
		if(!empty($lines)) {
			$freighthtml = "<table> \n";
			$freighthtml .= "<tr> \n";
			$freighthtml .= '<th>Sõiduki numbrimärk</th>' ."\n";
			$freighthtml .= '<th>Sisenemiskaal</th>' ."\n";
			$freighthtml .= '<th>Väljumiskaal</th>' ."\n";
			$freighthtml .= '<th>Koorma kaal (sisenemiskaal miinus väljumiskaal)</th>' ."\n";
			$freighthtml .= "</tr> \n";
			$freighthtml .= $lines;
			$freighthtml .= "</table> \n <br>";
			$freighthtml .= "Selle sõidukiga veetud vilja kogumass: " .$totalmass ." kg.\n";
		}
		echo $stmt->error;
		$stmt->close();
		$conn->close();
		
		return $freighthtml;
	}
	
	function sendEditableVehicleDropdown() {
		$vehicledropdown = null;
		
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT reg_nr, veo_id, enter_mass FROM harjutus WHERE exit_mass IS NULL");
		echo $conn->error;
		$stmt->bind_result($regnr, $veoid, $enter_mass);
		$stmt->execute();
		while($stmt->fetch()) {
			$vehicledropdown .= "\n \t \t" .'<option value="' .$veoid .'">' .$regnr .' sisenemiskaaluga ' .$enter_mass .' kg' .'</option>';
		}
		echo $stmt->error;
		$stmt->close();
		$conn->close();
		
		return $vehicledropdown;
	}
	
	function editFright($exitmass, $veoid) {
		$notice = null;
		
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("UPDATE harjutus SET exit_mass = ? WHERE veo_id = ?");
		echo $conn->error;
		$stmt->bind_param("si", $exitmass, $veoid);
		if($stmt->execute()) {
			$notice = 1;
		}
		echo $stmt->error;
		$stmt->close();
		$conn->close();
		
		return $notice;
	}
?>