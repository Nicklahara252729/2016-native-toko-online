<?php
ob_start();
include"../koneksi.php";
include"../validpage-admin.php";
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
</body>
</html>
<?php
mysql_close($koneksi);
ob_flush();
?>