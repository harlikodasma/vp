<?php
	require("usesession.php");
	require("header.php");
?>
  <audio src="../sound/arabic.mp3" autoplay="autoplay" loop="loop"></audio>
  
  <img src="../img/vp_banner_improved.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p>See veebileht on loodud õppetöö kaigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
    <p><a href="?logout=1">Logi välja</a></p>
  <hr>
  
  <ul>
    <li><a href="mottesisestus.php">Sisesta oma mõte siin</a></li>
    <li><a href="motetevaade.php">Vaata sisestatud mõtteid siin</a></li>
  </ul>
  <ul>
	<li><a href="listfilms.php">Loe filmiinfot</a></li>
	<li><a href="addfilm.php">Filmi lisamine</a></li>
  </ul>
  <ul>
	<li><a href="addfilmdata.php">Filmiga seotud andmete lisamine</a></li>
	<li><a href="addfilmrelations.php">Filmiandmete vahel seoste loomine</a></li>
	<li><a href="showfilmdata.php">Filmiandmete kuvamise valikud</a></li>
  </ul>
  <ul>
	<li><a href="userprofile.php">Minu kasutajaprofiil</a></li>
  </ul>
  <hr>
</body>
</html>