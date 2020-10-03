<?php
	require("../../../config.php");
	require("fnc_common.php");
	require("fnc_user.php");

	$firstnameerror = ""; $lastnameerror = ""; $gendererror = ""; $birthdayerror = ""; $birthmontherror = ""; $birthyearerror = ""; $birthdateerror = ""; $emailerror = ""; $passworderror = ""; $passwordsecondaryerror = "";
	
	$monthnameset = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
	
	$notice = "";
	$firstname = ""; $lastname = ""; $gender = ""; $birthday = ""; $birthmonth = ""; $birthyear = ""; $birthdate = ""; $email = "";
	
	if(isset($_POST["accountdatasubmit"])) {
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
		if(empty($_POST["genderinput"])) {
			$gendererror = "Sugu valimata!";
		} else {
			$gender = intval($_POST["genderinput"]);
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
		
		//kontrollime kuupäeva kehtivust
		
		if(!empty($_POST["birthdayinput"]) and !empty($_POST["birthmonthinput"]) and !empty($_POST["birthyearinput"])) {
			if(checkdate($birthmonth, $birthday, $birthyear)) {
				$tempdate = new DateTime($birthyear ."-" .$birthmonth ."-" .$birthday);
				$birthdate = $tempdate->format("Y-m-d");
			} else {
				$birthdateerror = "Kuupäev ei ole reaalne!";
			}
		}
		
		if(empty($_POST["emailinput"])) {
			$emailerror = "E-post sisestamata!";
		} else {
			if((filter_var(test_input($_POST["emailinput"]), FILTER_VALIDATE_EMAIL)) == null) {
				$emailerror = "Ebakorrektne e-posti aadress!";
			} else {
				$email = test_input($_POST["emailinput"]);
			}
		}
		if(empty($_POST["passwordinput"])) {
			$passworderror = "Salasõna sisestamata!";
			$passwordsecondaryerror = "Salasõna sisestamata!";
		}
		if(empty($_POST["passwordsecondaryinput"]) and empty($passworderror)) {
			$passwordsecondaryerror = "Salasõna tuleb ka teist korda sisestada!";
		}
		if(($_POST["passwordinput"] != $_POST["passwordsecondaryinput"])) {
			$passworderror = "Salasõnad ei ühti!";
			$passwordsecondaryerror = "Salasõnad ei ühti!";
		}
		if((strlen($_POST["passwordinput"]) < 8) or (strlen($_POST["passwordsecondaryinput"]) < 8)) {
			$passworderror = "Salasõna peab olema vähemalt 8 tähemärki pikk!";
			$passwordsecondaryerror = "Salasõna peab olema vähemalt 8 tähemärki pikk!";
		}
		if(empty($firstnameerror) and empty($lastnameerror) and empty($gendererror) and empty($birthdayerror) and empty($birthmontherror) and empty($birthyearerror) and empty($birthdateerror) and empty($emailerror) and empty($passworderror) and empty($passwordsecondaryerror)) {
			$notice = signup($firstname, $lastname, $email, $gender, $birthdate, $_POST["passwordinput"]);
			if($notice == "ok") {
				$notice = "Kõik korras, kasutaja loodud!";
				$firstname = ""; $lastname = ""; $gender = ""; $birthday = null; $birthmonth = null; $birthyear = null; $birthdate = null; $email = null;
			} else {
				$notice = "Tõrge: " .$notice;
			}
		}
	}
	require("header_anon.php");
?>

  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label for="firstnameinput">Eesnimi</label>
	  <input type="text" name="firstnameinput" id="firstnameinput" placeholder="Kalle" value="<?php echo $firstname; ?>">
	  <span><?php echo $firstnameerror; ?></span>
	  <br>
	  <label for="lastnameinput">Perekonnanimi</label>
	  <input type="text" name="lastnameinput" id="lastnameinput" placeholder="Karu" value="<?php echo $lastname; ?>">
	  <span><?php echo $lastnameerror; ?></span>
	  <br>
	  <label for="genderinput">Sugu</label>
	  <input type="radio" name="genderinput" id="gendermale" value="1" <?php if($gender == "1"){echo "checked";} ?>><label for="gendermale">Mees</label><input type="radio" name="genderinput" id="genderfemale" value="2" <?php if($gender == "2"){echo "checked";} ?>><label for="genderfemale">Naine</label>
	  <span><?php echo $gendererror; ?></span>
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
	  <label for="emailinput">E-posti aadress</label>
	  <input type="email" name="emailinput" id="emailinput" placeholder="kalle.karu@gmail.com" value="<?php echo $email; ?>">
	  <span><?php echo $emailerror; ?></span>
	  <br>
	  <label for="passwordinput">Salasõna</label>
	  <input type="password" name="passwordinput" id="passwordinput">
	  <span><?php echo $passworderror; ?></span>
	  <br>
	  <label for="passwordsecondaryinput">Salasõna teist korda</label>
	  <input type="password" name="passwordsecondaryinput" id="passwordsecondaryinput">
	  <span><?php echo $passwordsecondaryerror; ?></span>
	  <br>
	  <input type="submit" name="accountdatasubmit" value="Loo kasutaja">
    </form>
	<span><?php echo $notice; ?></span>
	<hr>
  <p><a href="page.php">Tagasi avalehele</a></p>
</body>
</html>