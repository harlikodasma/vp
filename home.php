<?php
	$username = "Harli Kodasma";
	$fulltimenow = date("d.m.Y H:i:s");
	$hournow = date("H");
	$partofday = "lihtsalt aeg";
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
	$fromsemesterstartuntiltoday = $semesterstart->diff($today);
	$daysfromsemesterstart = $fromsemesterstartuntiltoday->format("%r%a");
	$daysuntilend = $semesterdurationdays - $daysfromsemesterstart;
	$percentagecalculation = round(($daysfromsemesterstart * 100) / $semesterdurationdays, 2);
	if($daysfromsemesterstart < 0) {
		$daysfromsemesterstartformatted = -$daysfromsemesterstart;
		$semesterpercentage = "Semestri õppetööst on tehtud 0%.";
		if($daysfromsemesterstartformatted == 1) {
			$semesterprintout = "Semester ei ole veel alanud. Alguseni on jäänud " .$daysfromsemesterstartformatted ." päev.";
		} else {
		$semesterprintout = "Semester ei ole veel alanud. Alguseni on jäänud " .$daysfromsemesterstartformatted ." päeva.";
		}
	} elseif($daysfromsemesterstart == 0) {
		$semesterpercentage = "Semestri õppetööst on tehtud 0%.";
		$semesterprintout = "Täna algab semester.";
	} elseif($daysfromsemesterstart > 0 and $daysfromsemesterstart <= $semesterdurationdays) {
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
	}
	//2020-8-31
	//2020-12-13
?>
<!DOCTYPE html>
<html lang="et">
<head>
  <meta charset="utf-8">
  <title><?php echo $username; ?> veebiprogrammeerimine</title>

</head>
<body>
  <h1><?php echo $username; ?></h1>
  <p>See veebileht on loodud õppetöö kaigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  <p>Kodutööna panin ühe lause juurde.</p>
  <p>Lehe avamise hetk: <?php echo $fulltimenow; ?>.</p>
  <p><?php echo "Praegu on " .$partofday ."."; ?></p>
  
  <p>Semester kestab kokku <?php echo $semesterdurationdays; ?> päeva.</p>
  <p><?php echo $semesterprintout; ?></p>
  <p><?php echo $semesterpercentage; ?></p>
</body>
</html>