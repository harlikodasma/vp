<?php
	require("usesession.php");
	require("../../../config.php");
	
	//loen lehele kõik olemasolevad mõtted
	$database = "if20_harli_kod_vp_1";
	$conn = new mysqli($serverhost, $serverusername, $serverpassword, $database);
	$stmt = $conn->prepare("SELECT idea FROM myideas");
	echo $conn->error;
	//seome tulemuse muutujaga
	$stmt->bind_result($ideafromdb);
	$stmt->execute();
	echo $stmt->error;
	$ideahtml = "";
	while($stmt->fetch()) {
		$ideahtml .= "<p>" .$ideafromdb ."</p>";
	}
	$stmt->close();
	$conn->close();
	
	require("header.php");
?>
  <img src="../img/vp_banner_improved.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p>See veebileht on loodud õppetöö kaigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
    <p><a href="?logout=1">Logi välja</a></p>
  <hr>
  
  <b>Kirja pandud mõtted (kõige alumised on kõige uuemad):</b>
  <?php echo $ideahtml; ?>
  <hr>
  <p><a href="home.php">Tagasi avalehele</a></p>
  <p><a href="mottesisestus.php">Sisesta oma mõte siin</a></p>
  <hr>
</body>
</html>