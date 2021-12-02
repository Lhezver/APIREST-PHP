<?php
	$username = 'root';
	$servername = 'localhost';
	$password = 'root';
	$dbname = 'api';
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	mysqli_set_charset($conn, 'utf8');
?>