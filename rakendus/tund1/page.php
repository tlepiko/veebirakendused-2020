<?php
	$myName = "Taavi Lepiko";
	$fullTimeNow = date("d.m.Y H:i:s");
	//<p>Lehe avamise hetkel oli: <strong>31.01.2020 11:32:07</strong></p>
	$timeHTML = "\n <p>Lehe avamise hetkel oli aeg: <strong>" .$fullTimeNow ."</strong></p> \n";
	$hourNow = date("H");
	//$hourNow = 15; //taustavärvi ja tekstivärvi testimiseks
	$partOfDay = "hägune aeg";

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
		$semesterProgressHTML = "<p>Semester pole veel alanud!</p>";
	} elseif($fromSemesterStart->format("%r%a") > $semesterDuration->format("%r%a")) {
		$semesterProgressHTML = "<p>Semester on juba lõppenud!</p>";
	} else {
		$semesterProgressHTML = '<p>Semester on hoos: <meter min="0" max="';
		$semesterProgressHTML .= $semesterDuration->format("%r%a"); //%r on miinusmärk vajadusel, %a annab kogu päevade arvu
		$semesterProgressHTML .= '" value="';
		$semesterProgressHTML .= $fromSemesterStart->format("%r%a");
		$semesterProgressHTML .= '"></meter>.</p>' . "\n";
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
	$randomImageHTML = '';
	$photoCount = count($photoList); // loeb mitu pilti on massiivis
	if(count($photoList)>0) {
		do {
			$photoNum2 = mt_rand(0,$photoCount-1);
			if(in_array($photoNum2, $photoRandomNum) == false) {
				array_push($photoRandomNum, $photoNum2);
				$randomImageHTML .= '<img src="' .$picsDir .$photoList[$photoNum2] .'" width=33% alt="juhuslik pilt Haapsalust">' ."\n";
			}
		} while (count($photoRandomNum)<=2); //piirang tsüklile
	}
	else {
		$randomImageHTML .= '<p>Pildid või piltide kaust puudub!</p>' ."\n"; //Tekst mis ilmub, kui pildid puuduvad
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
		p,h1 {
			color:<?php echo $textColor ?>;
		}
	</style>
</head>
<body class="loeng">
	<h1><?php echo $myName; ?></h1>
	<p class="test">See leht on valminud õppetöö raames!</p>
	<?php
		echo $timeHTML;
		echo $partOfDayHTML;
		echo $semesterProgressHTML;
		// echo $randomImageHTML;
	?>
		<div style="content:''; clear:both; display:table;:">
			<div style="float:left; width:33.33%; padding:5px;">
				<img src="img_snow.jpg" alt="Snow" style="width:100%">
			</div>
			<div style="float:left; width:33.33%; padding:5px;">
				<img src="img_forest.jpg" alt="Forest" style="width:100%">
			</div>
			<div style="float:left; width:33.33%; padding:5px;">
				<img src="img_mountains.jpg" alt="Mountains" style="width:100%">
			</div>
		</div>
</body>
</html>