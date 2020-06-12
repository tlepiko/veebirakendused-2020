<?php
	require("../../../../configuration.php");
	require("fnc.php");
	$newsHTML = readNews(1);
	if (isset($_POST["amount"])) {
		$newsHTML = readNews($_POST["amount"]);
	}
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2020</title>
</head>
<body>
	<h1>Uudised</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<div>
		<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
			<select onchange="this.form.submit();" name="amount">
				<option>Mitu uudist kuvada?</option>
				<?php
					$amounts = array("1","5","10","15");
					foreach ($amounts as $value) {
				?>
				<option><?php echo $value; ?></option>
				<?php } ?>
			</select>
		</form>
	</div>
	<?php echo $newsHTML ?>
	<br>
</body>
</html>