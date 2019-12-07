<?php
session_start();

include($_SERVER['DOCUMENT_ROOT'].'/config.php');

$dbLink = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		
if (mysqli_error($dbLink)) die("Could not connect to the database");

$qSubmit = "UPDATE users SET Thoughts='".mysqli_real_escape_string($dbLink, $_POST["thoughts"])."' WHERE Email='".$_SESSION["user"]."'";

if(!mysqli_query($dbLink, $qSubmit)) {
	header('HTTP/1.1 500 Internal Server Error');
	die("Could not update the database");
}
?>