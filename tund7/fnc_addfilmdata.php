<?php
	$database = "if20_harli_kod_1";
	
	function addperson($firstname, $lastname, $birthdate) {
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT first_name FROM person WHERE (first_name = ? AND last_name = ?)");
		echo $conn->error;
		$stmt->bind_param("ss", $firstname, $lastname);
		$stmt->bind_result($existingfirstname);
		if($stmt->execute()) {
			if($stmt->fetch()) {
				$notice = "See isik on juba andmebaasis!";
			} else {
				$stmt->close();
				$stmt = $conn->prepare("INSERT INTO person (first_name, last_name, birth_date) VALUES (?, ?, ?)");
				echo $conn->error;
				$stmt->bind_param("sss", $firstname, $lastname, $birthdate);
				if($stmt->execute()) {
					$notice = "Isiku info salvestatud!";
				} else {
					$notice = $stmt->error;
				}
			}
		} else {
			$notice = $stmt->error;
		}
		$stmt->close();
		$conn->close();
		
		return $notice;
	}
	
	function addfilm($moviename, $movieyear, $movieduration, $description) {
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT title FROM movie WHERE title = ?");
		echo $conn->error;
		$stmt->bind_param("s", $moviename);
		$stmt->bind_result($existingmovie);
		if($stmt->execute()) {
			if($stmt->fetch()) {
				$notice = "See film on juba andmebaasis!";
			} else {
				$stmt->close();
				$stmt = $conn->prepare("INSERT INTO movie (title, production_year, duration, description) VALUES (?, ?, ?, ?)");
				echo $conn->error;
				$stmt->bind_param("siis", $moviename, $movieyear, $movieduration, $description);
				if($stmt->execute()) {
					$notice = "Filmi info salvestatud!";
				} else {
					$notice = $stmt->error;
				}
			}
		} else {
			$notice = $stmt->error;
		}
		$stmt->close();
		$conn->close();
		
		return $notice;
	}
	
	function addposition($positionname, $positiondescription) {
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT position_name FROM position WHERE position_name = ?");
		echo $conn->error;
		$stmt->bind_param("s", $positionname);
		$stmt->bind_result($existingpositionname);
		if($stmt->execute()) {
			if($stmt->fetch()) {
				$notice = "See positsioon on juba andmebaasis!";
			} else {
				$stmt->close();
				$stmt = $conn->prepare("INSERT INTO position (position_name, description) VALUES (?, ?)");
				echo $conn->error;
				$stmt->bind_param("ss", $positionname, $positiondescription);
				if($stmt->execute()) {
					$notice = "Positsioon salvestatud!";
				} else {
					$notice = $stmt->error;
				}
			}
		} else {
			$notice = $stmt->error;
		}
		$stmt->close();
		$conn->close();
		
		return $notice;
	}
	
	function addgenre($genre, $genredescription) {
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT genre_name FROM genre WHERE genre_name = ?");
		echo $conn->error;
		$stmt->bind_param("s", $genre);
		$stmt->bind_result($existinggenrename);
		if($stmt->execute()) {
			if($stmt->fetch()) {
				$notice = "See žanr on juba andmebaasis!";
			} else {
				$stmt->close();
				$stmt = $conn->prepare("INSERT INTO genre (genre_name, description) VALUES (?, ?)");
				echo $conn->error;
				$stmt->bind_param("ss", $genre, $genredescription);
				if($stmt->execute()) {
					$notice = "Žanr salvestatud!";
				} else {
					$notice = $stmt->error;
				}
			}
		} else {
			$notice = $stmt->error;
		}
		$stmt->close();
		$conn->close();
		
		return $notice;
	}
	
	function addcompany($companyname, $companyaddress) {
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT company_name FROM production_company WHERE company_name = ?");
		echo $conn->error;
		$stmt->bind_param("s", $companyname);
		$stmt->bind_result($existingcompanyname);
		if($stmt->execute()) {
			if($stmt->fetch()) {
				$notice = "See filmitootja on juba andmebaasis!";
			} else {
				$stmt->close();
				$stmt = $conn->prepare("INSERT INTO production_company (company_name, company_address) VALUES (?, ?)");
				echo $conn->error;
				$stmt->bind_param("ss", $companyname, $companyaddress);
				if($stmt->execute()) {
					$notice = "Filmitootja salvestatud!";
				} else {
					$notice = $stmt->error;
				}
			}
		} else {
			$notice = $stmt->error;
		}
		$stmt->close();
		$conn->close();
		
		return $notice;
	}
	
?>