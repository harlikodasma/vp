<?php
	require("../../../config.php");
	require("fnc_films.php");
	//$filmhtml = readfilms();
	require("header_logged.php");
?>

  <p><a href="home.php">Tagasi avalehele</a></p>
  <hr>
  <b>Filmid:</b>
  <?php echo readfilms(); ?>
  <hr>
</body>
</html>