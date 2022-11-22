<?php
	try {
			$dsn = "mysql:host=localhost;dbname=webshop;charset=utf8";
			//Host+DBName+Zeichensatz,Benutzername und Passwort
			$pdo = new PDO($dsn,'root','');
	} catch ( PDOException $e ) {
		die ( "Es ist ein Fehler beim Verbindungsaufbau zur Datenbank aufgetreten!" );
	}
	
?>