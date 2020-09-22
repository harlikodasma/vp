<?php
	//var_dump($_POST); post input väärtused, premade array
	require("../../../config.php");
	
	//$username = "Harli Kodasma"; //pole enam vaja, header.php-s olemas
	$fulltimenow = date("d.m.Y H:i:s");
	$hournow = date("H");
	$partofday = "lihtsalt aeg";
	$weekdaynameset = ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev", "pühapäev"];
	$monthnameset = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
	$daynow = date("d");
	$monthnumbernow = date("n");
	$yearnow = date("Y");
	$fullclocknow = date("H:i:s");
	//echo $weekdaynameset; //nii ei saa arrayd vaadata
	//var_dump($weekdaynameset); //nii saab arrayd vaadata
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
	require("header.php");
?>

  <img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1><?php echo $username; ?></h1>
  <p>See veebileht on loodud õppetöö kaigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  
  <ul>
    <li><a href="mottesisestus.php">Sisesta oma mõte siin</a></li>
    <li><a href="motetevaade.php">Vaata sisestatud mõtteid siin</a></li>
	<li><a href="listfilms.php">Loe filmiinfot</a></li>
	<li><a href="addfilm.php">Filmiinfo lisamine</a></li>
  </ul>
  
  <p>Lehe avamise hetk: <?php echo $weekdaynameset[$weekdaynow - 1] .", " .$daynow .". " .$monthnameset[$monthnumbernow - 1] ." " .$yearnow .", kell " .$fullclocknow; ?>.</p>
  <p><?php echo "Praegu on " .$partofday ."."; ?></p>
  
  <p>Semester kestab kokku <?php echo $semesterdurationdays; ?> päeva.</p>
  <p><?php echo $semesterprintout; ?></p>
  <p><?php echo $semesterpercentage; ?></p>
  <hr>
  <?php echo $imghtml; ?>
  <hr>
</body>
</html>