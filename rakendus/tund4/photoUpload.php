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
		
		//kas üldse on pilt?
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		if($check !== false){
			//failitüübi väljaselgitamine ja sobivuse kontroll
			if($check["mime"] == "image/jpeg"){
				$imageFileType = "jpg";
			} elseif ($check["mime"] == "image/png"){
				$imageFileType = "png";
			} else {
				$error = "Ainult jpg ja png pildid on lubatud! "; 
			}
		} else {
			$error = "Valitud fail ei ole pilt! ";
		}
		
		//ega pole liiga suur
		if($_FILES["fileToUpload"]["size"] > $fileUploadSizeLimit){
			$error .= "Valitud fail on liiga suur! ";
		}
		
		//loome oma nime failile
		$timestamp = microtime(1) * 10000;
		$fileName = $fileNamePrefix . $timestamp . "." .$imageFileType;
		
		//$originalTarget = $originalPhotoDir .$_FILES["fileToUpload"]["name"];
		$originalTarget = $originalPhotoDir .$fileName;
		
		//äkki on fail olemas?
		if(file_exists($originalTarget)){
			$error .= "Selline fail on juba olemas!";
		}
		
		//kui vigu pole
		if($error == null){
			if($imageFileType == "jpg"){
				$myTempImage = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
			}
			if($imageFileType == "png"){
				$myTempImage = imagecreatefrompng($_FILES["fileToUpload"]["tmp_name"]);
			}
			$myNewImage = resizePhoto($myTempImage, $maxWidth, $maxHeight);
			$result = saveImgToFile($myNewImage, $normalPhotoDir, $fileName, $imageFileType);

			if($result == 1) {
				$notice = "Vähendatud pilt laeti üles! ";
			} else {
				$error = "Vähendatud pildi salvestamisel tekkis viga!";
			}

			$myNewImage = resizePhoto($myTempImage, $thumbSize, $thumbSize);
			$result = saveImgToFile($myNewImage, $thumbPhotoDir, $fileName, $imageFileType);
			
			if($result == 1) {
				$notice .= "Thumbnail laeti üles! ";
			} else {
				$error .= "Thumbnaili salvestamisel tekkis viga!";
			}

			if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $originalTarget)){
				$notice .= "Originaalpilt laeti üles! ";
			} else {
				$error .= " Pildi üleslaadimisel tekkis viga! ";
			}
			
			if($error == null){
				$result = addPhotoData($_POST["altText"], $_POST["privacy"], $fileName, $_FILES["fileToUpload"]["name"]);
				if($result == "ok"){
					$notice .= "Pildi andmed lisati andmebaasi!";
				} else {
					$error .= " Pildi andmete lisamisel andmebaasi tekkis tehniline tõrge: " .$result;
				}
			}

			imagedestroy($myTempImage);
			imageDestroy($myNewImage);
			
		}//kui vigu pole
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