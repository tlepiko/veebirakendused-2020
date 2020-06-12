<?php
require ("../../../../configuration.php");
require ("fnc.php");
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
$summaryHTML = readLogsAll();
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="utf-8">
    <title>Õppelogi</title>
    <p>Tagasi <a href="home.php">avalehele</a>!</p>
</head>
<body>
    <h1>Õppelogi</h1>
<?php echo $summaryHTML; ?>
</body>
</html>