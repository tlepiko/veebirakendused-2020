<?php
require ("../../../../configuration.php");
require ("fnc.php");
$summaryHTML = readLogsAll();
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="utf-8">
    <title>Õppelogi</title>
</head>
<body>
    <h1>Õppelogi</h1>
<?php echo $summaryHTML; ?>
</body>
</html>