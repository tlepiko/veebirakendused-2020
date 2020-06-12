<?php
	require("../../../../configuration.php");
	require("fnc.php");
	require("classes/Session.class.php");
	SessionManager::sessionStart("vr20", 0, "/~taavi.lepiko/", "tigu.hk.tlu.ee");

	//kas pole sisseloginud
	if(!isset($_SESSION["userid"])){
		//jõuga avalehele
		header("Location: page.php");
	}
	
	//login välja
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: page.php");
	}
	$courseName = null;
	$activityType = null;
	$elapsedTime = null;
	$error = null;
	$courseSelect = courseSelect();
	$activitySelect = activitySelect();
	$latestHTML = readLogsLatest();

	if (isset($_POST["submitButton"])) {
		if (isset($_POST["courseName"]) and !empty(clean_inputs($_POST["courseName"]))) {
			$courseName = $_POST["courseName"];
		} else {
			$error = "<div style='color:red;'>Aine pole valitud! </div>";
		}
		if (isset($_POST["activityType"]) and !empty(clean_inputs($_POST["activityType"]))) {
			$activityType = $_POST["activityType"];
		} else {
			$error .= "<div style='color:red;'>Tegevus pole valitud! </div>";
		}
		if (isset($_POST["elapsedTime"]) and !empty(clean_inputs($_POST["elapsedTime"]))) {
			$elapsedTime = $_POST["elapsedTime"];
		} else {
			$error .= "<div style='color:red;'>Aeg pole valitud! </div>";
		}
	
		if (empty($error)){
			$response = saveActivity($courseName, $activityType, $elapsedTime, $_SESSION["userid"]);
				if ($response == 1) {
					$error = "<p style='color:green;'>Tegevus salvestatud!</p>";
				} else {
					$error = "<p style='color:red;'>Viga salvestamisel!</p>";
				}
		}
	}

?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Õppimise logimine</title>
</head>
<body>
	<h1>Õppimise logi sisestus</h1>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<label>Aine nimetus:</label><br>
<select name="courseName">
<option value="">Vali aine</option>
<?php echo $courseSelect; ?><br>
</select><br><br>
<label>Õpppimise liik:</label><br>
<select name="activityType">
<option value="">Vali tegevus</option>
<?php echo $activitySelect; ?><br>
</select><br><br>
<label>Aeg:</label><br>
<input type="number" min=".25" max="24" step=".25" name="elapsedTime" placeholder="Vali aeg"><br><br>
<input type="submit" name="submitButton" value="Salvesta"><br>
<?php echo $error ?>
<?php echo $latestHTML; ?>

</select>
</body>
</html>