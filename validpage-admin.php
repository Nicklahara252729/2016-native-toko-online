<?php
ob_start();
if(!isset($_SESSION['user'])){
	session_start();
}
if(!isset($_SESSION['user'])){
	header("location:../404.php?level=user atau admin");
}else{
	$level=$_SESSION['level'];
	if(!($level=="user" or $level=="admin")){
		header("location:../404.php?level=user atau admin");
	}
}
ob_flush();
?>