<?php
	require("../usesession.php");
	require("../../../../config.php");
	require("../fnc_films.php");
	
	$sortby = 0;
	$sortorder = 0;
	
	require("../header.php");
?>
  <img src="../../img/vp_banner_improved.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p>See veebileht on loodud õppetöö kaigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
    <p><a href="../home.php?logout=1">Logi välja</a></p>
  <hr>
  
  <p><a href="../home.php">Tagasi avalehele</a></p>
  <hr>
  <p><a href="../showfilmdata.php">Tagasi filmiandmete kuvamise valikusse</a></p>
  <hr>
  <h2>Positsioonid:</h2>
  <?php
	if(isset($_GET["sortby"]) and isset($_GET["sortorder"])) {
		if($_GET["sortby"] >= 1 and $_GET["sortby"] <= 2) {
			$sortby = $_GET["sortby"];
		}
		if($_GET["sortorder"] == 1 or $_GET["sortorder"] == 2) {
			$sortorder = $_GET["sortorder"];
		}
	}
	echo readpositions($sortby, $sortorder);
  ?>
  <hr>
</body>
</html>