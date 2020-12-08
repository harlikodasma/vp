<?php
	require("usesession.php");
	require("../../../config.php");
	require("fnc_common.php");
	
	$inputerror = "";
	$regnrinput = null;
	$entermassinput = null;
	$exitmassinput = null;
	$database = "if20_harli_kod_vp_1";
	
	if(isset($_POST["freightsubmit"])) {
		if(empty($_POST["regnrinput"])) {
			$inputerror = "Sõiduki numbrimärk on lisamata!";
			$entermassinput = $_POST["entermassinput"];
			$exitmassinput = $_POST["exitmassinput"];
		}
		if(empty($_POST["entermassinput"])) {
			$inputerror .= " Sõiduki sisenemismass on tühi!";
			$regnrinput = $_POST["regnrinput"];
			$exitmassinput = $_POST["exitmassinput"];
		}
		
		if(empty($inputerror)) {
			$regnrinput = test_input($_POST["regnrinput"]);
			$entermassinput = test_input($_POST["entermassinput"]);
			if(empty($_POST["exitmassinput"])) {
				$exitmassinput = null;
			} else {
				$exitmassinput = test_input($_POST["exitmassinput"]);
			}
			
			$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
			$stmt = $conn->prepare("INSERT INTO harjutus (reg_nr, enter_mass, exit_mass) VALUES (?, ?, ?)");
			echo $conn->error;
			$stmt->bind_param("sii", $regnrinput, $entermassinput, $exitmassinput);
			if($stmt->execute()) {
				$inputerror = "Andmed edukalt salvestatud!";
				$regnrinput = null;
				$entermassinput = null;
				$exitmassinput = null;
			}
			echo $stmt->error;
			$stmt->close();
			$conn->close();
		}
	}
	
	require("header.php");
?>
  <img src="../img/vp_banner_improved.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p>See veebileht on loodud õppetöö kaigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
    <p><a href="../tund13/home.php?logout=1">Logi välja</a></p>
  <hr>
  
  <h2>Uue viljaveo sisestamine</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label for="regnrinput">Sõiduki numbrimärk:</label>
	  <br>
	  <input type="text" name="regnrinput" id="regnrinput" placeholder="874MEX" value="<?php echo $regnrinput;?>">
	  <br>
	  <label for="entermassinput">Sõiduki sisenemismass:</label>
	  <br>
	  <input type="number" name="entermassinput" id="entermassinput" value="<?php echo $entermassinput;?>"> kg
	  <br>
	  <label for="exitmassinput">Sõiduki väljumismass (võid jätta esialgu tühjaks ja lisada hiljem):</label>
	  <br>
	  <input type="number" name="exitmassinput" id="exitmassinput" value="<?php echo $exitmassinput;?>"> kg
	  <br>
	  <input type="submit" name="freightsubmit" value="Salvesta vedu">
  </form>
  <p><?php echo $inputerror; ?></p>
  <hr>
  <p><a href="../tund13/home.php">Tagasi avalehele</a></p>
  <hr>
</body>
</html>