<?php
  require("../../../../configuration.php");
  require("fnc.php");
    
  $notice = null;
  $name = null;
  $surname = null;
  $email = null;
  $gender = null;
  $birthMonth = null;
  $birthYear = null;
  $birthDay = null;
  $birthDate = null;
  $monthNamesET = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni","juuli", "august", "september", "oktoober", "november", "detsember"];
  
  //muutujad võimalike veateadetega
  $nameError = null;
  $surnameError = null;
  $birthMonthError = null;
  $birthYearError = null;
  $birthDayError = null;
  $birthDateError = null;
  $genderError = null;
  $emailError = null;
  $passwordError = null;
  $confirmpasswordError = null;
  
  //kui on uue kasutaja loomise nuppu vajutatud
  if(isset($_POST["submitUserData"])){
	//kui on sisestatud nimi
	if(isset($_POST["firstName"]) and !empty($_POST["firstName"])){
		$name = clean_inputs($_POST["firstName"]);
	} else {
		$nameError = "Palun sisestage eesnimi!";
	} //eesnime kontrolli lõpp
	
	if (isset($_POST["surName"]) and !empty($_POST["surName"])){
		$surname = clean_inputs($_POST["surName"]);
	} else {
		$surnameError = "Palun sisesta perekonnanimi!";
	}
	
	if(isset($_POST["gender"])){
	    $gender = intval($_POST["gender"]);
	} else {
		$genderError = "Palun märgi sugu!";
	}

	//kontrollime, kas sünniaeg sisestati ja kas on korrektne
	  if(isset($_POST["birthDay"]) and !empty($_POST["birthDay"])){
		  $birthDay = intval($_POST["birthDay"]);
	  } else {
		  $birthDayError = "Palun vali sünnikuupäev!";
	  }
	  
	  if(isset($_POST["birthMonth"]) and !empty($_POST["birthMonth"])){
		  $birthMonth = intval($_POST["birthMonth"]);
	  } else {
		  $birthMonthError = "Palun vali sünnikuu!";
	  }
	  
	  if(isset($_POST["birthYear"]) and !empty($_POST["birthYear"])){
		  $birthYear = intval($_POST["birthYear"]);
	  } else {
		  $birthYearError = "Palun vali sünniaasta!";
	  }
	  
	  //vaja ka kuupäeva valiidsust kontrollida ja kuupäev kokku panna
	  if(empty($birthMonthError) and empty($birthYearError) and empty($birthDayError)){
		  if(checkdate($birthMonth, $birthDay, $birthYear)){
			  $tempDate = new DateTime($birthYear ."-" .$birthMonth ."-" . $birthDay);
			  $birthDate = $tempDate->format("Y-m-d");
		  } else {
			  $birthDateError = "Valitud kuupäev on vigane!";
		  }
	  }
	  
	//email ehk kasutajatunnus
	
	  if (isset($_POST["email"]) and !empty($_POST["email"])){
		$email = clean_inputs($_POST["email"]);
		$email = filter_var($email, FILTER_VALIDATE_EMAIL);
		if ($email === false) {
			$emailError = "Palun sisesta korrektne e-postiaadress!";
		}
	  } else {
		  $emailError = "Palun sisesta e-postiaadress!";
	  }
	  
	  //parool ja selle kaks korda sisestamine
	  
	  if (!isset($_POST["password"]) or empty($_POST["password"])){
		$passwordError = "Palun sisesta salasõna!";
	  } else {
		  if(strlen($_POST["password"]) < 8){
			  $passwordError = "Liiga lühike salasõna (sisestasite ainult " .strlen($_POST["password"]) ." märki).";
		  }
	  }
	  
	  if (!isset($_POST["confirmpassword"]) or empty($_POST["confirmpassword"])){
		$confirmpasswordError = "Palun sisestage salasõna kaks korda!";  
	  } else {
		  if($_POST["confirmpassword"] != $_POST["password"]){
			  $confirmpasswordError = "Sisestatud salasõnad ei olnud ühesugused!";
		  }
	  }

	
	//Kui kõik on korras, salvestame
	if(empty($nameError) and empty($surnameError) and empty($birthMonthError) and empty($birthYearError) and empty($birthDayError) and empty($birthDateError) and empty($genderError) and empty($emailError) and empty($passwordError) and empty($confirmpasswordError)){
		$notice = signUp($name, $surname, $email, $gender, $birthDate, $_POST["password"]);
		if($notice == "ok"){
			$notice = "Uus kasutaja on loodud!";
			$name = null;
			$surname = null;
			$email = null;
			$gender = null;
			$birthMonth = null;
			$birthYear = null;
			$birthDay = null;
			$birthDate = null;
		} elseif($notice == "nope") {
			$notice = "Selline e-posti aadress on juba kasutuses!";
		}
		else {
			$notice = "Uue kasutaja salvestamisel tekkis tehniline tõrge: " .$notice;
		}
	}//kui kõik korras
	
  } //kui on nuppu vajutatud
  
  
?>

<!DOCTYPE html>
<html lang="et">
  <head>
    <meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2020</title>
  </head>
  <body>
    <h1>Loo endale kasutajakonto</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<hr>
	
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label>Eesnimi:</label><br>
	  <input name="firstName" type="text" value="<?php echo $name; ?>"><span><?php echo $nameError; ?></span><br>
      <label>Perekonnanimi:</label><br>
	  <input name="surName" type="text" value="<?php echo $surname; ?>"><span><?php echo $surnameError; ?></span>
	  <br>
	  
	  <input type="radio" name="gender" value="2" <?php if($gender == "2"){		echo " checked";} ?>><label>Naine</label>
	  <input type="radio" name="gender" value="1" <?php if($gender == "1"){		echo " checked";} ?>><label>Mees</label><br>
	  <span><?php echo $genderError; ?></span>
	  <br>
	  
	  <label>Sünnikuupäev: </label>
	  <?php
	    //sünnikuupäev
	    echo '<select name="birthDay">' ."\n";
		echo "\t \t" .'<option value="" selected disabled>päev</option>' ."\n";
		for($i = 1; $i < 32; $i ++){
			echo "\t \t" .'<option value="' .$i .'"';
			if($i == $birthDay){
				echo " selected";
			}
			echo ">" .$i ."</option> \n";
		}
		echo "\t </select> \n";
	  ?>
	  	  <label>Sünnikuu: </label>
	  <?php
	    echo '<select name="birthMonth">' ."\n";
		echo "\t \t" .'<option value="" selected disabled>kuu</option>' ."\n";
		for ($i = 1; $i < 13; $i ++){
			echo "\t \t" .'<option value="' .$i .'"';
			if ($i == $birthMonth){
				echo " selected ";
			}
			echo ">" .$monthNamesET[$i - 1] ."</option> \n";
		}
		echo "</select> \n";
	  ?>
	  <label>Sünniaasta: </label>
	  <?php
	    echo '<select name="birthYear">' ."\n";
		echo "\t \t" .'<option value="" selected disabled>aasta</option>' ."\n";
		for ($i = date("Y") - 15; $i >= date("Y") - 110; $i --){
			echo "\t \t" .'<option value="' .$i .'"';
			if ($i == $birthYear){
				echo " selected ";
			}
			echo ">" .$i ."</option> \n";
		}
		echo "</select> \n";
	  ?>

	  <span><?php echo $birthDateError ." " .$birthDayError ." " .$birthMonthError ." " .$birthYearError; ?></span>
	  
	  <br>
	  <label>E-mail (kasutajatunnus):</label><br>
	  <input type="email" name="email" value="<?php echo $email; ?>"><span><?php echo $emailError; ?></span><br>
	  <label>Salasõna (min 8 tähemärki):</label><br>
	  <input name="password" type="password"><span><?php echo $passwordError; ?></span><br>
	  <label>Korrake salasõna:</label><br>
	  <input name="confirmpassword" type="password"><span><?php echo $confirmpasswordError; ?></span><br>
	  <input name="submitUserData" type="submit" value="Loo kasutaja"><span><?php echo $notice; ?></span>
	</form>
	<hr>
	<p>Tagasi <a href="page.php">avalehele</a></p>
    <hr>
  </body>
</html>