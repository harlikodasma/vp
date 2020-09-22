<?php
	require("../../../config.php");
	require("fnc_films.php");
	//kui on idee sisestatud ja nuppu vajutatud, salvestame selle andmebaasi
	$inputerror = "";
	//$database = "if20_harli_kod_vp_1";
	if(isset($_POST["filmsubmit"])) {
		if(empty($_POST["titleinput"]) or empty($_POST["genreinput"]) or empty($_POST["studioinput"]) or empty($_POST["directorinput"])) {
			$inputerror .= "Osa infot on sisestamata!";
		}
		if($_POST["yearinput"] > date("Y") or $_POST["yearinput"] < 1895) {
			$inputerror .= "Ebareaalne valmimisaasta!";
		}
		if(empty($inputerror)) {
			savefilm($_POST["titleinput"], $_POST["yearinput"], $_POST["durationinput"], $_POST["genreinput"], $_POST["studioinput"], $_POST["directorinput"]);
		}
	}
	require("header.php");
?>

  <form method="POST">
	  <label for="titleinput">Filmi pealkiri</label>
	  <input type="text" name="titleinput" id="titleinput" placeholder="Pealkiri">
	  <br>
	  <label for="yearinput">Filmi valmimisaasta</label>
	  <input type="number" name="yearinput" id="yearinput" value="<?php echo date("Y"); ?>">
	  <br>
	  <label for="durationinput">Filmi kestus minutites</label>
	  <input type="number" name="durationinput" id="durationinput" value="80">
	  <br>
	  <label for="titleinput">Filmi žanr</label>
	  <input type="text" name="genreinput" id="genreinput" placeholder="Žanr">
	  <br>
	  <label for="titleinput">Filmi tootja</label>
	  <input type="text" name="studioinput" id="studioinput" placeholder="Stuudio">
	  <br>
	  <label for="titleinput">Filmi lavastaja</label>
	  <input type="text" name="directorinput" id="directorinput" placeholder="Lavastaja">
	  <br>
	  <input type="submit" name="filmsubmit" value="Salvesta filmi info">
    </form>
  <p><?php echo $inputerror; ?></p>
  <hr>
  <p><a href="home.php">Tagasi avalehele</a></p>
  <hr>
</body>
</html>