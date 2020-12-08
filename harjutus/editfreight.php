<?php
	require("usesession.php");
	require("../../../config.php");
	require("fnc_common.php");
	require("fnc_freight.php");
	
	$database = "if20_harli_kod_vp_1";
	$notice = null;
	$error = null;
	$exitmass = null;
	
	if(isset($_POST["editfrightsubmit"])) {
		if(empty($_POST["freightinput"])) {
			$error = "Vali menüüst vedu!";
			$exitmass = $_POST["exitmassinput"];
		}
		if(empty($_POST["exitmassinput"])) {
			$error .= " Väljumismass tuleb sisestada!";
		}
		if(empty($error)) {
			if(editFright(test_input($_POST["exitmassinput"]), $_POST["freightinput"]) == 1) {
				$notice = "Väljumismass on salvestatud.";
			} else {
				$notice = "Midagi läks valesti.";
			}
		}
	}
	$freightdropdown = sendEditableVehicleDropdown();
	
	require("header.php");
?>
  <img src="../img/vp_banner_improved.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p>See veebileht on loodud õppetöö kaigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
    <p><a href="../tund13/home.php?logout=1">Logi välja</a></p>
  <hr>
  
  <h2>Eelnevalt sisestamata jäänud väljumiskaalu tagantjärgi lisamine</h2>
  
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <label for="freightinput">Vali poolelijäänud vedu:</label>
	<select name="freightinput">
		<option value="" selected disabled>Väljumiskaaluta veod</option>
		<?php echo $freightdropdown; ?>
	</select>
	<br>
	<label for="exitmassinput">Sõiduki väljumismass:</label>
	<input type="number" name="exitmassinput" id="exitmassinput" value="<?php echo $exitmass; ?>"> kg
  <br>
  
  <input type="submit" name="editfrightsubmit" value="Salvesta väljumiskaal">
  </form>
  <br>
  <?php echo $notice; echo $error; ?>
  <hr>
  <p><a href="../tund13/home.php">Tagasi avalehele</a></p>
  <hr>
</body>
</html>