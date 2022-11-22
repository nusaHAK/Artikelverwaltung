<?php
require_once('./Upload.class.php');
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Artikel bearbeiten</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>

    <?php
	if (isset($_POST['speichern'])){
		//Daten in DB speichern
		$artikelnummer = $_POST['artikelnummer'];
		$artikelbezeichnung = $_POST['artikelbezeichnung'];
		$farbe = $_POST['farbe'];
		$groesse = $_POST['groesse'];
		$preis = $_POST['preis'];
		$bild_alt = $_POST['bild_alt'];
		$bild_falsch = false;
		
						
				//Bei der Übertragung des Images ist kein Fehler aufgetreten
				if (isset($_FILES['bild_neu']) && $_FILES['bild_neu']['error'] == 0){
					$bilddatei = new Upload($_FILES['bild_neu']['name'],
											$_FILES['bild_neu']['tmp_name'],
											$_FILES['bild_neu']['type'],
											$_FILES['bild_neu']['size']);
										
					if ($bilddatei->checkImage()){ //prüfe, ob Dateityp und -größe stimmen
					
						// Die hochgeladene Datei in das Bildverzeichnis verschieben
						if ($bilddatei->doFileUpload()){
							//upload erfolgreich
							// Neu Bilddatei wurde erfolgreich hochgeladen, also alte löschen
							if (!empty($bild_alt) && ($bild_alt != $bilddatei->getFilename())) {
							  @unlink($bild_alt);
							  echo "Das alte Bild ". $bild_alt . " wurde gelöscht!";
							}
						}else {
							// Neues Bild konnte nicht verschoben werden, also temporäre Datei löschen und Fehler-Flag setzen
							@unlink($bilddatei->getTmpName());
							$bild_falsch = true;  //Flag setzen, damit Bildinfo nicht in die DB gespeichert wird.
							echo "Beim Upload der Bilddatei ist ein Fehler aufgetreten. Das Bild wurde nicht gespeichert.";
						}
					}else{
						//Das Bild entspricht nicht den Vorgaben - also aus dem tmp-Ordner löschen
						@unlink($bilddatei->getTmpName());
						$bild_falsch = true; //Flag setzen, damit Bildinfo nicht in die DB gespeichert wird.
						echo "Die Datei entspricht nicht den Vorgaben für Dateityp und -größe!";
					}
				}else{
					echo "Es wurde keine Abbildung zum Artikel gespeichert!";
					echo "<br>Das alte Bild " . $bild_alt . " wurde gelöscht.";
				}
			
				//jetzt kann die DB aktualisiert werden
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
					if ($_FILES['bild_neu']['error'] != 0 || $bild_falsch){
						$bild = "";  //Bildname soll nicht gespeichert werden
						@unlink($bild_alt);
					}else {
						//Bildname mit Pfad holen und speichern
						$bild = $bilddatei->getUploadFilename();
					}
					$statement->bindParam(':image',$bild);
					$statement->bindParam(':artikelnummer',$artikelnummer);
						
					$statement->execute();
											
				} catch ( PDOException $e ) {
					die ( "Es ist ein Fehler bei der Verarbeitung der Anfrage aufgetreten!" );
				}
					
				echo "<h3>Die Daten wurden aktualisiert!</h3>";
				echo "<p><a href='index.php'>Hier</a> geht es zurück zur Übersicht.</p>";

		
		
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
			<form enctype="multipart/form-data" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

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
				
				<label for="bild_neu">Image:</label><br>
				<img src="<?php echo $row['image']; ?>" alt="Leider kein Bild verfügbar." width="100" height="100"><br><br>
				<label for="bild_neu">Neues Image hochladen:</label><br>
				<input id="bild_neu" type="file" name="bild_neu"
							value="<?php echo $row['image']; ?>"   /></br></br>
				<input type="hidden" name="bild_alt" value="<?php echo $row['image']; ?>" />
				<input type="submit" value="Änderungen speichern" name="speichern" />

			</form>
	<?php

		}else{
			echo "Der Artikel wurde nicht in der Datenbank gefunden!";
		}
	}else {
		echo "Bitte wählen Sie zuerst den <a href='index.php' />gewünschten Artikel</a> aus.";
	}
	?>
	
</body>
</html>