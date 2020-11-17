<?php
  require("usesession.php");
  require("../../../config.php");
  require("fnc_photo.php");
  require("fnc_common.php");
  require("classes/Photoupload_class.php");
  require("../../../config_photo.php");
  
  $tolink = '<script src="javascript/checkfilesize.js" defer></script>' ."\n";
    
  $inputerror = "";
  $notice = null;
    
  //kui klikiti submit, siis ...
  if(isset($_POST["photosubmit"])) {
	//võtame kasutusele klassi
	$myphoto = new Photoupload($_FILES["photoinput"], $filetype);
	
	$privacy = intval($_POST["privinput"]);
	$alttext = test_input($_POST["altinput"]);
	
	if(empty($myphoto->isPhoto($photoFileTypes))) {
		$inputerror = "Valitud fail ei ole pilt!";
	}
	
	if(empty($inputerror) and !empty($myphoto->isAllowedSize($filesizelimit))) {
		$inputerror = "Liiga suur fail!";
	}
	
	//kui vigu pole ...
	if(empty($inputerror)) {
		$filename = $myphoto->createFileName($filenameprefix, $filenamesuffix);
		
		//teeme pildi väiksemaks
		$myphoto->resizePhoto($photomaxwidth, $photomaxheight, true);
		//lisame vesimärgi
		$myphoto->addWatermark($watermark);
		//salvestame vähendatud pildi
		$result = $myphoto->saveimage($photouploaddir_normal .$filename);
		if($result == 1){
			$notice .= "Vähendatud pildi salvestamine õnnestus!";
		} else {
			$inputerror .= "Vähendatud pildi salvestamisel tekkis tõrge!";
		}
		//teeme pisipildi
		$myphoto->resizePhoto($thumbsize, $thumbsize);
		$result = $myphoto->saveimage($photouploaddir_thumb .$filename);
		if($result == 1){
			$notice .= " Pisipildi salvestamine õnnestus!";
		} else {
			$inputerror .= " Pisipildi salvestamisel tekkis tõrge!";
		}
		//eemaldan klassi
		unset($myphoto);
		
		//salvestame originaalpildi
		if(empty($inputerror)) {
			if(move_uploaded_file($_FILES["photoinput"]["tmp_name"], $photouploaddir_orig .$filename)){
				$notice .= " Originaalfaili üleslaadimine õnnestus!";
			} else {
				$inputerror .= " Originaalfaili üleslaadimisel tekkis tõrge!";
			}
		}
		
		if(empty($inputerror)) {
			$result = storePhotoData($filename, $alttext, $privacy);
			if($result == 1){
				$notice .= " Pildi info lisati andmebaasi!";
				$privacy = 1;
				$alttext = null;
			} else {
				$inputerror .= " Pildi info andmebaasi salvestamisel tekkis tõrge!";
			}
		} else {
			$inputerror .= " Tekkinud vigade tõttu pildi andmeid ei salvestatud!";
		}
		
	}
  }
  
  require("header.php");
?>
  <img src="../img/vp_banner_improved.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p>See veebileht on loodud õppetöö kaigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
    <p><a href="?logout=1">Logi välja</a></p>
  <hr>
  
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
    <label for="photoinput">Vali pildifail</label>
	<input id="photoinput" name="photoinput" type="file" required>
	<br>
	<label for="altinput">Lisa pildi lühikirjeldus (alternatiivtekst)</label>
	<input id="altinput" name="altinput" type="text" value="<?php echo $alttext; ?>">
	<br>
	<label>Privaatsustase</label>
	<br>
	<input id="privinput1" name="privinput" type="radio" value="1" <?php if($privacy == 1){echo " checked";} ?>>
	<label for="privinput1">Privaatne (ainult ise näen)</label>
	<input id="privinput2" name="privinput" type="radio" value="2" <?php if($privacy == 2){echo " checked";} ?>>
	<label for="privinput2">Klubi liikmetele (sisseloginud kasutajad näevad)</label>
	<input id="privinput3" name="privinput" type="radio" value="3" <?php if($privacy == 3){echo " checked";} ?>>
	<label for="privinput3">Avalik (kõik näevad)</label>
	<br>
	<input type="submit" id="photosubmit" name="photosubmit" value="Lae foto üles">
  </form>
  <p id="notice">
  <?php
	echo $inputerror;
	echo $notice;
  ?>
  </p>
  <hr>
  <p><a href="home.php">Tagasi avalehele</a></p>
  <hr>
</body>
</html>