<?php
	require("usesession.php");
	
	//klassi testimine
	//require("classes/first_class.php");
	//$myclassobject = new First(10);
	//echo "Salajane arv on " .$myclassobject->mybusiness;
	//echo "Avalik arv on " .$myclassobject->everybodysbusiness;
	//$myclassobject->tellMe();
	//unset($myclassobject);
	
	//tegelen küpsistega - cookies
	//setcookie  see funktsioon peab olema enne <html> elementi, mis asub meil header failis
	//küpsise nimi, väärtus, aegumistähtaeg, failitee (domeeni piires), domeen, https kasutamine, küpsise muutmine ainult läbi minu lehe
	setcookie("vpvisitorname", $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"], time() + (86400 * 8), "/~harli/", "greeny.cs.tlu.ee", isset($_SERVER["HTTPS"]), true);
	$lastvisitor = null;
	if(isset($_COOKIE["vpvisitorname"])) {
		$lastvisitor = "<p>Viimati külastas lehte: " .$_COOKIE["vpvisitorname"] .".</p> \n";
	} else {
		$lastvisitor = "<p>Küpsiseid ei leitud, viimane külastaja pole teada.</p> \n";
	}
	//küpsise kustutamine
	//kustutamiseks tuleb sama küpsis kirjutada minevikus aegumistähtajaga, näiteks time() - 3600
	
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
	<li><a href="photoupload.php">Galeriipiltide üleslaadimine</a></li>
	<li><a href="photogallery_public.php">Avalike fotode galerii</a></li>
  </ul>
  <ul>
	<li><a href="addnews.php">Uudiste lisamine</a></li>
  </ul>
  <hr>
  <h3>Viimane külastaja sellest arvutist</h3>
  <?php
	if(count($_COOKIE > 0)) {
		echo "<p>Küpsised on lubatud! Leiti " .count($_COOKIE) ." küpsist.</p> \n";
	}
	echo $lastvisitor;
  ?>
</body>
</html>