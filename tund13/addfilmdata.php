<?php
	require("usesession.php");
	require("../../../config.php");
	require("header.php");
	require("fnc_common.php");
	require("fnc_addfilmdata.php");
	
	$database = "if20_harli_kod_1";
	
	$monthnameset = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
	
	$personnotice = null; $movienotice = null; $positionnotice = null; $genrenotice = null; $companynotice = null;
	$firstnameerror = null; $lastnameerror = null; $birthdateerror = null; $birthdayerror = null; $birthmontherror = null; $birthyearerror = null; $movienameerror = null; $movieyearerror = null; $moviedurationerror = null; $descriptionerror = null; $positionnameerror = null; $positiondescriptionerror = null; $genreerror = null; $genredescriptionerror = null; $companynameerror = null; $companyaddresserror = null;
	$birthday = null; $birthmonth = null; $birthyear = null; $movieyear = null;
	
	if(isset($_POST["personsubmit"])) {
		if(empty($_POST["firstnameinput"])) {
			$firstnameerror = "Eesnimi sisestamata!";
		} else {
			$firstname = test_input($_POST["firstnameinput"]);
		}
		if(empty($_POST["lastnameinput"])) {
			$lastnameerror = "Perekonnanimi sisestamata!";
		} else {
			$lastname = test_input($_POST["lastnameinput"]);
		}
		if(empty($_POST["birthdayinput"])) {
			$birthdayerror = "Sünnipäev valimata!";
		} else {
			$birthday = intval($_POST["birthdayinput"]);
		}
		if(empty($_POST["birthmonthinput"])) {
			$birthmontherror = "Sünnikuu valimata!";
		} else {
			$birthmonth = test_input($_POST["birthmonthinput"]);
		}
		if(empty($_POST["birthyearinput"])) {
			$birthyearerror = "Sünniaasta valimata!";
		} else {
			$birthyear = intval($_POST["birthyearinput"]);
		}
		if(!empty($_POST["birthdayinput"]) and !empty($_POST["birthmonthinput"]) and !empty($_POST["birthyearinput"])) {
			if(checkdate($birthmonth, $birthday, $birthyear)) {
				$tempdate = new DateTime($birthyear ."-" .$birthmonth ."-" .$birthday);
				$birthdate = $tempdate->format("Y-m-d");
			} else {
				$birthdateerror = "Kuupäev ei ole reaalne!";
			}
		}
		if(empty($firstnameerror) and empty($lastnameerror) and empty($birthdayerror) and empty($birthmontherror) and empty($birthyearerror) and empty($birthdateerror)) {
			$personnotice = addperson($firstname, $lastname, $birthdate);
		}
	}
	
	if(isset($_POST["filmsubmit"])) {
		if(empty($_POST["movienameinput"])) {
			$movienameerror = "Filmi nimi sisestamata!";
		} else {
			$moviename = test_input($_POST["movienameinput"]);
		}
		if(empty($_POST["movieyearinput"])) {
			$movieyearerror = "Filmi tootmisaasta valimata!";
		} else {
			$movieyear = intval($_POST["movieyearinput"]);
		}
		if(empty($_POST["moviedurationinput"])) {
			$moviedurationerror = "Filmi pikkus valimata!";
		} else {
			if($_POST["moviedurationinput"] <= 0 or $_POST["moviedurationinput"] > 500) {
				$moviedurationerror = "Ebareaalne filmi pikkus!";
			} else {
				$movieduration = intval($_POST["moviedurationinput"]);
			}
		}
		if(empty($_POST["descriptioninput"])) {
			$descriptionerror = "Filmi kirjeldus lisamata!";
		} else {
			$description = test_input($_POST["descriptioninput"]);
		}
		if(empty($movienameerror) and empty($movieyearerror) and empty($moviedurationerror) and empty($descriptionerror)) {
			$movienotice = addfilm($moviename, $movieyear, $movieduration, $description);
		}
	}
	
	if(isset($_POST["positionsubmit"])) {
		if(empty($_POST["positionnameinput"])) {
			$positionnameerror = "Positsioon sisestamata!";
		} else {
			$positionname = test_input($_POST["positionnameinput"]);
		}
		if(empty($_POST["positiondescriptioninput"])) {
			$positiondescriptionerror = "Positsiooni kirjeldus sisestamata!";
		} else {
			$positiondescription = test_input($_POST["positiondescriptioninput"]);
		}
		if(empty($positionnameerror) and empty($positiondescriptionerror)) {
			$positionnotice = addposition($positionname, $positiondescription);
		}
	}
	
	if(isset($_POST["genresubmit"])) {
		if(empty($_POST["genreinput"])) {
			$genreerror = "Žanr sisestamata!";
		} else {
			$genre = test_input($_POST["genreinput"]);
		}
		if(empty($_POST["genredescriptioninput"])) {
			$genredescriptionerror = "Žanri kirjeldus sisestamata!";
		} else {
			$genredescription = test_input($_POST["genredescriptioninput"]);
		}
		if(empty($genreerror) and empty($genredescriptionerror)) {
			$genrenotice = addgenre($genre, $genredescription);
		}
	}
	
	if(isset($_POST["companysubmit"])) {
		if(empty($_POST["companynameinput"])) {
			$companynameerror = "Tootja nimi sisestamata!";
		} else {
			$companyname = test_input($_POST["companynameinput"]);
		}
		if(empty($_POST["companyaddressinput"])) {
			$companyaddresserror = "Žanri kirjeldus sisestamata!";
		} else {
			$companyaddress = test_input($_POST["companyaddressinput"]);
		}
		if(empty($companynameerror) and empty($companyaddresserror)) {
			$companynotice = addcompany($companyname, $companyaddress);
		}
	}
	
?>

  <img src="../img/vp_banner_improved.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1>Noic veebileht</h1>
  <p>See veebileht on loodud õppetöö kaigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  
  <hr>
  
  <h2>Lisame filmiga seotud isiku</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label for="firstnameinput">Eesnimi: </label>
	  <input type="text" name="firstnameinput" placeholder="Jüri" <?php if(!empty($firstname)) {echo 'value="' .$firstname .'"';} ?>>
	  <span><?php echo $firstnameerror; ?></span>
	  <br>
	<label for="lastnameinput">Perenimi: </label>
	  <input type="text" name="lastnameinput" placeholder="Mägi" <?php if(!empty($lastname)){echo 'value="' .$lastname .'"';} ?>>
	  <span><?php echo $lastnameerror; ?></span>
	  <br>
	<label for="birthdayinput">Sünnipäev: </label>
	    <?php
			echo '<select name="birthdayinput" id="birthdayinput">' ."\n";
			echo '<option value="" selected disabled>päev</option>' ."\n";
			for ($i = 1; $i < 32; $i ++){
				echo '<option value="' .$i .'"';
				if ($i == $birthday){
					echo " selected ";
				}
				echo ">" .$i ."</option> \n";
			}
			echo "</select> \n";
		?>
	  <label for="birthmonthinput">Sünnikuu: </label>
	  <?php
	    echo '<select name="birthmonthinput" id="birthmonthinput">' ."\n";
		echo '<option value="" selected disabled>kuu</option>' ."\n";
		for ($i = 1; $i < 13; $i ++){
			echo '<option value="' .$i .'"';
			if ($i == $birthmonth){
				echo " selected ";
			}
			echo ">" .$monthnameset[$i - 1] ."</option> \n";
		}
		echo "</select> \n";
	  ?>
	  <label for="birthyearinput">Sünniaasta: </label>
	  <?php
	    echo '<select name="birthyearinput" id="birthyearinput">' ."\n";
		echo '<option value="" selected disabled>aasta</option>' ."\n";
		for ($i = date("Y") - 15; $i >= date("Y") - 110; $i --){
			echo '<option value="' .$i .'"';
			if ($i == $birthyear){
				echo " selected ";
			}
			echo ">" .$i ."</option> \n";
		}
		echo "</select> \n";
	  ?>
	  <span><?php echo $birthdateerror ." " .$birthdayerror ." " .$birthmontherror ." " .$birthyearerror; ?></span>
	  <br>
	<input type="submit" name="personsubmit" value="Salvesta isik">
  </form>
  <p><?php echo $personnotice; ?></p>
  
  <hr>
  
  <h2>Lisame filmi</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label for="movienameinput">Filmi nimi: </label>
	  <input type="text" name="movienameinput" placeholder="Siin me oleme" <?php if(!empty($moviename)){echo 'value="' .$moviename .'"';} ?>>
	  <span><?php echo $movienameerror; ?></span>
	  <br>
	<label for="movieyearinput">Filmi tootmisaasta: </label>
	  <?php
	    echo '<select name="movieyearinput" id="movieyearinput">' ."\n";
		echo '<option value="" selected disabled>aasta</option>' ."\n";
		for ($i = date("Y"); $i >= 1890; $i --){
			echo '<option value="' .$i .'"';
			if ($i == $movieyear){
				echo " selected ";
			}
			echo ">" .$i ."</option> \n";
		}
		echo "</select> \n";
	  ?>
	  <span><?php echo $movieyearerror; ?></span>
	  <br>
	<label for="moviedurationinput">Filmi kestus minutites: </label>
	  <input type="number" name="moviedurationinput" <?php if(!empty($movieduration)){echo 'value="' .$movieduration .'"';} ?>>
	  <span><?php echo $moviedurationerror; ?></span>
	  <br>
	<label for="descriptioninput">Kirjeldus: </label>
	  <br>
	  <textarea rows="7" cols="60" name="descriptioninput" id="descriptioninput" placeholder="Filmi lühikirjeldus ..."><?php if(!empty($description)) {echo $description;} ?></textarea>
	  <span><?php echo $descriptionerror; ?></span>
	  <br>
	<input type="submit" name="filmsubmit" value="Salvesta film">
  </form>
  <p><?php echo $movienotice; ?></p>
  
  <hr>
  
  <h2>Lisame positsiooni</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label for="positionnameinput">Positsioon: </label>
	  <input type="text" name="positionnameinput" placeholder="Režissöör" <?php if(!empty($positionname)){echo 'value="' .$positionname .'"';} ?>>
	  <span><?php echo $positionnameerror; ?></span>
	  <br>
	<label for="positiondescriptioninput">Positsiooni kirjeldus: </label>
	  <br>
	  <textarea rows="5" cols="40" name="positiondescriptioninput" id="positiondescriptioninput" placeholder="Positsiooni lühikirjeldus ..."><?php if(!empty($positiondescription)) {echo $positiondescription;} ?></textarea>
	  <span><?php echo $positiondescriptionerror; ?></span>
	  <br>
	<input type="submit" name="positionsubmit" value="Salvesta positsioon">
  </form>
  <p><?php echo $positionnotice; ?></p>
  
  <hr>
  
  <h2>Lisame žanri</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label for="genreinput">Žanr: </label>
	  <input type="text" name="genreinput" placeholder="Komöödia" <?php if(!empty($genre)){echo 'value="' .$genre .'"';} ?>>
	  <span><?php echo $genreerror; ?></span>
	  <br>
	<label for="genredescriptioninput">Žanri kirjeldus: </label>
	  <br>
	  <textarea rows="5" cols="40" name="genredescriptioninput" id="genredescriptioninput" placeholder="Žanri lühikirjeldus ..."><?php if(!empty($genredescription)) {echo $genredescription;} ?></textarea>
	  <span><?php echo $genredescriptionerror; ?></span>
	  <br>
	<input type="submit" name="genresubmit" value="Salvesta žanr">
  </form>
  <p><?php echo $genrenotice; ?></p>
  
  <hr>
  
  <h2>Lisame filmitootja</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label for="companynameinput">Filmitootja: </label>
	  <input type="text" name="companynameinput" placeholder="Allfilm" <?php if(!empty($companyname)){echo 'value="' .$companyname .'"';} ?>>
	  <span><?php echo $companynameerror; ?></span>
	  <br>
	<label for="companyaddressinput">Tootja aadress: </label>
	  <input type="text" name="companyaddressinput" placeholder="Narva mnt 25, Tallinn" <?php if(!empty($companyaddress)){echo 'value="' .$companyaddress .'"';} ?>>
	  <span><?php echo $companyaddresserror; ?></span>
	  <br>
	<input type="submit" name="companysubmit" value="Salvesta tootja">
  </form>
  <p><?php echo $companynotice; ?></p>
  
  <hr>
  
  <p><a href="home.php">Tagasi avalehele</a></p>
  <hr>
</body>
</html>