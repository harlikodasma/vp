<?php
	require("usesession.php");
	require("../../../config.php");
	require("header.php");
	
	$database = "if20_harli_kod_1";
	
	$notice = null;
	$filmtitledropdown = null;
	$genredropdown = null;
	
	//filmi pealkirjade listi tegemine
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT movie_id, title FROM movie");
	echo $conn->error;
	$stmt->bind_result($movieidfromdb, $movietitlefromdb);
	if($stmt->execute()) {
		while($stmt->fetch()) {
		$filmtitledropdown .= "\n \t \t" .'<option value="' .$movieidfromdb .'">' .$movietitlefromdb .'</option>';
		}
	} else {
		$notice = $stmt->error();
	}
	$stmt->close();
	
	//zanrite listi tegemine
	$stmt = $conn->prepare("SELECT genre_id, genre_name FROM genre");
	echo $conn->error;
	$stmt->bind_result($genreidfromdb, $genrefromdb);
	if($stmt->execute()) {
		while($stmt->fetch()) {
		$genredropdown .= "\n \t \t" .'<option value="' .$genreidfromdb .'">' .$genrefromdb .'</option>';
		}
	} else {
		$notice = $stmt->error();
	}
	$stmt->close();
	
	//andmete saatmine db-sse
	if(isset($_POST["filmrelationsubmit"])) {
		if(isset($_POST["filminput"]) and isset($_POST["genreinput"])) {
			$stmt = $conn->prepare("INSERT INTO movie_genre (movie_id, genre_id) VALUES (?, ?)");
			echo $conn->error;
			$stmt->bind_param("ii", $_POST["filminput"], $_POST["genreinput"]);
			if($stmt->execute()) {
				$notice = "Seos salvestatud!";
			} else {
				$notice = $stmt->error();
			}
			$stmt->close();
		} else {
			$notice = "Üks valikutest on tegemata!";
		}
	}
	$conn->close();
?>

  <img src="../img/vp_banner_improved.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1>Noic veebileht</h1>
  <p>See veebileht on loodud õppetöö kaigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  <hr>
  
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="filminput">Film: </label>
	<select name="filminput" id="filminput">
		<option value="" selected disabled>Vali film</option>
		<?php echo $filmtitledropdown; ?>
	</select>
	<br>
	<label for="genreinput">Žanr: </label>
	<select name="genreinput" id="genreinput">
		<option value="" selected disabled>Vali žanr</option>
		<?php echo $genredropdown; ?>
	</select>
	<br>
	<input type="submit" name="filmrelationsubmit" value="Sisesta seos andmebaasi">
	<?php echo $notice; ?>
  </form>
  
  <hr>
  <p><a href="home.php">Tagasi avalehele</a></p>
  <hr>
</body>
</html>