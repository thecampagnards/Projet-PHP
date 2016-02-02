<?php
function db_connect()
{
	$host="localhost"; // Host name
	$username="root"; // Mysql username
	$password=""; // Mysql password
	$db_name="mini_projet"; // Database name
	try
	{
		//$db = new PDO('mysql:host=172.17.7.211;dbname=cinema', 'cir2', 'isen');
		$db = mysqli_connect($host,$username,$password,$db_name);
	}
	catch(Exception $e)
	{
		die('Erreur : ' . $e->getMessage());
	}
	return $db;
}
?>