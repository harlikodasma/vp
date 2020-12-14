<?php
	require("../../../config.php");
	require("fnc_common.php");
	require("fnc_people.php");
	
	$database = "if20_harli_kod_vp_1";
	
	$inputerror = null;
	$message = null;
	$gender = null;
	$category = null;
	$datatype = null;
	$numberinput = null;
	
	if(isset($_POST["entrysubmit"])) {
		if(empty($_POST["genderinput"])) {
			$inputerror = "Sugu on valimata!";
		} else {
			$gender = $_POST["genderinput"];
		}
		if(empty($_POST["categoryinput"])) {
			$inputerror .= " Inimese kategooria on valimata!";
		} else {
			$category = $_POST["categoryinput"];
		}
		if(empty($_POST["datatypeinput"])) {
			$inputerror .= " Vali, kas inimesed läksid sisse või välja!";
		} else {
			$datatype = $_POST["datatypeinput"];
		}
		if(empty($_POST["numberinput"])) {
			$inputerror .= " Sisesta arv!";
		} elseif($_POST["numberinput"] < 1) {
			$inputerror .= " Arv peab olema positiivne!";
		} else {
			$numberinput = $_POST["numberinput"];
		}
		
		if(empty($inputerror)) {
			$gender = $_POST["genderinput"];
			$category = $_POST["categoryinput"];
			$datatype = $_POST["datatypeinput"];
			$numberinput = test_input($_POST["numberinput"]);
			
			$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
			
			$stmt = $conn->prepare("SELECT current, max FROM eksam WHERE gender = ? AND category = ?");
			echo $conn->error;
			$stmt->bind_param("ii", $gender, $category);
			$stmt->bind_result($currentfromdb, $maxfromdb);
			$stmt->execute();
			$stmt->fetch();
			echo $stmt->error;
			$stmt->close();
			
			$stmt = $conn->prepare("SELECT max FROM eksam WHERE gender = 0 AND category = 0");
			echo $conn->error;
			$stmt->bind_result($alltimemaxfromdb);
			$stmt->execute();
			$stmt->fetch();
			echo $stmt->error;
			$stmt->close();
			
			if(empty($currentfromdb) and empty($maxfromdb)) {
				$stmt = $conn->prepare("INSERT INTO eksam (gender, category, current, max) VALUES (?, ?, ?, ?)");
				echo $conn->error;
				$stmt->bind_param("iiii", $gender, $category, $numberinput, $numberinput);
				if($stmt->execute()) {
					$message = "Andmed edukalt salvestatud!";
				}
				echo $stmt->error;
				$stmt->close();
			} elseif($datatype == 1) {
				$currenttodb = $currentfromdb + $numberinput;
				if($maxfromdb < ($numberinput + $currentfromdb)) {
					$stmt = $conn->prepare("UPDATE eksam SET current = ?, max = ? WHERE gender = ? AND category = ?");
					echo $conn->error;
					$stmt->bind_param("iiii", $currenttodb, $currenttodb, $gender, $category);
					if($stmt->execute()) {
						$message = "Andmed edukalt salvestatud!";
					}
					echo $stmt->error;
					$stmt->close();
				} else {
					$stmt = $conn->prepare("UPDATE eksam SET current = ? WHERE gender = ? AND category = ?");
					echo $conn->error;
					$stmt->bind_param("iii", $currenttodb, $gender, $category);
					if($stmt->execute()) {
						$message = "Andmed edukalt salvestatud!";
					}
					echo $stmt->error;
					$stmt->close();
				}
				$totalpeople = totalPeople();
				if($alltimemaxfromdb < $totalpeople) {
					$stmt = $conn->prepare("UPDATE eksam SET max = ? WHERE gender = 0 AND category = 0");
					echo $conn->error;
					$stmt->bind_param("i", $totalpeople);
					$stmt->execute();
					echo $stmt->error;
					$stmt->close();
				}
			} elseif($datatype == 2) {
				if(($currentfromdb - $numberinput) < 0) {
					$inputerror = "Sa ei saa panna väljuma rohkem inimesi, kui neid majas on!";
				} else {
					$currenttodb = $currentfromdb - $numberinput;
					$stmt = $conn->prepare("UPDATE eksam SET current = ? WHERE gender = ? AND category = ?");
					echo $conn->error;
					$stmt->bind_param("iii", $currenttodb, $gender, $category);
					if($stmt->execute()) {
						$message = "Andmed edukalt salvestatud!";
					}
					echo $stmt->error;
					$stmt->close();
				}
			}
			$conn->close();
		}
	}
	
	require("header.php");
?>
  <img src="../img/vp_banner_improved.png" alt="Bänner">
  <h1>Eksam 14.12.2020 - Harli Kodasma</h1>
  <p>See veebileht on loodud eksamitööna.</p>
  <hr>
  
  <h2>Uute sisenemiste ja väljumiste sisestamine</h2>
  
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label for="genderinput">Sugu:</label>
	  <input type="radio" name="genderinput" value="1" <?php if($gender == "1"){echo "checked";} ?>><label for="gendermale">Mees</label><input type="radio" name="genderinput" value="2" <?php if($gender == "2"){echo "checked";} ?>><label for="genderfemale">Naine</label>
	  <br>
	  <label for="categoryinput">Kategooria:</label>
	  <input type="radio" name="categoryinput" value="1" <?php if($category == "1"){echo "checked";} ?>><label for="category_student">Üliõpilane</label><input type="radio" name="categoryinput" value="2" <?php if($category == "2"){echo "checked";} ?>><label for="category_teacher">Õppejõud</label>
	  <br>
	  <label for="datatypeinput">Andmetüüp:</label>
	  <input type="radio" name="datatypeinput" value="1" <?php if($datatype == "1"){echo "checked";} ?>><label for="datatype_entry">Sisenemine</label><input type="radio" name="datatypeinput" value="2" <?php if($datatype == "2"){echo "checked";} ?>><label for="datatype_exit">Väljumine</label>
	  <br>
	  <label for="numberinput">Sellist tüüpi inimeste arv:</label>
	  <input type="number" name="numberinput" id="numberinput" value="<?php echo $numberinput;?>">
	  <br>
	  <br>
	  <input type="submit" name="entrysubmit" value="Salvesta kirje">
  </form>
  <p><?php echo $inputerror; echo $message; ?></p>
  <hr>
  <p><a href="overview.php">Ülevaade sisestatud andmetest</a></p>
  <hr>
</body>
</html>