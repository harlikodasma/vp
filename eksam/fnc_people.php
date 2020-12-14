<?php
	function totalPeople() {
		$notice = null;
		
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT SUM(current) FROM eksam");
		echo $conn->error;
		$stmt->bind_result($resultfromdb);
		$stmt->execute();
		if($stmt->fetch()) {
			$notice = $resultfromdb;
		}
		echo $stmt->error;
		$stmt->close();
		$conn->close();
		
		return $notice;
	}
	
	function currentInCategory($gender, $category) {
		$notice = null;
		
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT current FROM eksam WHERE gender = ? AND category = ?");
		echo $conn->error;
		$stmt->bind_param("ii", $gender, $category);
		$stmt->bind_result($resultfromdb);
		$stmt->execute();
		if($stmt->fetch()) {
			$notice = $resultfromdb;
		}
		echo $resultfromdb;
		echo $stmt->error;
		$stmt->close();
		$conn->close();
		
		return $notice;
	}
	
	function maxPeople() {
		$notice = null;
		
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT max FROM eksam WHERE gender = 0 AND category = 0");
		echo $conn->error;
		$stmt->bind_result($resultfromdb);
		$stmt->execute();
		if($stmt->fetch()) {
			$notice = $resultfromdb;
		}
		echo $resultfromdb;
		echo $stmt->error;
		$stmt->close();
		$conn->close();
		
		return $notice;
	}
	
	function maxInCategory($gender, $category) {
		$notice = null;
		
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT max FROM eksam WHERE gender = ? AND category = ?");
		echo $conn->error;
		$stmt->bind_param("ii", $gender, $category);
		$stmt->bind_result($resultfromdb);
		$stmt->execute();
		if($stmt->fetch()) {
			$notice = $resultfromdb;
		}
		echo $resultfromdb;
		echo $stmt->error;
		$stmt->close();
		$conn->close();
		
		return $notice;
	}
?>