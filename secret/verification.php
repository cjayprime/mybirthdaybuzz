<?php
	
	session_start();
	
	if((!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) || (!isset($_SESSION['username']) || empty($_SESSION['username'])))
	header("Location: /");
	
?>