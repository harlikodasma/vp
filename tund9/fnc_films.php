<?php
	$database = "if20_harli_kod_1";
	//var_dump($GLOBALS);
	//funktsioon, mis loeb kõikide filmide info
	function readfilms() {
		//loen lehele kõik olemasolevad mõtted
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		//$stmt = $conn->prepare("SELECT pealkiri, aasta, kestus, zanr, tootja, lavastaja FROM film");
		$conn->set_charset("utf8");
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
		$conn->set_charset("utf8");
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
		$conn->set_charset("utf8");
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
		
		if($sortby == 3){
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY role DESC");
			} else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY role");
			}
		}
		
		if($sortby == 2){
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY last_name DESC");
			} else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY last_name");
			}
		}
		
		if($sortby == 1){
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY first_name DESC");
			} else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY first_name");
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
			$notice .= '<th>Isiku nimi &nbsp; E <a href="?sortby=1&sortorder=1">&uarr;</a> &nbsp;<a href="?sortby=1&sortorder=2">&darr;</a> &nbsp; P <a href="?sortby=2&sortorder=1">&uarr;</a> &nbsp;<a href="?sortby=2&sortorder=2">&darr;</a> </th>' ."\n";
			$notice .= '<th>Roll filmis &nbsp;<a href="?sortby=3&sortorder=1">&uarr;</a> &nbsp;<a href="?sortby=3&sortorder=2">&darr;</a> </th>' ."\n";
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
	
	function readpersons($sortby, $sortorder) {
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$SQLsentence = "SELECT first_name, last_name, birth_date FROM person";
		if($sortby == 0 and $sortorder == 0) {
			$stmt = $conn->prepare($SQLsentence);
		}

		if($sortby == 3){
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY birth_date DESC");
			} else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY birth_date");
			}
		}
		
		if($sortby == 2){
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY last_name DESC");
			} else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY last_name");
			}
		}
		
		if($sortby == 1){
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY first_name DESC");
			} else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY first_name");
			}
		}

		echo $conn->error;
		$stmt->bind_result($firstnamefromdb, $lastnamefromdb, $birthdatefromdb);
		$stmt->execute();
		$lines = null;
		while($stmt->fetch()) {
			$lines .= "<tr> \n";
			$lines .= "\t <td>" .$firstnamefromdb ."</td> \n";
			$lines .= "<td>" .$lastnamefromdb ."</td> \n";
			$lines .= "<td>" .$birthdatefromdb ."</td> \n";
			$lines .= "</tr> \n";
		}
		if(!empty($lines)) {
			$notice = "<table> \n";
			$notice .= "<tr> \n";
			$notice .= '<th>Eesnimi &nbsp;<a href="?sortby=1&sortorder=1">&uarr;</a> &nbsp;<a href="?sortby=1&sortorder=2">&darr;</a>' ."\n";
			$notice .= '<th>Perenimi <a href="?sortby=2&sortorder=1">&uarr;</a> &nbsp;<a href="?sortby=2&sortorder=2">&darr;</a> </th>' ."\n";
			$notice .= '<th>Sünnikuupäev &nbsp;<a href="?sortby=3&sortorder=1">&uarr;</a> &nbsp;<a href="?sortby=3&sortorder=2">&darr;</a> </th>' ."\n";
			$notice .= "</tr> \n";
			$notice .= $lines;
			$notice .= "</table> \n";
		}
		echo $stmt->error;
		$stmt->close();
		$conn->close();	
		return $notice;
	}
	
	function readpositions($sortby, $sortorder) {
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$SQLsentence = "SELECT position_name, description FROM position";
		if($sortby == 0 and $sortorder == 0) {
			$stmt = $conn->prepare($SQLsentence);
		}

		if($sortby == 2){
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY description DESC");
			} else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY description");
			}
		}
		
		if($sortby == 1){
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY position_name DESC");
			} else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY position_name");
			}
		}

		echo $conn->error;
		$stmt->bind_result($positionnamefromdb, $descriptionfromdb);
		$stmt->execute();
		$lines = null;
		while($stmt->fetch()) {
			$lines .= "<tr> \n";
			$lines .= "\t <td>" .$positionnamefromdb ."</td> \n";
			$lines .= "<td>" .$descriptionfromdb ."</td> \n";
			$lines .= "</tr> \n";
		}
		if(!empty($lines)) {
			$notice = "<table> \n";
			$notice .= "<tr> \n";
			$notice .= '<th>Positsioon &nbsp;<a href="?sortby=1&sortorder=1">&uarr;</a> &nbsp;<a href="?sortby=1&sortorder=2">&darr;</a>' ."\n";
			$notice .= '<th>Kirjeldus &nbsp;<a href="?sortby=2&sortorder=1">&uarr;</a> &nbsp;<a href="?sortby=2&sortorder=2">&darr;</a> </th>' ."\n";
			$notice .= "</tr> \n";
			$notice .= $lines;
			$notice .= "</table> \n";
		}
		echo $stmt->error;
		$stmt->close();
		$conn->close();	
		return $notice;
	}
	
	function readmovies($sortby, $sortorder) {
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$SQLsentence = "SELECT title, production_year, duration, description FROM movie";
		if($sortby == 0 and $sortorder == 0) {
			$stmt = $conn->prepare($SQLsentence);
		}

		if($sortby == 4){
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY description DESC");
			} else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY description");
			}
		}

		if($sortby == 3){
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY duration DESC");
			} else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY duration");
			}
		}

		if($sortby == 2){
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY production_year DESC");
			} else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY production_year");
			}
		}
		
		if($sortby == 1){
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY title DESC");
			} else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY title");
			}
		}

		echo $conn->error;
		$stmt->bind_result($titlefromdb, $productionyearfromdb, $durationfromdb, $descriptionfromdb);
		$stmt->execute();
		$lines = null;
		while($stmt->fetch()) {
			$lines .= "<tr> \n";
			$lines .= "\t <td>" .$titlefromdb ."</td> \n";
			$lines .= "<td>" .$productionyearfromdb ."</td> \n";
			$lines .= "<td>" .$durationfromdb ."</td> \n";
			$lines .= "<td>" .$descriptionfromdb ."</td> \n";
			$lines .= "</tr> \n";
		}
		if(!empty($lines)) {
			$notice = "<table> \n";
			$notice .= "<tr> \n";
			$notice .= '<th>Pealkiri &nbsp;<a href="?sortby=1&sortorder=1">&uarr;</a> &nbsp;<a href="?sortby=1&sortorder=2">&darr;</a>' ."\n";
			$notice .= '<th>Tootmisaasta &nbsp;<a href="?sortby=2&sortorder=1">&uarr;</a> &nbsp;<a href="?sortby=2&sortorder=2">&darr;</a> </th>' ."\n";
			$notice .= '<th>Pikkus minutites &nbsp;<a href="?sortby=3&sortorder=1">&uarr;</a> &nbsp;<a href="?sortby=3&sortorder=2">&darr;</a>' ."\n";
			$notice .= '<th>Kirjeldus &nbsp;<a href="?sortby=4&sortorder=1">&uarr;</a> &nbsp;<a href="?sortby=4&sortorder=2">&darr;</a>' ."\n";
			$notice .= "</tr> \n";
			$notice .= $lines;
			$notice .= "</table> \n";
		}
		echo $stmt->error;
		$stmt->close();
		$conn->close();	
		return $notice;
	}
	
	function readgenres($sortby, $sortorder) {
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$SQLsentence = "SELECT genre_name, description FROM genre";
		if($sortby == 0 and $sortorder == 0) {
			$stmt = $conn->prepare($SQLsentence);
		}

		if($sortby == 2){
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY description DESC");
			} else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY description");
			}
		}
		
		if($sortby == 1){
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY genre_name DESC");
			} else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY genre_name");
			}
		}

		echo $conn->error;
		$stmt->bind_result($genrefromdb, $descriptionfromdb);
		$stmt->execute();
		$lines = null;
		while($stmt->fetch()) {
			$lines .= "<tr> \n";
			$lines .= "\t <td>" .$genrefromdb ."</td> \n";
			$lines .= "<td>" .$descriptionfromdb ."</td> \n";
			$lines .= "</tr> \n";
		}
		if(!empty($lines)) {
			$notice = "<table> \n";
			$notice .= "<tr> \n";
			$notice .= '<th>Žanr &nbsp;<a href="?sortby=1&sortorder=1">&uarr;</a> &nbsp;<a href="?sortby=1&sortorder=2">&darr;</a>' ."\n";
			$notice .= '<th>Kirjeldus &nbsp;<a href="?sortby=2&sortorder=1">&uarr;</a> &nbsp;<a href="?sortby=2&sortorder=2">&darr;</a> </th>' ."\n";
			$notice .= "</tr> \n";
			$notice .= $lines;
			$notice .= "</table> \n";
		}
		echo $stmt->error;
		$stmt->close();
		$conn->close();	
		return $notice;
	}
	
	function readproductioncompanies($sortby, $sortorder) {
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$SQLsentence = "SELECT company_name, company_address FROM production_company";
		if($sortby == 0 and $sortorder == 0) {
			$stmt = $conn->prepare($SQLsentence);
		}

		if($sortby == 2){
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY company_address DESC");
			} else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY company_address");
			}
		}
		
		if($sortby == 1){
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY company_name DESC");
			} else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY company_name");
			}
		}

		echo $conn->error;
		$stmt->bind_result($companynamefromdb, $companyaddressfromdb);
		$stmt->execute();
		$lines = null;
		while($stmt->fetch()) {
			$lines .= "<tr> \n";
			$lines .= "\t <td>" .$companynamefromdb ."</td> \n";
			$lines .= "<td>" .$companyaddressfromdb ."</td> \n";
			$lines .= "</tr> \n";
		}
		if(!empty($lines)) {
			$notice = "<table> \n";
			$notice .= "<tr> \n";
			$notice .= '<th>Firma nimi &nbsp;<a href="?sortby=1&sortorder=1">&uarr;</a> &nbsp;<a href="?sortby=1&sortorder=2">&darr;</a>' ."\n";
			$notice .= '<th>Firma aadress &nbsp;<a href="?sortby=2&sortorder=1">&uarr;</a> &nbsp;<a href="?sortby=2&sortorder=2">&darr;</a> </th>' ."\n";
			$notice .= "</tr> \n";
			$notice .= $lines;
			$notice .= "</table> \n";
		}
		echo $stmt->error;
		$stmt->close();
		$conn->close();	
		return $notice;
	}
	
	function readmoviesandgenres($sortby, $sortorder) {
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$SQLsentence = "SELECT title, genre_name FROM movie JOIN movie_genre ON movie_genre.movie_id = movie.movie_id JOIN genre ON movie_genre.genre_id = genre.genre_id";
		if($sortby == 0 and $sortorder == 0) {
			$stmt = $conn->prepare($SQLsentence);
		}

		if($sortby == 2){
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY genre_name DESC");
			} else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY genre_name");
			}
		}
		
		if($sortby == 1){
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY title DESC");
			} else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY title");
			}
		}

		echo $conn->error;
		$stmt->bind_result($titlefromdb, $genrefromdb);
		$stmt->execute();
		$lines = null;
		while($stmt->fetch()) {
			$lines .= "<tr> \n";
			$lines .= "\t <td>" .$titlefromdb ."</td> \n";
			$lines .= "<td>" .$genrefromdb ."</td> \n";
			$lines .= "</tr> \n";
		}
		if(!empty($lines)) {
			$notice = "<table> \n";
			$notice .= "<tr> \n";
			$notice .= '<th>Filmi pealkiri &nbsp;<a href="?sortby=1&sortorder=1">&uarr;</a> &nbsp;<a href="?sortby=1&sortorder=2">&darr;</a>' ."\n";
			$notice .= '<th>Žanr &nbsp;<a href="?sortby=2&sortorder=1">&uarr;</a> &nbsp;<a href="?sortby=2&sortorder=2">&darr;</a> </th>' ."\n";
			$notice .= "</tr> \n";
			$notice .= $lines;
			$notice .= "</table> \n";
		}
		echo $stmt->error;
		$stmt->close();
		$conn->close();	
		return $notice;
	}
	
	function readmoviesandcompanies($sortby, $sortorder) {
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$SQLsentence = "SELECT title, company_name FROM movie JOIN movie_by_production_company ON movie_by_production_company.movie_movie_id = movie.movie_id JOIN production_company ON movie_by_production_company.production_company_id = production_company.production_company_id";
		if($sortby == 0 and $sortorder == 0) {
			$stmt = $conn->prepare($SQLsentence);
		}

		if($sortby == 2){
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY company_name DESC");
			} else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY company_name");
			}
		}
		
		if($sortby == 1){
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY title DESC");
			} else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY title");
			}
		}

		echo $conn->error;
		$stmt->bind_result($titlefromdb, $companyfromdb);
		$stmt->execute();
		$lines = null;
		while($stmt->fetch()) {
			$lines .= "<tr> \n";
			$lines .= "\t <td>" .$titlefromdb ."</td> \n";
			$lines .= "<td>" .$companyfromdb ."</td> \n";
			$lines .= "</tr> \n";
		}
		if(!empty($lines)) {
			$notice = "<table> \n";
			$notice .= "<tr> \n";
			$notice .= '<th>Filmi pealkiri &nbsp;<a href="?sortby=1&sortorder=1">&uarr;</a> &nbsp;<a href="?sortby=1&sortorder=2">&darr;</a>' ."\n";
			$notice .= '<th>Tootmisfirma nimi &nbsp;<a href="?sortby=2&sortorder=1">&uarr;</a> &nbsp;<a href="?sortby=2&sortorder=2">&darr;</a> </th>' ."\n";
			$notice .= "</tr> \n";
			$notice .= $lines;
			$notice .= "</table> \n";
		}
		echo $stmt->error;
		$stmt->close();
		$conn->close();	
		return $notice;
	}
	
	function readquotes($sortby, $sortorder) {
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$SQLsentence = "SELECT quote_text, role, first_name, last_name, title FROM quote JOIN person_in_movie ON quote.person_in_movie_id = person_in_movie.person_in_movie_id JOIN person ON person_in_movie.person_id = person.person_id JOIN movie ON person_in_movie.movie_id = movie.movie_id";
		if($sortby == 0 and $sortorder == 0) {
			$stmt = $conn->prepare($SQLsentence);
		}
		
		if($sortby == 5){
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY quote_text DESC");
			} else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY quote_text");
			}
		}
		
		if($sortby == 4){
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY last_name DESC");
			} else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY last_name");
			}
		}
		
		if($sortby == 3){
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY first_name DESC");
			} else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY first_name");
			}
		}
		
		if($sortby == 2){
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY title DESC");
			} else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY title");
			}
		}
		
		if($sortby == 1){
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY role DESC");
			} else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY role");
			}
		}

		echo $conn->error;
		$stmt->bind_result($quotetextfromdb, $rolefromdb, $firstnamefromdb, $lastnamefromdb, $titlefromdb);
		$stmt->execute();
		$lines = null;
		while($stmt->fetch()) {
			$lines .= "<tr> \n";
			$lines .= "\t <td>" .$rolefromdb ."</td> \n";
			$lines .= "<td>" .$titlefromdb ."</td> \n";
			$lines .= "\t <td>" .$firstnamefromdb ." " .$lastnamefromdb ."</td> \n";
			$lines .= "<td>" .$quotetextfromdb ."</td> \n";
			$lines .= "</tr> \n";
		}
		if(!empty($lines)) {
			$notice = "<table> \n";
			$notice .= "<tr> \n";
			$notice .= '<th>Tegelane &nbsp;<a href="?sortby=1&sortorder=1">&uarr;</a> &nbsp;<a href="?sortby=1&sortorder=2">&darr;</a>' ."\n";
			$notice .= '<th>Filmi pealkiri &nbsp;<a href="?sortby=2&sortorder=1">&uarr;</a> &nbsp;<a href="?sortby=2&sortorder=2">&darr;</a> </th>' ."\n";
			$notice .= '<th>Näitleja nimi &nbsp; E <a href="?sortby=3&sortorder=1">&uarr;</a> &nbsp;<a href="?sortby=3&sortorder=2">&darr;</a> &nbsp; P <a href="?sortby=4&sortorder=1">&uarr;</a> &nbsp;<a href="?sortby=4&sortorder=2">&darr;</a> </th>' ."\n";
			$notice .= '<th>Tsitaat &nbsp;<a href="?sortby=5&sortorder=1">&uarr;</a> &nbsp;<a href="?sortby=5&sortorder=2">&darr;</a> </th>' ."\n";
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
		$conn->set_charset("utf8");
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