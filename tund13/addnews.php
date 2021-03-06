<?php
  require("usesession.php");
  require("../../../config.php");
  require("fnc_photo.php");
  require("fnc_common.php");
  require("classes/Photoupload_class.php");
  require("../../../config_photo.php");
  
  $tolink = "\t" .'<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js"></script>' ."\n";
  $tolink .= "\t" .'<script>tinymce.init({selector:"textarea#newsinput", plugins: "link", menubar: "edit",});</script>' ."\n";
    
  $inputerror = "";
  $newsnotice = null;
  $news = null;
  $newstitle = null;
  $expireday = null;
  $expiremonth = null;
  $expireyear = null;
  $expireerror = null;
  $photoerror = null;
  $monthnameset = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
    
  //kui klikiti submit, siis ...
  if(isset($_POST["newssubmit"])) {
	if(strlen($_POST["newstitleinput"]) == 0) {
		$inputerror .= "Uudise pealkiri on puudu!";
	} else {
		$newstitle = test_input($_POST["newstitleinput"]);
	}
	if(strlen($_POST["newsinput"]) == 0) {
		$inputerror .= " Uudise sisu on puudu!";
	} else {
		$news = test_input($_POST["newsinput"]);
		//htmlspecialchars teisendab html noolsulud
		//nende tagasisaamiseks htmlspecialchars_decode(uudis)
	}
	if(!empty($_POST["expiredayinput"]) and (empty($_POST["expiremonthinput"]) or empty($_POST["expireyearinput"]))) {
		$expireday = $_POST["expiredayinput"];
		$expireerror = "Vali kas kõik osad aegumiskuupäevast või ära vali midagi!";
	}
	if(!empty($_POST["expiremonthinput"]) and (empty($_POST["expiredayinput"]) or empty($_POST["expireyearinput"]))) {
		$expiremonth = $_POST["expiremonthinput"];
		$expireerror = "Vali kas kõik osad aegumiskuupäevast või ära vali midagi!";
	}
	if(!empty($_POST["expireyearinput"]) and (empty($_POST["expiremonthinput"]) or empty($_POST["expiredayinput"]))) {
		$expireyear = $_POST["expireyearinput"];
		$expireerror = "Vali kas kõik osad aegumiskuupäevast või ära vali midagi!";
	}
	if(!empty($_POST["expireyearinput"]) and !empty($_POST["expiremonthinput"]) and !empty($_POST["expiredayinput"])) {
		$expireday = test_input($_POST["expiredayinput"]);
		$expiremonth = test_input($_POST["expiremonthinput"]);
		$expireyear = test_input($_POST["expireyearinput"]);
		if(checkdate($expiremonth, $expireday, $expireyear)) {
				$tempdate = new DateTime($expireyear ."-" .$expiremonth ."-" .$expireday);
				$expiredate = $tempdate->format("Y-m-d");
			} else {
				$expiredate = null;
				$expireerror = "Kuupäev ei ole reaalne!";
			}
	}
	if(!empty($_FILES["photoinput"]["name"]) and !empty($_FILES["photoinput"]["type"]) and !empty($_FILES["photoinput"]["size"])) {
		$myphoto = new Photoupload($_FILES["photoinput"], $filetype);
		if(empty($myphoto->isPhoto($photoFileTypes))) {
			$photoerror = "Valitud fail ei ole pilt!";
		}
		if(empty($photoerror) and !empty($myphoto->isAllowedSize($filesizelimit))) {
			$photoerror = "Liiga suur fail!";
		}
		if(empty($photoerror)) {
			$filename = $myphoto->createFileName($filenameprefix_news, $filenamesuffix);
			$myphoto->resizePhoto($photomaxwidth, $photomaxheight, true);
			$myphoto->addWatermark($watermark);
			$result = $myphoto->saveimage($photouploaddir_news .$filename);
			if($result != 1){
				$photoerror .= "Pildi salvestamisel tekkis tõrge!";
			}
			unset($myphoto);
		}
		if(empty($photoerror)) {
			$result = storeNewsPhotoData($filename);
			if($result != 1){
				$inputerror .= " Pildi info andmebaasi salvestamisel tekkis tõrge!";
			}
		}
	}
	
	if(empty($inputerror) and empty($expireerror) and empty($photoerror)) {
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], "if20_harli_kod_vp_1");
		if(empty($result)) {
			$stmt = $conn->prepare("INSERT INTO vpnews (userid, title, content, expire) VALUES (?, ?, ?, ?)");
			echo $conn->error;
			$stmt->bind_param("isss", $_SESSION["userid"], $newstitle, $news, $expiredate);
			if($stmt->execute()){
				$newsnotice = "Uudis on salvestatud!";
			} else {
				$newsnotice = "Uudise salvestamisel läks midagi valesti.";
			}
			$stmt->close();
		} else {
			$stmt = $conn->prepare("SELECT MAX(vpnewsphotos_id) FROM vpnewsphotos");
			echo $conn->error;
			$stmt->bind_result($photoid);
			$stmt->execute();
			$stmt->fetch();
			$stmt->close();
			
			$stmt = $conn->prepare("INSERT INTO vpnews (userid, title, content, expire, vpnewsphotos_id) VALUES (?, ?, ?, ?, ?)");
			echo $conn->error;
			$stmt->bind_param("isssi", $_SESSION["userid"], $newstitle, $news, $expiredate, $photoid);
			if($stmt->execute()){
				$newsnotice = "Uudis on salvestatud!";
			} else {
				$newsnotice = "Uudise salvestamisel läks midagi valesti.";
			}
			$stmt->close();
		}
		$conn->close();
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
    <label for="newstitleinput">Sisesta uudise pealkiri:</label>
	<input id="newstitleinput" name="newstitleinput" type="text" value="<?php echo $newstitle; ?>" required>
	<br>
	<label for="newsinput">Kirjuta uudis:</label>
	<textarea id="newsinput" name="newsinput"><?php echo $news; ?></textarea>
	<br>
	<label for="photoinput">Soovi korral vali pildifail:</label>
	<input id="photoinput" name="photoinput" type="file">
	<br>
	<br>
	<label>Soovi korral määra uudise aegumiskuupäev:</label>
	<br>
	<label for="expiredayinput">Päev: </label>
	    <?php
			echo '<select name="expiredayinput" id="expiredayinput">' ."\n";
			echo '<option value="" selected disabled>päev</option>' ."\n";
			for ($i = 1; $i < 32; $i ++){
				echo '<option value="' .$i .'"';
				if ($i == $expireday){
					echo " selected ";
				}
				echo ">" .$i ."</option> \n";
			}
			echo "</select> \n";
		?>
	  <label for="expiremonthinput">Kuu: </label>
	  <?php
	    echo '<select name="expiremonthinput" id="expiremonthinput">' ."\n";
		echo '<option value="" selected disabled>kuu</option>' ."\n";
		for ($i = 1; $i < 13; $i ++){
			echo '<option value="' .$i .'"';
			if ($i == $expiremonth){
				echo " selected ";
			}
			echo ">" .$monthnameset[$i - 1] ."</option> \n";
		}
		echo "</select> \n";
	  ?>
	  <label for="expireyearinput">Aasta: </label>
	  <?php
	    echo '<select name="expireyearinput" id="expireyearinput">' ."\n";
		echo '<option value="" selected disabled>aasta</option>' ."\n";
		for ($i = date("Y"); $i <= date("Y") + 20; $i ++){
			echo '<option value="' .$i .'"';
			if ($i == $expireyear){
				echo " selected ";
			}
			echo ">" .$i ."</option> \n";
		}
		echo "</select> \n";
	  ?>
	<br>
	<br>
	<input type="submit" id="newssubmit" name="newssubmit" value="Salvesta uudis">
  </form>
  <p id="notice">
  <?php
	echo $inputerror ." ";
	echo $newsnotice;
	echo $expireerror ." ";
	echo $photoerror;
  ?>
  </p>
  <hr>
  <p><a href="home.php">Tagasi avalehele</a></p>
  <hr>
</body>
</html>