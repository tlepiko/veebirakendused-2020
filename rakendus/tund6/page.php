<?php

	require("../../../../configuration.php");
	require("fnc.php");
	require("classes/Session.class.php");
	
	SessionManager::sessionStart("vr20", 0, "/~taavi.lepiko/", "tigu.hk.tlu.ee");

	$myName = "Taavi Lepiko";
	$fullTimeNow = date("d.m.Y H:i:s");
	//<p>Lehe avamise hetkel oli: <strong>31.01.2020 11:32:07</strong></p>
	$timeHTML = "\n <div style='float:left; width:33.33%; padding:5px; box-sizing:border-box;'><p>Lehe avamise hetkel oli aeg: <strong>" .$fullTimeNow ."</strong></p> \n";
	$hourNow = date("H");
	//$hourNow = 15; //taustavärvi ja tekstivärvi testimiseks
	$partOfDay = "hägune aeg";
	$newsHTML = readNews(1);


	if($hourNow < 10) {
		$partOfDay = "hommik";
		$bgColor = "#F3D340";
		$textColor ="#000000";
	}
	if($hourNow > 10 and $hourNow < 18) {
		$partOfDay = "aeg aktiivselt tegutseda!";
		$bgColor = "#4CED9A";
		$textColor ="#000000";
	}
	if($hourNow > 18) {
		$partOfDay = "õhtune rahulik aeg!";
		$bgColor = "#0023FF";
		$textColor ="#FFFFFF";
	}
	$partOfDayHTML = "<p>Käes on " .$partOfDay ."!</p> \n";
	//$bg = '<body style="background-color:' .$bgColor .';">';

	//info semestri kulgemise kohta
	$semesterStart = new DateTime("2020-01-27");
	$semesterEnd = new DateTime("2020-06-22");
	$semesterDuration = $semesterStart->diff($semesterEnd);
	// var_dump($semesterDuration);
	$today = new DateTime("now");
	//$today = new DateTime("2020-01-25"); //kuupäeva testimiseks
	$fromSemesterStart = $semesterStart->diff($today);


	//<p>Semester on hoos: <meter value="" min="0" max=""></meter></p>
	if($fromSemesterStart->format("%r%a")<0) {
		$semesterProgressHTML = "<p>Semester pole veel alanud!</p></div>";
	} elseif($fromSemesterStart->format("%r%a") > $semesterDuration->format("%r%a")) {
		$semesterProgressHTML = "<p>Semester on juba lõppenud!</p></div>";
	} else {
		$semesterProgressHTML = '<p>Semester on hoos: <meter min="0" max="';
		$semesterProgressHTML .= $semesterDuration->format("%r%a"); //%r on miinusmärk vajadusel, %a annab kogu päevade arvu
		$semesterProgressHTML .= '" value="';
		$semesterProgressHTML .= $fromSemesterStart->format("%r%a");
		$semesterProgressHTML .= '"></meter>.</p></div>' . "\n";
	}

	//loen ette antud kataloogist pildifailid
	$picsDir = "../../pics/";
	$photoTypesAllowed = ["image/jpeg", "image/png"]; //muutuja selle jaoks, millist tüüpi failid on lubatud
	$photoList = []; //tühi list, kuhu tüübikontrolli läbinud failid paigutatakse
	$allFiles = array_slice(scandir($picsDir), 2); //ilma array_slice käsuta näitab massiivis ka kausta nime kus failid asuvad, kui ka eelmise kausta nime
	foreach($allFiles as $file) {
		$fileInfo = getimagesize($picsDir .$file);
		if(in_array($fileInfo["mime"], $photoTypesAllowed) == true) {
			array_push($photoList, $file);
		}
	}
	$photoRandomNum = []; //tühi massiiv juhuslike numbrite jaoks
	$randomImageHTML = '<div style="content:; clear:both; display:table; box-sizing:border-box;">';
	$photoCount = count($photoList); // loeb mitu pilti on massiivis
	if(count($photoList)>0) {
		do {
			$photoNum2 = mt_rand(0,$photoCount-1);
			if(in_array($photoNum2, $photoRandomNum) == false) {
				array_push($photoRandomNum, $photoNum2);
				$randomImageHTML .= '<div style="float:left; width:33.33%; padding:5px; box-sizing:border-box;"><img src="' .$picsDir .$photoList[$photoNum2] .'" width=100% height: 300px alt="juhuslik pilt Haapsalust"></div>' ."\n";
			}
		} while (count($photoRandomNum)<=2); //piirang tsüklile
		$randomImageHTML .= '</div>';
	}
	else {
		$randomImageHTML .= '<p>Pildid või piltide kaust puudub!</p>' ."\n"; //Tekst mis ilmub, kui pildid puuduvad
	}
	
	$notice = null;
	$email = null;
	$emailError = null;
	$passwordError = null;
    
	if(isset($_POST["login"])){
		if (isset($_POST["email"]) and !empty($_POST["email"])){
		  $email = clean_inputs($_POST["email"]);
		} else {
		  $emailError = "Palun sisesta kasutajatunnusena e-posti aadress!";
		}
	  
		if (!isset($_POST["password"]) or strlen($_POST["password"]) < 8){
		  $passwordError = "Palun sisesta parool, vähemalt 8 märki!";
		}
	  
		if(empty($emailError) and empty($passwordError)){
		   $notice = signIn($email, $_POST["password"]);
		} else {
			$notice = "Ei saa sisse logida!";
		}
	}
?>

<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2020</title>
	<style>
		.loeng {
			background:<?php echo $bgColor ?>;
		}
		p,h1,h2,label,a,h3 {
			color:<?php echo $textColor ?>;
		}
	</style>
</head>
<body class="loeng">
	<h1><?php echo $myName; ?></h1>
	<p class="test">See leht on valminud õppetöö raames!</p>
	<div style='content:; clear:both; width:100%; display:table; box-sizing:border-box;'>
		<div style="float:left; width:33.33%; padding:5px; box-sizing:border-box;">
 			<h2>Logi sisse</h2>
			<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<label>E-mail (kasutajatunnus):</label><br>
				<input type="email" name="email" value="<?php echo $email; ?>"><span><?php echo $emailError; ?></span><br>
				<label>Salasõna:</label><br>
				<input name="password" type="password"><span><?php echo $passwordError; ?></span><br><br>
				<input name="login" type="submit" value="Logi sisse!"><span><?php echo $notice; ?></span>
			</form>

			
			<p>Loo endale <a href="newuser.php">kasutajakonto</a>!</p>
		</div>
			<?php
				echo $timeHTML;
				echo $partOfDayHTML;
				echo $semesterProgressHTML;
			?>
		<div style="float:left; width:33.33%; padding:5px; box-sizing:border-box;">
		<p><a href="logi.php">Lisa tegevus</a>!</p>
		<p><a href="summary_log.php">Tegevuslogi</a>!</p>
		<p><a href="addnews.php">Lisa uudis</a>!</p>
		<p><a href="news.php">Loe uudiseid</a>!</p>
		<p><a href="photoUpload.php">Lae üles foto</a>!</p>
		<p><a href="semipublicgallery.php">Galerii kasutajatele</a>!</p>
		<p><a href="privategallery.php">Privaatgalerii</a>!</p>
		</div>
		</div>
			<?php
				echo $randomImageHTML;
			?>	
	<div>
		<?php echo $newsHTML; ?>
	</div>
</body>
</html>