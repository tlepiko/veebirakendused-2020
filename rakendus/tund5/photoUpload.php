<?php
	require("classes/Session.class.php");
	require("fnc.php");
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
	
	require("../../../../configuration.php");
	require("classes/Photo.class.php");
	
	//pildi üleslaadimine osa
	
	$originalPhotoDir = "../../uploadOriginalPhoto/";
	$normalPhotoDir = "../../uploadNormalPhoto/";
	$thumbPhotoDir = "../../uploadThumbnail/";
	$error = null;
	$notice = null;
	$imageFileType = null;
	$fileUploadSizeLimit = 1048576;
	$fileNamePrefix = "vr_";
	$fileName = null;
	$maxWidth = 600;
	$maxHeight = 400;
	$thumbSize = 100;
	
	if(isset($_POST["photoSubmit"])){
		$photoUp = new Photo($_FILES["fileToUpload"], $fileUploadSizeLimit, $fileNamePrefix);

		//kood mida praegu kirjutan
		if ($photoUp->checkFileType()) {
			$photoUp->resizePhoto($maxWidth, $maxHeight);
			$photoUp->addWatermark("vr_watermark.png", 3, 10);
			$result = $photoUp->saveImgToFile($normalPhotoDir .$photoUp->fileName);
			if($result == 1) {
				$notice .= "Vähendatud pilt laeti üles! ";
			} else {
				$error .= "Vähendatud pildi salvestamisel tekkis viga!";
			}
		}
			
			$photoUp->resizePhoto($thumbSize, $thumbSize);
			$result = $photoUp->saveImgToFile($thumbPhotoDir .$photoUp->fileName);
			if($result == 1) {
				$notice .= "Thumbnail laeti üles! ";
			} else {
				$error .= "Thumbnaili salvestamisel tekkis viga! ";
			}
			
			$originalTarget = $originalPhotoDir .$fileName;
			if($error == null){
				$result = addPhotoData($_POST["altText"], $_POST["privacy"], $photoUp->fileName, $_FILES["fileToUpload"]["name"]);
				if($result == "ok"){
					$notice .= "Pildi andmed lisati andmebaasi!";
				} else {
					$error .= " Pildi andmete lisamisel andmebaasi tekkis tehniline tõrge: " .$result;
				}
			}
	}
	
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2020</title>
</head>
<body>
	<h1>Fotode üleslaadimine</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<p><?php echo $_SESSION["userFirstName"]. " " .$_SESSION["userLastName"] ."."; ?> Logi <a href="?logout=1">välja</a>!</p>
	<p>Tagasi <a href="home.php">avalehele</a>!</p>
	<hr>
	
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
		<label>Vali pildifail! </label><br>
		<input type="file" name="fileToUpload"><br>
		<label>Alt tekst: </label><input type="text" name="altText"><br>
		<label>Privaatsus</label><br>
		<label for="priv1">Privaatne</label><input id="priv1" type="radio" name="privacy" value="3" checked><br>
		<label for="priv2">Sisseloginud kasutajatele</label><input id="priv2" type="radio" name="privacy" value="2"><br>
		<label for="priv3">Avalik</label><input id="priv3" type="radio" name="privacy" value="1"><br>
		
		<input type="submit" name="photoSubmit" value="Lae valitud pilt üles!">
		<span><?php echo $error; echo $notice; ?></span>
	</form>
	
	<br>
	<hr>
</body>
</html>