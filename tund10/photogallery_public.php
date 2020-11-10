<?php
  require("usesession.php");
  require("../../../config.php");
  require("fnc_photo.php");
  require("../../../config_photo.php");
  
  $tolink = '<link rel="stylesheet" type="text/css" href="style/gallery.css">' ."\n";
    
  $notice = null;
  $gallerypagelimit = 3;
  $page = 1;
  $photocount = countPublicPhotos($privacy);
  if(!isset($_GET["page"]) or $_GET["page"] < 1) {
	  $page = 1;
  } elseif(round($_GET["page"] - 1) * $gallerypagelimit >= $photocount) {
	  $page = ceil($photocount / $gallerypagelimit);
  } else {
	  $page = $_GET["page"];
  }
  //$publicphotothumbshtml = readPublicPhotoThumbs(2);
  $publicphotothumbshtml = readPublicPhotoThumbsPage(2, $gallerypagelimit, $page);
  
  require("header.php");
?>
  <img src="../img/vp_banner_improved.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p>See veebileht on loodud õppetöö kaigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
    <p><a href="?logout=1">Logi välja</a></p>
	<p><a href="home.php">Tagasi avalehele</a></p>
  <hr>
  <h2>Fotogalerii</h2>
  <p>
	<?php
		if($page > 1) {
			echo '<span><a href="?page=' .($page - 1) .'">Eelmine leht</a></span> |' ."\n";
		} else {
			echo '<span>Eelmine leht</a></span> |' ."\n";
		}
		if($page * $gallerypagelimit < $photocount) {
			echo '<span><a href="?page=' .($page + 1) .'">Järgmine leht</a></span>' ."\n";
		} else {
			echo '<span>Järgmine leht</a></span>' ."\n";
		}
	?>
  </p>
  <?php
	echo $publicphotothumbshtml;
  ?>
</body>
</html>