<?php
	require("../../../config.php");
	
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], "if20_harli_kod_vp_1");
	$stmt = $conn->prepare("SELECT filename, alttext, privacy, userid FROM vpphotos WHERE vpphotos_id = ?");
	$stmt->bind_param("i", $_REQUEST["photo"]);
	$stmt->bind_result($filenamefromdb, $alttextfromdb, $privacyfromdb, $useridfromdb);
	$stmt->execute();
	$stmt->fetch();
	$stmt->close();
	$conn->close();
	
	if(empty($_REQUEST["thumb"])) {
		$imagedir = "../photoupload_normal/";
	} else {
		$imagedir = "../photoupload_thumb/";
	}
	
	if($privacyfromdb == 1) {
		require("usesession.php");
		if($useridfromdb != $_SESSION["userid"]) {
			exit("Puudub õigus seda pilti näha!");
		}
	}
	
	if($privacyfromdb == 2) {
		require("usesession.php");
		if(empty($_SESSION["userid"])) {
			exit("Puudub õigus seda pilti näha!");
		}
	}
	
	$imgext = explode(".", $filenamefromdb);
	if($imgext[1] == "jpeg" or $imgext[1] == "jpg") {
		header("Content-type: image/jpeg");
	} elseif($imgext[1] == "png") {
		header("Content-type: image/png");
	} elseif($imgext[1] == "gif") {
		header("Content-type: image/gif");
	}
	
	readfile($imagedir .$filenamefromdb);
?>