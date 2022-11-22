<!DOCTYPE html>
<html lang="de">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Suchfunktion</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>


<h2>Artikelsuche</h2>
<p>Geben Sie den gesuchten Artikel ein</p>

<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
 <!-- formular für die Suche erstellt-->

<label for="gesuchter_artikel"><strong>Artikel:</strong></label>
<input type="text" id="artikel" name="gesuchter_artikel" required />
<input type="submit" value="Suchen" name="submit">
</form>

<?php

$suchbegriff = "";

if(isset($_POST['submit'])){// Geprüft ob auf den Button geklickt wurde, wenn ja wird in SESSION Variable gespeichert
	$suchbegriff=$_POST['gesuchter_artikel'];
	
	if (!empty(trim($suchbegriff))){
	
		require_once('dbConnection.php');
		try {
				//SQL-Stmt vorbereiten
				$statement = $pdo->prepare("SELECT * FROM w_artikel 
											WHERE artikelbezeichnung LIKE :begriff");
				$suche = "%$suchbegriff%";
				$statement->bindParam(":begriff",$suche);
				$statement->execute();
			
		} catch ( PDOException $e ) {
			die ( "Es ist ein Fehler bei der Verarbeitung der Anfrage aufgetreten!" );
		}
			
		echo"<h2>Suchergebnisse</h2>";
		echo "<p>Wir haben folgende Artikel in der Datenbank gefunden:</p>";

		echo "<ul>";

		if($statement->rowCount() > 0){
			while($row = $statement->fetch()) {
				echo "<li>
				$row[artikelnummer] 
				$row[artikelbezeichnung]
				$row[preis] Euro
				</li>\n";
			}
		}else{
			echo "Keine passenden Artikel in der Datenbank!";
		}

		echo"</ul>";
	}else{
		echo "Sie haben keinen Suchbegriff eingegeben!";
	}

}







?>



</body>