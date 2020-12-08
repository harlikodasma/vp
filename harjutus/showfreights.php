<?php
	require("usesession.php");
	require("../../../config.php");
	require("fnc_freight.php");
	
	$database = "if20_harli_kod_vp_1";

	if(isset($_POST["sortsubmit"])) {
		if($_POST["sortinput"] == "default") {
			$freighthtml = sendAllFreights();
		} else {
			$freighthtml = sendSpecificFreights($_POST["sortinput"]);
		}
	} else {
		$freighthtml = sendAllFreights();
	}
	$vehicledropdown = sendVehicleDropdown();
	
	require("header.php");
?>
  <img src="../img/vp_banner_improved.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p>See veebileht on loodud õppetöö kaigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
    <p><a href="../tund13/home.php?logout=1">Logi välja</a></p>
  <hr>
  
  <h2>Viljavedude kokkuvõtete vaatamine</h2>
  
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  Esialgu näed kõikide sõidukite kõiki vedusid, mille väljumiskaalu ei ole tühjaks jäetud. Kui soovid näha mõne kindla sõiduki vedusid, siis tee valik allpool (ka seal ei näidata tühja väljumiskaaluga vedusid). Kui soovid minna tagasi kõikide sõidukite vaatesse, siis vali uuesti "Näita kõiki sõidukeid" ja vajuta nupule.
  <br><br>
  <label for="sortinput">Sõiduki valik:</label>
	<select name="sortinput">
		<option value="default" selected>Näita kõiki sõidukeid</option>
		<?php echo $vehicledropdown; ?>
	</select>
	<br>
	<input type="submit" name="sortsubmit" value="Näita valitud vedusid">
  </form>
  <br>
  <?php echo $freighthtml; ?>
  <hr>
  <p><a href="../tund13/home.php">Tagasi avalehele</a></p>
  <hr>
</body>
</html>