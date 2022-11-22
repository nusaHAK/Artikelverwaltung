<!DOCTYPE html>
<html lang="de">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Artikel hinzufügen</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>

    <?php
	
	require_once('Upload.class.php');
	
	$artikelnummer = "";
	$artikelbezeichnung ="";
	$farbe = "";
	$groesse = "";
	$preis = 0;
	$image = "";
	
	if (isset($_POST['speichern'])){
		//Daten in DB speichern
		$artikelbezeichnung = $_POST['artikelbezeichnung'];
		$farbe = $_POST['farbe'];
		$groesse = $_POST['groesse'];
		$preis = $_POST['preis'];
		
		$uploadOK = false;
		
		//Zuerst Fileupload versuchen, wenn dieser gelingt, dann erst Artikel in der DB speichern//Bei der Übertragung des Images ist kein Fehler aufgetreten
				if ($_FILES['bild']['error'] == 0){
					$bilddatei = new Upload($_FILES['bild']['name'],
											$_FILES['bild']['tmp_name'],
											$_FILES['bild']['type'],
											$_FILES['bild']['size']);
										
					if ($bilddatei->checkImage()){ //prüfe, ob Dateityp und -größe stimmen
					
						// Die hochgeladene Datei in das Bildverzeichnis verschieben
						if ($bilddatei->doFileUpload()){
							//upload erfolgreich
							echo "Das Bild wurde erfolgreich hochgeladen!";
							$uploadOK = true; 
						}else {
							// Neues Bild konnte nicht verschoben werden, also temporäre Datei löschen und Fehler-Flag setzen
							@unlink($bilddatei->getTmpName());
							echo "Beim Upload der Bilddatei ist ein Fehler aufgetreten. Das Bild wurde nicht gespeichert.";
						}
					}else{
						//Das Bild entspricht nicht den Vorgaben - also aus dem tmp-Ordner löschen
						@unlink($bilddatei->getTmpName());
						echo "Die Datei entspricht nicht den Vorgaben für Dateityp und -größe!";
					}
				}else{
					echo "Es ist ein Fehler beim Upload der Datei entstanden!";
				}
			
				if ($uploadOK){
					require_once('dbConnection.php');
					//echo "INSERT INTO w_artikel (artikelbezeichnung,farbe,groesse,preis,image)
					//								VALUES ('$artikelbezeichnung', '$farbe', '$groesse', $preis, '$image')";
					try {
						//SQL-Stmt vorbereiten (Vorteil von prepare: auf quotes im Statement kann verzichtet werden!!!)
						$statement = $pdo->prepare("INSERT INTO w_artikel (artikelnummer,artikelbezeichnung,farbe,groesse,preis,image)
													VALUES (NULL, :artikelbezeichnung, :farbe, :groesse, :preis, :image)");
																	
						//Variablen einzeln binden
						$statement->bindParam(':artikelbezeichnung',$artikelbezeichnung);
						$statement->bindParam(':farbe',$farbe);
						$statement->bindParam(':groesse',$groesse);
						$statement->bindParam(':preis',$preis);
						//den Namen des Bild-Objektes holen
						$image = $bilddatei->getUploadFilename();
						$statement->bindParam(':image',$image);
						
						$statement->execute();
						
						
					} catch ( PDOException $e ) {
						echo $e->getMessage();
						die ( "Es ist ein Fehler beim Einfügen des Datensatzes in die Datenbank aufgetreten!" );
					}
		
					echo "<h3>Der neue Artikel wurde hinzugefügt!</h3>";
					echo "<p><a href='index.php'>Hier</a> geht es zurück zur Übersicht.</p>";
				}else{
					echo "<h2>Das Hinzufügen des Artikels war leider nicht möglich.</h2>";
				}
	}else {
			
	?>
			<h1>Hinzufügen eines neuen Artikels</h1>
			<form enctype="multipart/form-data" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

				<label for="artikelbezeichnung">Artikelbezeichnung:</label>
				<input id="artikelbezeichnung" type="text" name="artikelbezeichnung" 
				            value="<?php echo ($artikelbezeichnung != "") ? $artikelbezeichnung : ""; ?>"required /></br></br>
				<label for="farbe">Farbe:</label>
				<input id="farbe" type="text" name="farbe" 
							value="<?php echo ($farbe != "") ? $farbe : ""; ?>" required/></br></br>
				<label for="groesse">Größe:</label>
				<input id="groesse" type="text" name="groesse" 
							value="<?php echo ($groesse != "") ? $groesse : ""; ?>" required/></br></br>
				<label for="preis">Preis:</label>
				<input id="preis" type="number" name="preis" 
							value="<?php echo($preis != "") ? $preis : ""; ?>" required /></br></br>
				
				<label for="image">Image:</label>
				<input id="image" type="file" name="bild"
							value="<?php echo($image != "") ? $image : ""; ?>"  /></br></br>
				<input type="submit" value="neuen Artikel speichern" name="speichern" />


			</form>
	
	<?php
	}
	?>
	
</body>

</html>