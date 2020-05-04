<?php
session_start();
	unset($_SESSION['nim']);
	unset($_SESSION['password']);
	session_destroy();
	header("location:index.php?pesan=logout");
?>