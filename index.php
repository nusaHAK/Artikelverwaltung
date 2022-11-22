<!DOCTYPE html>
<html lang="de">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Sortimentübersicht</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	<h1>Sortimentübersicht</h1>
    <?php

		try {
			$dsn = "mysql:host=localhost;dbname=webshop;charset=utf8";
			$pdo = new PDO($dsn,'root','');
		} catch ( PDOException $e ) {
			  die ( "Es ist ein Fehler beim Verbindungsaufbau zur Datenbank aufgetreten!" );
		}
	
		try {
			//SQL-Stmt vorbereiten
			$statement = $pdo->prepare("SELECT artikelnummer, artikelbezeichnung,
			                            farbe, groesse, preis, image FROM w_artikel");
			
			$statement->execute();
		
		} catch ( PDOException $e ) {
			die ( "Es ist ein Fehler bei der Verarbeitung der Anfrage aufgetreten!" );
		}
		
		if($statement->rowCount() > 0){
		//Tabelle mit dem ResultSet aufbauen
		?>
			<table border=1>
			<th>Artikelnummer</th>
			<th>Artikelbezeichnung</th>
			<th>Farbe</th>
			<th>Größe</th>
			<th>Preis</th>
			<th>Abbildung</th>
			<th>Bearbeiten</th>
			<th>Löschen</th>
		<?php
			//immer eine Zeile aus dem ResultSet holen und ausgeben
			while($row = $statement->fetch()) {
				echo "<tr><td>".$row['artikelnummer']."</td>";
				echo "<td>".$row['artikelbezeichnung']."</td>";
				echo "<td>".$row['farbe']."</td>";
				echo "<td>".$row['groesse']."</td>";
				echo "<td>".$row['preis']."</td>";
				echo "<td><img src='" . $row['image'] . "'". 'alt="keine Abbildung"' . ' width="100"' . ' height="100" ></td>';
				echo "<td><a href='update_artikel_fileUpload.php?artikelnummer=". $row['artikelnummer']. "'>Bearbeiten</a></td>";
				echo "<td><a href='delete_artikel.php?artikelnummer=". $row['artikelnummer']. "'>Löschen</a></td></tr>";
			}
			echo "</table>";
			echo "<p>Hier einen <a href='insert_artikel_fileUpload.php'>NEUEN ARTIKEL</a>  anlegen.</p>";
		}else{
			echo "Keine passenden Artikel in der Datenbank!";
		}
?>
	
</body>
</html>