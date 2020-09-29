<?php
	require("../../../config.php");
	require("fnc_common.php");
	
	//kui on idee sisestatud ja nuppu vajutatud, salvestame selle andmebaasi
	$database = "if20_harli_kod_vp_1";
	if(isset($_POST["ideasubmit"]) and !empty($_POST["ideainput"])) {
		$conn = new mysqli($serverhost, $serverusername, $serverpassword, $database);
		//valmistan ette sql käsu
		$stmt = $conn->prepare("INSERT INTO myideas (idea) VALUES (?)");
		echo $conn->error; //ütleb kui on db error
		//seome käsuga päris andmed
		//i - integer, d - decimal, s - string
		$stmt->bind_param("s", test_input($_POST["ideainput"]));
		$stmt->execute();
		echo $stmt->error;
		$stmt->close();
		$conn->close();
	}
	require("header.php");
?>

  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label>Sisesta oma pähe tulnud mõte!</label>
	  <input type="text" name="ideainput" placeholder="Kirjuta siia mõte!">
	  <input type="submit" name="ideasubmit" value="Saada mõte ära!">
    </form>
  <hr>
  <p><a href="home.php">Tagasi avalehele</a></p>
  <p><a href="motetevaade.php">Vaata sisestatud mõtteid siin</a></p>
  <hr>
</body>
</html>