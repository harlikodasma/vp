<?php
  require("usesession.php");
  require("../../../config.php");
  
  $newshtml = null;
  
  $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], "if20_harli_kod_vp_1");
	$stmt = $conn->prepare("SELECT vpnews_id, title, content, firstname, lastname, added, expire, filename FROM vpnews JOIN vpusers ON vpusers.vpusers_id = vpnews.userid LEFT JOIN vpnewsphotos ON vpnews.vpnewsphotos_id = vpnewsphotos.vpnewsphotos_id WHERE vpnews.deleted IS NULL AND expire IS NULL OR expire > CURDATE() ORDER BY vpnews_id DESC LIMIT 5");
	echo $conn->error;
	$stmt->bind_result($newsidfromdb, $newstitlefromdb, $newscontentfromdb, $authorfirstnamefromdb, $authorlastnamefromdb, $uploaddate, $expiredate, $filename);
	$stmt->execute();
	while($stmt->fetch()) {
		if($expiredate == null and $filename != null) {
			$newshtml .= "\t" ."<h3>" .$newstitlefromdb ."</h3> \n \t" ."<p>Autor: <strong>" .$authorfirstnamefromdb ." " .$authorlastnamefromdb ."</strong></p>" ."\n \t" ."<p>Lisatud: <strong>" .$uploaddate ."</strong></p>" ."\n \t" .'<img src="../photoupload_news/' .$filename .'">' ."\n \t" .htmlspecialchars_decode($newscontentfromdb) ."\n \t" .'<button type="button"><a class="nupp" href="alterNews.php?id=' .$newsidfromdb .'">Uudise "' .$newstitlefromdb .'" muutmine/kustutamine</a></button>' ."\n";
		} elseif($expiredate == null and $filename == null) {
			$newshtml .= "\t" ."<h3>" .$newstitlefromdb ."</h3> \n \t" ."<p>Autor: <strong>" .$authorfirstnamefromdb ." " .$authorlastnamefromdb ."</strong></p>" ."\n \t" ."Lisatud: <strong>" .$uploaddate ."</strong>" ."\n \t" .htmlspecialchars_decode($newscontentfromdb) ."\n \t" .'<button type="button"><a class="nupp" href="alterNews.php?id=' .$newsidfromdb .'">Uudise "' .$newstitlefromdb .'" muutmine/kustutamine</a></button>' ."\n";
		} elseif($expiredate != null and $filename == null) {
			$newshtml .= "\t" ."<h3>" .$newstitlefromdb ."</h3> \n \t" ."<p>Autor: <strong>" .$authorfirstnamefromdb ." " .$authorlastnamefromdb ."</strong></p>" ."\n \t" ."<p>Lisatud: <strong>" .$uploaddate ."</strong></p>" ."\n \t" ."Aegub: <strong>" .$expiredate ."</strong>" ."\n \t" .htmlspecialchars_decode($newscontentfromdb) ."\n \t" .'<button type="button"><a class="nupp" href="alterNews.php?id=' .$newsidfromdb .'">Uudise "' .$newstitlefromdb .'" muutmine/kustutamine</a></button>' ."\n";
		} elseif($expiredate != null and $filename != null) {
			$newshtml .= "\t" ."<h3>" .$newstitlefromdb ."</h3> \n \t" ."<p>Autor: <strong>" .$authorfirstnamefromdb ." " .$authorlastnamefromdb ."</strong></p>" ."\n \t" ."<p>Lisatud: <strong>" .$uploaddate ."</strong></p>" ."\n \t" ."<p>Aegub: <strong>" .$expiredate ."</strong></p>" ."\n \t" .'<img src="../photoupload_news/' .$filename .'">' ."\n \t" .htmlspecialchars_decode($newscontentfromdb) ."\n \t" .'<button type="button"><a class="nupp" href="alterNews.php?id=' .$newsidfromdb .'">Uudise "' .$newstitlefromdb .'" muutmine/kustutamine</a></button>' ."\n";
		}
	}
	if(!empty($newshtml)) {
		$newshtml = "<div> \n" .$newshtml ."\n  </div> \n";
	} else {
		$newshtml = "<p>Kahjuks uudiseid ei leitud!</p> \n";
	}
	$stmt->close();
	$conn->close();
  
  require("header.php");
?>

  <style>
	.nupp {
		-webkit-appearance: button;
		-moz-appearance: button;
		appearance: button;

		text-decoration: none;
		color: initial;
	}
  </style>
  <img src="../img/vp_banner_improved.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p>See veebileht on loodud õppetöö kaigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
    <p><a href="?logout=1">Logi välja</a></p>
	<p><a href="home.php">Tagasi avalehele</a></p>
  <hr>
  <h2>Uudiste muutmine</h2>
  <p id="notice">
  <?php echo $newshtml; ?>
  </p>
  <hr>
</body>
</html>