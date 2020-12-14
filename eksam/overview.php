<?php
	require("../../../config.php");
	require("fnc_people.php");
	
	$database = "if20_harli_kod_vp_1";
	
	require("header.php");
?>
  <img src="../img/vp_banner_improved.png" alt="Bänner">
  <h1>Eksam 14.12.2020 - Harli Kodasma</h1>
  <p>See veebileht on loodud eksamitööna.</p>
  <hr>
  
  <h2>Ülevaade hoones viibijatest</h2>
  
  Hetkel viibib hoones kokku <b><?php echo totalPeople(); ?></b> inimest.
  <br>
  Neist <b><?php currentInCategory(1, 1); ?></b> meessoost üliõpilasi, <b><?php currentInCategory(2, 1); ?></b> naissoost üliõpilasi, <b><?php currentInCategory(1, 2); ?></b> meessoost õppejõude ja <b><?php currentInCategory(2, 2); ?></b> naissoost õppejõude.
  <br>
  <br>
  Kõige rohkem inimesi on korraga hoones olnud <b><?php maxPeople(); ?></b>.
  <br>
  Igas kategoorias on kõige rohkem korraga inimesi hoones olnud järgnevalt: meessoost üliõpilasi <b><?php maxInCategory(1, 1); ?></b>, naissoost üliõpilasi <b><?php maxInCategory(2, 1); ?></b>, meessoost õppejõude <b><?php maxInCategory(1, 2); ?></b> ja naissoost õppejõude <b><?php maxInCategory(2, 2); ?></b>.
  <hr>
  <p><a href="addpeople.php">Andmete muutmine ja lisamine</a></p>
  <hr>
</body>
</html>