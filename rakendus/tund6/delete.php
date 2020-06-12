<?php
	
	//sessiooni käivitamine või kasutamine
	//session_start();
	//var_dump($_SESSION);
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

	require("../../../../configuration.php");
    require("fnc_gallery.php");
    require("fnc.php");

	
	$privacy = 3;
	$page = 1;
	$limit = 20;
	$picCount = countPics(3);
	
	if(!isset($_GET["page"]) or $_GET["page"] < 1){
		$page = 1;
	  } elseif(round($_GET["page"] - 1) * $limit >= $picCount){
		$page = intval(ceil($picCount / $limit));
	  }	else {
		$page = intval($_GET["page"]);
	  }

	  $privateThumbnails = deleteMyPictureThumbs($privacy, $page, $limit);
	  

	  if(isset($_POST['deleteSelected']) and !empty($_POST['check'])){
		$checkbox = $_POST['check'];
		for($i=0;$i<count($checkbox);$i++){
		$del_id = $checkbox[$i];
		deleteId($del_id);
		}
	}
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Veebirakendused ja nende loomine 2020</title>
	<link rel="stylesheet" type="text/css" href="style/gallery.css">
	<link rel="stylesheet" type="text/css" href="style/modal.css">
	<script src="javascript/modal.js" defer></script>
	<style>
</style>
</head>
<body>
<div id="modalArea" class="modalArea">
	<!--Sulgemisnupp-->
	<span id="modalClose" class="modalClose">&times;</span>
	<!--pildikoht-->
	<div class="modalHorizontal">
		<div class="modalVertical">
		<p id="modalCaption"></p>
			<img src="empty.png" id="modalImg" class="modalImg" alt="galeriipilt">
			<br>
			<div id="rating" class="modalRating">
				<label><input id="rate1" name="rating" type="radio" value="1">1</label>
				<label><input id="rate2" name="rating" type="radio" value="2">2</label>
				<label><input id="rate3" name="rating" type="radio" value="3">3</label>
				<label><input id="rate4" name="rating" type="radio" value="4">4</label>
				<label><input id="rate5" name="rating" type="radio" value="5">5</label>
				<button id="storeRating">Salvesta hinnang!</button>
				<br>
				
				
				<p id="avgRating"></p>
			</div>
			<div id="deleting" class="modalDeleting">
			<button id="deleting">Kustuta!</button>
				<br>
				</div>
		</div>
	</div>
  </div>
  <h1>Piltide kustutamine</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<p><?php echo $_SESSION["userFirstName"]. " " .$_SESSION["userLastName"] ."."; ?> Logi <a href="?logout=1">välja</a>!</p>
	<p>Tagasi <a href="home.php">avalehele</a>!</p>
	<hr>
	<?php
		if($page > 1){
			echo '<a href="?page=' .($page - 1) .'">Eelmine leht</a> | ';
		} else {
			echo "<span>Eelmine leht</span> | ";
		}
		if($page *$limit <= $picCount){
			echo '<a href="?page=' .($page + 1) .'">Järgmine leht</a>';
		} else {
			echo "<span> Järgmine leht</span>";
		}
	?>
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<br><button type="submit" class="submit" name="deleteSelected">Kustuta märgitud</button>
    <div>
		<br>
        <div class='gallery' id='gallery'>
		<?php
			echo $privateThumbnails;
		?>
		</form>
	</div>
	<hr>
</body>
</html>