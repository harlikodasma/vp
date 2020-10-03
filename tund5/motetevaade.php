<?php
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
	
	require("header_logged.php");
?>

  <b>Kirja pandud mõtted (kõige alumised on kõige uuemad):</b>
  <?php echo $ideahtml; ?>
  <hr>
  <p><a href="home.php">Tagasi avalehele</a></p>
  <p><a href="mottesisestus.php">Sisesta oma mõte siin</a></p>
  <hr>
</body>
</html>