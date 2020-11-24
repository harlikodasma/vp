<?php
	require("usesession.php");
	require("header.php");
?>
  
  <img src="../img/vp_banner_improved.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p>See veebileht on loodud õppetöö kaigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
    <p><a href="?logout=1">Logi välja</a></p>
  <hr>
  
  <p><a href="home.php">Tagasi avalehele</a></p>
  <hr>
  <h3>Eraldiseisvad andmed</h3>
  <ul>
    <li><a href="lists/listpersons.php">Isikud</a></li>
	<li><a href="lists/listpositions.php">Positsioonid</a></li>
	<li><a href="lists/listmovies.php">Filmid</a></li>
	<li><a href="lists/listgenres.php">Žanrid</a></li>
	<li><a href="lists/listproductioncompanies.php">Stuudiod/tootmisfirmad</a></li>
  </ul>
  <hr>
  <h3>Omavahel seostatud andmed</h3>
  <ul>
    <li><a href="lists/listfilmpersons.php">Filmitegelased</a></li>
	<li><a href="lists/listmoviesandgenres.php">Filmide žanrid</a></li>
	<li><a href="lists/listmoviesandcompanies.php">Filmide tootjad</a></li>
	<li><a href="lists/listquotes.php">Tsitaadid</a></li>
  </ul>
  <hr>
</body>
</html>