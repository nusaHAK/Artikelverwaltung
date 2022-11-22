<!DOCTYPE>
<html lang="de">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Artikel löschen</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>

    <?php
	if(isset($_GET['artikelnummer'])){
		
		$artikelnummer = $_GET['artikelnummer'];
		
		require_once('dbConnection.php');
		
		try {
			//SQL-Stmt vorbereiten
			$statement = $pdo->prepare("SELECT * FROM w_artikel WHERE artikelnummer = :artikelnummer");
                                              			
			//Variablen einzeln binden
			$statement->bindParam(':artikelnummer',$artikelnummer);
			$statement->execute();
						
		} catch ( PDOException $e ) {
			die ( "Es ist ein Fehler bei der Verarbeitung der Anfrage aufgetreten!" );
		}
		
		//ResultSet verwenden, um Artikeldaten auszugeben
		$row = $statement->fetch()

	?>	<h2>Ausgewählter Artikel</h2>
		<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<table border=1>
			<th>Artikelnummer</th>
			<th>Artikelbezeichnung</th>
			<th>Farbe</th>
			<th>Größe</th>
			<th>Preis</th>
			<th>Abbildung</th>
	<?php
			echo "<tr><td>". $row['artikelnummer'] . "</td>";
			echo "<td>". $row['artikelbezeichnung'] . "</td>";
			echo "<td>". $row['farbe'] . "</td>";
			echo "<td>". $row['groesse'] . "</td>";
			echo "<td>". $row['preis'] . "</td>";
			echo "<td><img src='". $row['image'] . "'" . ' alt="keine Abbildung" width="100" height="100"></td></tr>';
			echo "</table>";
			echo "<h3>Sind Sie sicher, dass Sie diesen Artikel löschen wollen?</h3>";
	?>
			<input type="hidden" name="artikelnummer" value="<?php echo $artikelnummer; ?>">
			<input type="submit" name="ja" value="JA">
			<input type="submit" name="nein" value="NEIN">
		
		</form>
	<?php
	
	}elseif (isset($_POST['ja'])){
		$artikelnummer = $_POST['artikelnummer'];
		
		require_once('dbConnection.php');
		
		try {
			//SQL-Stmt vorbereiten
			$statement = $pdo->prepare("DELETE FROM w_artikel WHERE artikelnummer = :artikelnummer");
			$statement->bindParam(':artikelnummer',$artikelnummer);
			$statement->execute();
		} catch ( PDOException $e ) {
			die ( "Es ist ein Fehler bei der Verarbeitung der Anfrage aufgetreten!" );
		}
		
		echo "<h2>Artikel wurde gelöscht!</h2>";
		echo "<p><a href='index.php'>Hier</a> geht es zurück zur Übersicht.</p>";
	
	}elseif (isset($_POST['nein'])){
		echo "<h2>Der Löschvorgang wurde abgebrochen!</h2>";
		echo "<p><a href='index.php'>Hier</a> geht es zurück zur Übersicht.</p>";
	}else {
		echo "Bitte wählen Sie zuerst den <a href='index.php' />gewünschten Artikel</a> aus.";
	}
	?>
	
</body>

</html>