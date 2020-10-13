<?php
	$database = "if20_harli_kod_1";
	//var_dump($GLOBALS);
	//funktsioon, mis loeb kõikide filmide info
	function readfilms() {
		//loen lehele kõik olemasolevad mõtted
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		//$stmt = $conn->prepare("SELECT pealkiri, aasta, kestus, zanr, tootja, lavastaja FROM film");
		$stmt = $conn->prepare("SELECT * FROM film");
		echo $conn->error;
		//seome tulemuse muutujaga
		$stmt->bind_result($titlefromdb, $yearfromdb, $durationfromdb, $genrefromdb, $studiofromdb, $directorfromdb);
		$stmt->execute();
		echo $stmt->error;
	
		$filmhtml = "\t <ol> \n";
		while($stmt->fetch()) {
			$filmhtml .= "\t \t <li>" .$titlefromdb ." \n";
			$filmhtml .= "\t \t \t <ul> \n";
			$filmhtml .= "\t \t \t \t <li>Valmimisaasta: " .$yearfromdb ."</li> \n";
			$filmhtml .= "\t \t \t \t <li>Kestus minutites: " .$durationfromdb ." minutit</li> \n";
			$filmhtml .= "\t \t \t \t <li>Žanr: " .$genrefromdb ."</li> \n";
			$filmhtml .= "\t \t \t \t <li>Tootja: " .$studiofromdb ."</li> \n";
			$filmhtml .= "\t \t \t \t <li>Lavastaja: " .$directorfromdb ."</li> \n";
			$filmhtml .= "\t \t \t </ul> \n";
			$filmhtml .= "\t \t </li> \n";
		}
		$filmhtml .= "\t </ol> \n";
	
		$stmt->close();
		$conn->close();
		return $filmhtml;
	}
	function savefilm($titleinput, $yearinput, $durationinput, $genreinput, $studioinput, $directorinput) {
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		//valmistan ette sql käsu
		$stmt = $conn->prepare("INSERT INTO film (pealkiri, aasta, kestus, zanr, tootja, lavastaja) VALUES (?, ?, ?, ?, ?, ?)");
		echo $conn->error; //ütleb kui on db error
		//seome käsuga päris andmed
		//i - integer, d - decimal, s - string
		$stmt->bind_param("siisss", $titleinput, $yearinput, $durationinput, $genreinput, $studioinput, $directorinput);
		$stmt->execute();
		echo $stmt->error;
		$stmt->close();
		$conn->close();	
	}
	
	function readpersonsinfilm($sortby, $sortorder) {
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);

		$SQLsentence = "SELECT first_name, last_name, role, title FROM person JOIN person_in_movie ON person.person_id = person_in_movie.person_id JOIN movie ON movie.movie_id = person_in_movie.movie_id";
		if($sortby == 0 and $sortorder == 0) {
			$stmt = $conn->prepare($SQLsentence);
		}
		if($sortby == 4){
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY title DESC");
			} else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY title");
			}
		}

		echo $conn->error;
		$stmt->bind_result($firstnamefromdb, $lastnamefromdb, $rolefromdb, $titlefromdb);
		$stmt->execute();
		$lines = null;
		while($stmt->fetch()) {
			$lines .= "<tr> \n";
			$lines .= "\t <td>" .$firstnamefromdb ." " .$lastnamefromdb ."</td> \n";
			$lines .= "<td>" .$rolefromdb ."</td>";
			$lines .= "<td>" .$titlefromdb ."</td> \n";
			$lines .= "</tr> \n";
		}
		if(!empty($lines)) {
			$notice = "<table> \n";
			$notice .= "<tr> \n";
			$notice .= "<th>Isiku nimi</th> \n";
			$notice .= "<th>Roll filmis</th> \n";
			$notice .= '<th>Film &nbsp;<a href="?sortby=4&sortorder=1">&uarr;</a> &nbsp;<a href="?sortby=4&sortorder=2">&darr;</a> </th>' ."\n"; //&nbsp; - non breakable space (kõva tühik) | &uarr; - up arrow | &darr; - down arrow
			$notice .= "</tr> \n";
			$notice .= $lines;
			$notice .= "</table> \n";
		}
		echo $stmt->error;
		$stmt->close();
		$conn->close();	
		return $notice;
	}
	
	function old_version_readpersonsinfilm() {
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT first_name, last_name, role, title FROM person JOIN person_in_movie ON person.person_id = person_in_movie.person_id JOIN movie ON movie.movie_id = person_in_movie.movie_id");
		echo $conn->error;
		$stmt->bind_result($firstnamefromdb, $lastnamefromdb, $rolefromdb, $titlefromdb);
		$stmt->execute();
		$lines = null;
		while($stmt->fetch()) {
			$lines .= "<p>" .$firstnamefromdb ." " .$lastnamefromdb;
			if(!empty($rolefromdb)) {
				$lines .= " on tegelane " .$rolefromdb;
			}
			$lines .=  ' filmis "' .$titlefromdb .'".</p>' ."\n";
		}
		if(!empty($lines)) {
			$notice = $lines;
		}
		echo $stmt->error;
		$stmt->close();
		$conn->close();	
		return $notice;
	}
?>