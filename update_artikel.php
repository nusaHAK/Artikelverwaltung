<?php
session_start();
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Kundendaten</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>

    <?php
	if (isset($_GET['speichern'])){
		//Daten in DB speichern
		$artikelnummer = $_GET['artikelnummer'];
		$artikelbezeichnung = $_GET['artikelbezeichnung'];
		$farbe = $_GET['farbe'];
		$groesse = $_GET['groesse'];
		$preis = $_GET['preis'];
		$image = $_GET['image'];
		
		require_once('dbConnection.php');
		
		try {
			//SQL-Stmt vorbereiten
			$statement = $pdo->prepare("UPDATE w_artikel SET artikelbezeichnung = :artikelbezeichnung,
			                            farbe = :farbe, groesse = :groesse, preis = :preis, image = :image
										WHERE artikelnummer = :artikelnummer");
                                              			
			//Variablen einzeln binden
			$statement->bindParam(':artikelbezeichnung',$artikelbezeichnung);
			$statement->bindParam(':farbe',$farbe);
			$statement->bindParam(':groesse',$groesse);
			$statement->bindParam(':preis',$preis);
			$statement->bindParam(':image',$image);
			$statement->bindParam(':artikelnummer',$artikelnummer);
			
			$statement->execute();
			
			
		} catch ( PDOException $e ) {
			die ( "Es ist ein Fehler bei der Verarbeitung der Anfrage aufgetreten!" );
		}
		
		echo "<h3>Die Daten wurden aktualisiert!</h3>";
		echo "<p><a href='uebersichtArtikel.php'>Hier</a> geht es zurück zur Übersicht.</p>";
		
	}elseif (!isset($_GET['speichern']) && isset($_GET['artikelnummer'])){
		$artikelnummer = $_GET['artikelnummer'];
		
		require_once('dbConnection.php');
	
		try {
			//SQL-Stmt vorbereiten
			$statement = $pdo->prepare("SELECT * FROM w_artikel WHERE artikelnummer = :artikelnummer");
			$statement->bindParam(':artikelnummer',$artikelnummer);
			$statement->execute();
		
		} catch ( PDOException $e ) {
			die ( "Es ist ein Fehler beim Laden der Artikeldaten aufgetreten!" );
		}
		
		if($statement->rowCount() > 0){
			//Formular mit den Daten aus dem ResultSet aufbauen
			$row = $statement->fetch()
			?>
			<h1>Details zum Artikel</h1>
			<form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">

				<label for="artikelnummer">Artikelnummer:</label>
				<input id="artikelnummer" type="text"  
							value="<?php echo $row['artikelnummer']; ?>" disabled /></br></br>
							<!-- Vorsicht: disabled-Elemente werden nicht über submit weitergeleitet!!
							               Deshalb muss das Feld auch als hidden-Feld übertragen werden.-->
						<input type="hidden" name="artikelnummer" 
							value="<?php echo $row['artikelnummer']; ?>"  />
				<label for="artikelbezeichnung">Artikelbezeichnung:</label>
				<input id="artikelbezeichnung" type="text" name="artikelbezeichnung" 
				            value="<?php echo $row['artikelbezeichnung']; ?>"required /></br></br>
				<label for="farbe">Farbe:</label>
				<input id="farbe" type="text" name="farbe" 
							value="<?php echo $row['farbe']; ?>" required/></br></br>
				<label for="groesse">Größe:</label>
				<input id="groesse" type="text" name="groesse" 
							value="<?php echo $row['groesse']; ?>" required/></br></br>
				<label for="preis">Preis:</label>
				<input id="preis" type="number" name="preis" 
							value="<?php echo $row['preis']; ?>" required /></br></br>
				
				<label for="image">Image:</label>
				<input id="image" type="file" name="image"
							valu="<?php echo $row['image']; ?>"  /></br></br>
				<input type="submit" value="Änderungen speichern" name="speichern" />

			</form>
	
	<?php

		}else{
			echo "Der Artikel wurde nicht in der Datenbank gefunden!";
		}
	}else {
		echo "Bitte wählen Sie zuerst den <a href='select_artikel.php' />gewünschten Artikel</a> aus.";
	}
	?>
	
</body>

</html>