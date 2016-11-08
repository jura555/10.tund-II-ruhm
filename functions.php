<?php

	require("/home/juriguss/config.php");

	/* ALUSTAN SESSIOONI */
	session_start();
		
	/* ÜHENDUS */
	$database = "if16_juri";
	$mysqli = new mysqli($serverHost, $serverUsername, $serverPassword, $database);
	
	/* KLASSID */
	
	require("../class/Helper.class.php");
	$Helper = new Helper();

?>