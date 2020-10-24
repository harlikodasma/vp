<?php
	require("usesession.php");
	require("../../../config.php");
	require("header.php");
	require("fnc_common.php");
	
	$database = "if20_harli_kod_1";
	
	$notice = null;
	$studionotice = null;
	$personnotice = null;
	$quotenotice = null;
	$quoteerror = null;
	$filmtitledropdown = null;
	$filmstudiodropdown = null;
	$genredropdown = null;
	$personnamedropdown = null;
	$positiondropdown = null;
	$characterdropdown = null;
	
	//filmi pealkirjade listi tegemine
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$conn->set_charset("utf8");
	$stmt = $conn->prepare("SELECT movie_id, title FROM movie");
	echo $conn->error;
	$stmt->bind_result($movieidfromdb, $movietitlefromdb);
	if($stmt->execute()) {
		while($stmt->fetch()) {
		$filmtitledropdown .= "\n \t \t" .'<option value="' .$movieidfromdb .'">' .$movietitlefromdb .'</option>';
		}
	} else {
		$notice = $stmt->error;
	}
	$stmt->close();
	
	//zanrite listi tegemine
	$stmt = $conn->prepare("SELECT genre_id, genre_name FROM genre");
	echo $conn->error;
	$stmt->bind_result($genreidfromdb, $genrefromdb);
	if($stmt->execute()) {
		while($stmt->fetch()) {
		$genredropdown .= "\n \t \t" .'<option value="' .$genreidfromdb .'">' .$genrefromdb .'</option>';
		}
	} else {
		$notice = $stmt->error;
	}
	$stmt->close();
	
	//stuudiote listi tegemine
	$stmt = $conn->prepare("SELECT production_company_id, company_name FROM production_company");
	echo $conn->error;
	$stmt->bind_result($companyidfromdb, $companyfromdb);
	if($stmt->execute()) {
		while($stmt->fetch()) {
		$filmstudiodropdown .= "\n \t \t" .'<option value="' .$companyidfromdb .'">' .$companyfromdb .'</option>';
		}
	} else {
		$notice = $stmt->error;
	}
	$stmt->close();
	
	//isikute listi tegemine
	$stmt = $conn->prepare("SELECT person_id, first_name, last_name FROM person");
	echo $conn->error;
	$stmt->bind_result($personidfromdb, $personfirstnamefromdb, $personlastnamefromdb);
	if($stmt->execute()) {
		while($stmt->fetch()) {
		$personnamedropdown .= "\n \t \t" .'<option value="' .$personidfromdb .'">' .$personfirstnamefromdb ." " .$personlastnamefromdb .'</option>';
		}
	} else {
		$notice = $stmt->error;
	}
	$stmt->close();
	
	//positsioonide listi tegemine
	$stmt = $conn->prepare("SELECT position_id, position_name FROM position");
	echo $conn->error;
	$stmt->bind_result($positionidfromdb, $positionnamefromdb);
	if($stmt->execute()) {
		while($stmt->fetch()) {
		$positiondropdown .= "\n \t \t" .'<option value="' .$positionidfromdb .'">' .$positionnamefromdb .'</option>';
		}
	} else {
		$notice = $stmt->error;
	}
	$stmt->close();
	
	//karakterite listi tegemine
	$stmt = $conn->prepare("SELECT first_name, last_name, role, title, person_in_movie_id FROM person JOIN person_in_movie ON person.person_id = person_in_movie.person_id JOIN movie ON movie.movie_id = person_in_movie.movie_id WHERE role IS NOT NULL"); //JOIN quote ON quote.person_in_movie_id = person_in_movie.person_in_movie_id
	echo $conn->error;
	$stmt->bind_result($firstnamefromdb, $lastnamefromdb, $rolefromdb, $titlefromdb, $idfromdb);
	if($stmt->execute()) {
		while($stmt->fetch()) {
		$characterdropdown .= "\n \t \t" .'<option value="' .$idfromdb .'">' .$rolefromdb .' ' .' filmis ' .$titlefromdb .' (näitleja ' .$firstnamefromdb .' ' .$lastnamefromdb .')' .'</option>';
		}
	} else {
		$notice = $stmt->error;
	}
	$stmt->close();
	
	//andmete saatmine db-sse - film x žanr
	if(isset($_POST["filmrelationsubmit"])) {
		if(isset($_POST["filminput"]) and isset($_POST["genreinput"])) {
			$stmt = $conn->prepare("SELECT movie_id, genre_id FROM movie_genre WHERE (movie_id = ? AND genre_id = ?)");
			echo $conn->error;
			$stmt->bind_param("ii", $_POST["filminput"], $_POST["genreinput"]);
			if($stmt->execute()) {
				if($stmt->fetch()) {
					$notice = "Seos on juba olemas!";
				} else {
					$stmt->close();
					$stmt = $conn->prepare("INSERT INTO movie_genre (movie_id, genre_id) VALUES (?, ?)");
					echo $conn->error;
					$stmt->bind_param("ii", $_POST["filminput"], $_POST["genreinput"]);
					if($stmt->execute()) {
						$notice = "Seos salvestatud!";
					} else {
						$notice = $stmt->error;
					}
					$stmt->close();
				}
			} else {
				$notice = $stmt->error;
			}
		} else {
			$notice = "Üks valikutest on tegemata!";
		}
	}
	
	//andmete saatmine db-sse - film x stuudio/tootja
	if(isset($_POST["filmstudiorelationsubmit"])) {
		if(isset($_POST["filminput"]) and isset($_POST["studioinput"])) {
			$stmt = $conn->prepare("SELECT movie_movie_id, production_company_id FROM movie_by_production_company WHERE (movie_movie_id = ? AND production_company_id = ?)");
			echo $conn->error;
			$stmt->bind_param("ii", $_POST["filminput"], $_POST["studioinput"]);
			if($stmt->execute()) {
				if($stmt->fetch()) {
					$studionotice = "Seos on juba olemas!";
				} else {
					$stmt->close();
					$stmt = $conn->prepare("INSERT INTO movie_by_production_company (movie_movie_id, production_company_id) VALUES (?, ?)");
					echo $conn->error;
					$stmt->bind_param("ii", $_POST["filminput"], $_POST["studioinput"]);
					if($stmt->execute()) {
						$studionotice = "Seos salvestatud!";
					} else {
						$studionotice = $stmt->error;
					}
					$stmt->close();
				}
			} else {
				$studionotice = $stmt->error;
			}
		} else {
			$studionotice = "Üks valikutest on tegemata!";
		}
	}
	
	//andmete saatmine db-sse - film x isik
	if(isset($_POST["personrelationsubmit"])) {
		if(isset($_POST["roleinput"])) {
			$role = test_input($_POST["roleinput"]);
		}
		if(isset($_POST["filminput"]) and isset($_POST["personinput"]) and isset($_POST["positioninput"]) and isset($_POST["roleinput"])) {
			$stmt = $conn->prepare("SELECT person_id, movie_id, position_id, role FROM person_in_movie WHERE (person_id = ? AND movie_id = ? AND position_id = ? AND role = ?)");
			echo $conn->error;
			$stmt->bind_param("iiis", $_POST["personinput"], $_POST["filminput"], $_POST["positioninput"], $_POST["roleinput"]);
			if($stmt->execute()) {
				if($stmt->fetch()) {
					$personnotice = "Seos on juba olemas!";
				} else {
					$stmt->close();
					$stmt = $conn->prepare("INSERT INTO person_in_movie (person_id, movie_id, position_id, role) VALUES (?, ?, ?, ?)");
					echo $conn->error;
					$roleinput = test_input($_POST["roleinput"]);
					if(empty($roleinput)) {
						$roleinput = null;
					}
					$stmt->bind_param("iiis", $_POST["personinput"], $_POST["filminput"], $_POST["positioninput"], $roleinput);
					if($stmt->execute()) {
						$personnotice = "Seos salvestatud!";
					} else {
						$notice = $stmt->error;
					}
					$stmt->close();
				}
			} else {
				$personnotice = $stmt->error;
			}
		} else {
			$personnotice = "Üks valikutest on tegemata!";
		}
	}
	
	//andmete saatmine db-sse - karakter x tsitaat
	if(isset($_POST["characterquotesubmit"])) {
		if(isset($_POST["quoteinput"])) {
			$quote = test_input($_POST["quoteinput"]);
		}
		if(isset($_POST["characterinput"]) and !empty($_POST["quoteinput"])) {
			$stmt = $conn->prepare("SELECT person_in_movie_id FROM quote WHERE (quote_text = ? AND person_in_movie_id = ?)");
			echo $conn->error;
			$stmt->bind_param("si", $_POST["quoteinput"], $_POST["characterinput"]);
			if($stmt->execute()) {
				if($stmt->fetch()) {
					$quotenotice = "Seos on juba olemas!";
				} else {
					$stmt->close();
					$stmt = $conn->prepare("INSERT INTO quote (quote_text, person_in_movie_id) VALUES (?, ?)");
					echo $conn->error;
					$quoteinput = test_input($_POST["quoteinput"]);
					$stmt->bind_param("si", $quoteinput, $_POST["characterinput"]);
					if($stmt->execute()) {
						$quotenotice = "Seos salvestatud!";
					} else {
						$notice = $stmt->error;
					}
					$stmt->close();
				}
			} else {
				$quotenotice = $stmt->error;
			}
		} else {
			$quotenotice = "Kõik andmed pole sisestatud!";
		}
	}
	
	$conn->close();
?>

  <img src="../img/vp_banner_improved.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1>Noic veebileht</h1>
  <p>See veebileht on loodud õppetöö kaigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  
  <hr>
  
  <h2>Määrame filmiga seotud isiku</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label for="filminput">Film:</label>
	<select name="filminput">
		<option value="" selected disabled>Vali film</option>
		<?php echo $filmtitledropdown; ?>
	</select>
	<br>
	<label for="personinput">Isik:</label>
	<select name="personinput">
		<option value="" selected disabled>Vali isik</option>
		<?php echo $personnamedropdown; ?>
	</select>
	<br>
	<label for="positioninput">Positsioon:</label>
	<select name="positioninput">
		<option value="" selected disabled>Vali positsioon</option>
		<?php echo $positiondropdown; ?>
	</select>
	<br>
	<label for="roleinput">Roll:</label>
	<input type="text" name="roleinput" placeholder="Sisesta roll, keda mängis" <?php if(!empty($role)){echo 'value="' .$role .'"';} ?>>
	<br>
	<input type="submit" name="personrelationsubmit" value="Salvesta seos isikuga">
	<?php echo $personnotice; ?>
  </form>
  
  <hr>
  
  <h2>Määrame filmi stuudio/tootja</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label for="filminput">Film:</label>
	<select name="filminput">
		<option value="" selected disabled>Vali film</option>
		<?php echo $filmtitledropdown; ?>
	</select>
	<br>
	<label for="studioinput">Stuudio/tootja:</label>
	<select name="studioinput">
		<option value="" selected disabled>Vali stuudio/tootja</option>
		<?php echo $filmstudiodropdown; ?>
	</select>
	<br>
	<input type="submit" name="filmstudiorelationsubmit" value="Salvesta seos stuudioga">
	<?php echo $studionotice; ?>
  </form>
  
  <hr>
  
  <h2>Määrame filmile žanri</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="filminput">Film:</label>
	<select name="filminput" id="filminput">
		<option value="" selected disabled>Vali film</option>
		<?php echo $filmtitledropdown; ?>
	</select>
	<br>
	<label for="genreinput">Žanr:</label>
	<select name="genreinput" id="genreinput">
		<option value="" selected disabled>Vali žanr</option>
		<?php echo $genredropdown; ?>
	</select>
	<br>
	<input type="submit" name="filmrelationsubmit" value="Salvesta seos žanriga">
	<?php echo $notice; ?>
  </form>
  
  <hr>
  
  <h2>Määrame filmikarakterile tsitaadi</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="characterinput">Tegelane filmis:</label>
	<select name="characterinput" id="characterinput">
		<option value="" selected disabled>Vali karakter</option>
		<?php echo $characterdropdown; ?>
	</select>
	<br>
	<label for="quoteinput">Tsitaat:</label>
	  <input type="text" name="quoteinput" placeholder="Kui Arno isaga koolimajja jõudis..." <?php if(!empty($quote)){echo 'value="' .$quote .'"';} ?>>
	  <span><?php echo $quoteerror; ?></span>
	  <br>
	<input type="submit" name="characterquotesubmit" value="Seo karakter tsitaadiga">
	<?php echo $quotenotice; ?>
  </form>
  
  <hr>
  
  <p><a href="home.php">Tagasi avalehele</a></p>
  <hr>
</body>
</html>