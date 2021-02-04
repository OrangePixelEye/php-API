<?php
	try
	{
		$connection = new PDO('mysql: host=localhost:3306; dbname=NiceBank', 'root', '');
		$connection->exec("set names utf8");
	}catch(PDOexception $error){
        echo($error);
		//header("location: index.php?error=1");
		die();
	}
?>