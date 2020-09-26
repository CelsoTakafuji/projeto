<?php
	ob_start();
	session_start();
	unset($_SESSION['autUser']);
	header('Location: index.php');
	ob_end_flush();
	
?>