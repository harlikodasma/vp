<?php
	//käivitan sessiooni
	//session_start();
	require("classes/SessionManager.class.php");
	SessionManager::sessionStart("vp", 0, "/~harli/", "greeny.cs.tlu.ee");
	//var_dump($_POST); post input väärtused, premade array
	require("../../../config.php");
	require("fnc_user.php");
	require("fnc_common.php");
	
	$emailerror = null;
	$passworderror = null;
	$loginstatus = null;
	$email = null;
	$newshtml = null;
	
	$fulltimenow = date("d.m.Y H:i:s");
	$hournow = date("H");
	$partofday = "lihtsalt aeg";
	$weekdaynameset = ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev", "pühapäev"];
	$monthnameset = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
	$daynow = date("d");
	$monthnumbernow = date("n");
	$yearnow = date("Y");
	$fullclocknow = date("H:i:s");
	$weekdaynow = date("N");
	
	if($hournow < 6) {
		$partofday = "uneaeg";
	}
	if($hournow > 5 and $hournow < 8) {
		$partofday = "kooli minemise aeg";
	}
	if($hournow >= 8 and $hournow <= 18) {
		$partofday = "õppimise aeg";
	}
	if($hournow > 18 and $hournow < 20) {
		$partofday = "söömise aeg";
	}
	if($hournow > 19 and $hournow < 24) {
		$partofday = "teleka aeg";
	}
	
	//vaatame semestri kulgemist
	$semesterstart = new DateTime("2020-8-31");
	$semesterend = new DateTime("2020-12-13");
	$semesterduration = $semesterstart->diff($semesterend);
	$semesterdurationdays = $semesterduration->format("%r%a");
	$today = new DateTime("now");
	$todayformatted = $today->format("Y-n-j");
	$semesterstartformatted = $semesterstart->format("Y-n-j");
	$fromsemesterstartuntiltoday = $semesterstart->diff($today);
	$daysfromsemesterstart = $fromsemesterstartuntiltoday->format("%r%a");
	$daysuntilend = $semesterdurationdays - $daysfromsemesterstart;
	$percentagecalculation = round(($daysfromsemesterstart * 100) / $semesterdurationdays, 2);
	if($todayformatted == $semesterstartformatted) {
		$semesterpercentage = "Semestri õppetööst on tehtud 0%.";
		$semesterprintout = "Täna algab semester.";
	} elseif($daysfromsemesterstart > 0 and $daysfromsemesterstart < $semesterdurationdays) {
		$isitone = $semesterdurationdays - $daysfromsemesterstart;
		$semesterpercentage = "Semestri õppetööst on tehtud " .$percentagecalculation ."%";
		if($isitone == 1) {
			$semesterprintout = "Semester käib. See on käinud juba " .$daysfromsemesterstart ." päeva." ." Lõpuni on jäänud " .$daysuntilend ." päev.";
		} elseif($daysfromsemesterstart == 1) {
			$semesterprintout = "Semester käib. See on käinud juba " .$daysfromsemesterstart ." päev." ." Lõpuni on jäänud " .$daysuntilend ." päeva.";
		} elseif($isitone > 1 and $daysfromsemesterstart > 1) {
			$semesterprintout = "Semester käib. See on käinud juba " .$daysfromsemesterstart ." päeva." ." Lõpuni on jäänud " .$daysuntilend ." päeva.";
		}
	} elseif($daysfromsemesterstart == $semesterdurationdays) {
		$semesterprintout = "Täna on semestri viimane päev. Semester on käinud " .$semesterdurationdays ." päeva.";
		$semesterpercentage = "Semestri õppetööst on tehtud " .$percentagecalculation ."%";
	} elseif($daysfromsemesterstart > $semesterdurationdays) {
		$daysfromsemesterend = $daysfromsemesterstart - $semesterdurationdays;
		$semesterpercentage = "Semestri õppetööst on tehtud 100%.";
		if($daysfromsemesterend == 1) {
			$semesterprintout = "Semester on läbi. Semestri lõpust on möödas " .$daysfromsemesterend ." päev.";
		} else {
		$semesterprintout = "Semester on läbi. Semestri lõpust on möödas " .$daysfromsemesterend ." päeva.";
		}
	} elseif($daysfromsemesterstart == -0) {
		$fromsemesterstartuntiltoday = $today->diff($semesterstart);
		$daysfromsemesterstart += 1;
		$semesterpercentage = "Semestri õppetööst on tehtud 0%.";
		$semesterprintout = "Semester ei ole veel alanud. Alguseni on jäänud " .$daysfromsemesterstart ." päev.";
	} elseif($daysfromsemesterstart < 0) {
		$daysfromsemesterstart = -$daysfromsemesterstart + 1;
		$semesterpercentage = "Semestri õppetööst on tehtud 0%.";
		$semesterprintout = "Semester ei ole veel alanud. Alguseni on jäänud " .$daysfromsemesterstart ." päeva.";
	}
	//2020-8-31
	//2020-12-13
	
	//annan ette lubatud pildivormingute loendi
	$picfiletypes = ["image/jpeg", "image/png"];
	//loeme piltide kataloogi sisu ja näitame pilte
	$allfiles = array_slice(scandir("../vp_pics/"), 2); //slice sest 2 esimest pole õiged failid
	//$picfiles = array_slice($allfiles, 2);
	$picfiles = [];
	foreach($allfiles as $thing) {
		$fileinfo = getImagesize("../vp_pics/" .$thing);
		if(in_array($fileinfo["mime"], $picfiletypes)) {
			array_push($picfiles, $thing);
		}
	}
	
	//paneme kõik pildid ekraanile
	$piccount = count($picfiles);
	//$i = $i + 1;
	//$i ++;
	//$i += 2;
	//enne panime siin kõik pildid nähtavale, nüüd valime ühe suvalise
	//$imghtml = "";
	//for($i = 0; $i < $piccount; $i ++) {
		//$imghtml .= '<img src="../vp_pics/' .$picfiles[$i] .'" alt="Tallinna Ülikool">'; //.= append
	//}
	$whichpic = mt_rand(0, ($piccount - 1));
	$imghtml = '<img src="../vp_pics/' .$picfiles[$whichpic] .'" alt="Tallinna Ülikool">';
	
	//login
	if(!empty($_POST["loginsubmit"])) {
		if(empty($_POST["emailinput"])) {
			$emailerror = "E-post sisestamata!";
		} else {
			if((filter_var(test_input($_POST["emailinput"]), FILTER_VALIDATE_EMAIL)) == null) {
				$emailerror = "Ebakorrektne e-posti aadress!";
			} else {
				$email = test_input($_POST["emailinput"]);
			}
		}
		if(strlen($_POST["passwordinput"]) < 8) {
			if(empty($_POST["passwordinput"])) {
				$passworderror = "Salasõna sisestamata!";
			} else {
				$passworderror = "Salasõna peab olema vähemalt 8 tähemärki pikk!";
			}
		}
		if(empty($emailerror) and empty($passworderror)) {
			$loginstatus = signin($email, $_POST["passwordinput"]);
		}
	}
	
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], "if20_harli_kod_vp_1");
	$stmt = $conn->prepare("SELECT title, content, firstname, lastname, added, expire, filename FROM vpnews JOIN vpusers ON vpusers.vpusers_id = vpnews.userid LEFT JOIN vpnewsphotos ON vpnews.vpnewsphotos_id = vpnewsphotos.vpnewsphotos_id WHERE vpnews.deleted IS NULL AND expire IS NULL OR expire > CURDATE() ORDER BY vpnews_id DESC LIMIT 5");
	echo $conn->error;
	$stmt->bind_result($newstitlefromdb, $newscontentfromdb, $authorfirstnamefromdb, $authorlastnamefromdb, $uploaddate, $expiredate, $filename);
	$stmt->execute();
	while($stmt->fetch()) {
		if($expiredate == null and $filename != null) {
			$newshtml .= "\t" ."<h3>" .$newstitlefromdb ."</h3> \n \t" ."<p>Autor: <strong>" .$authorfirstnamefromdb ." " .$authorlastnamefromdb ."</strong></p>" ."\n \t" ."<p>Lisatud: <strong>" .$uploaddate ."</strong></p>" ."\n \t" .'<img src="../photoupload_news/' .$filename .'">' ."\n \t" .htmlspecialchars_decode($newscontentfromdb) ."\n";
		} elseif($expiredate == null and $filename == null) {
			$newshtml .= "\t" ."<h3>" .$newstitlefromdb ."</h3> \n \t" ."<p>Autor: <strong>" .$authorfirstnamefromdb ." " .$authorlastnamefromdb ."</strong></p>" ."\n \t" ."Lisatud: <strong>" .$uploaddate ."</strong>" ."\n \t" .htmlspecialchars_decode($newscontentfromdb) ."\n";
		} elseif($expiredate != null and $filename == null) {
			$newshtml .= "\t" ."<h3>" .$newstitlefromdb ."</h3> \n \t" ."<p>Autor: <strong>" .$authorfirstnamefromdb ." " .$authorlastnamefromdb ."</strong></p>" ."\n \t" ."<p>Lisatud: <strong>" .$uploaddate ."</strong></p>" ."\n \t" ."Aegub: <strong>" .$expiredate ."</strong>" ."\n \t" .htmlspecialchars_decode($newscontentfromdb) ."\n";
		} elseif($expiredate != null and $filename != null) {
			$newshtml .= "\t" ."<h3>" .$newstitlefromdb ."</h3> \n \t" ."<p>Autor: <strong>" .$authorfirstnamefromdb ." " .$authorlastnamefromdb ."</strong></p>" ."\n \t" ."<p>Lisatud: <strong>" .$uploaddate ."</strong></p>" ."\n \t" ."<p>Aegub: <strong>" .$expiredate ."</strong></p>" ."\n \t" .'<img src="../photoupload_news/' .$filename .'">' ."\n \t" .htmlspecialchars_decode($newscontentfromdb) ."\n";
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

  <!--<audio src="../sound/templeos.mp3" autoplay="autoplay" loop="loop"></audio>-->
  
  <img src="../img/vp_banner_improved.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1>Noic veebileht</h1>
  <p>See veebileht on loodud õppetöö kaigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  <hr>
  
  <ul>
	<li><a href="createaccount.php">Uue kasutaja loomine</a></li>
  </ul>
  
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label for="emailinput">Kasutajatunnus (e-posti aadress)</label>
	  <input type="email" name="emailinput" id="emailinput" value="<?php echo $email; ?>"> <!--placeholder="rick.tald@yandex.ru"-->
	  <span><?php echo $emailerror; ?></span>
	  <br>
	  <label for="passwordinput">Salasõna</label>
	  <input type="password" name="passwordinput" id="passwordinput"> <!--placeholder="😂😂😂😂😂"-->
	  <span><?php echo $passworderror; ?></span>
	  <br>
	  <input type="submit" name="loginsubmit" value="Logi sisse">
    </form>
  <b><?php echo $loginstatus; ?></b>
  
  <p>Lehe avamise hetk: <?php echo $weekdaynameset[$weekdaynow - 1] .", " .$daynow .". " .$monthnameset[$monthnumbernow - 1] ." " .$yearnow .", kell " .$fullclocknow; ?>.</p>
  <p><?php echo "Praegu on " .$partofday ."."; ?></p>
  
  <p>Semester kestab kokku <?php echo $semesterdurationdays; ?> päeva.</p>
  <p><?php echo $semesterprintout; ?></p>
  <p><?php echo $semesterpercentage; ?></p>
  <hr>
  <?php echo $imghtml; ?>
  <hr>
  <h2>Kõige uuemad uudised</h2>
  <?php echo $newshtml; ?>
</body>
</html>