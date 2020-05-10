<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title> Agrégateur </title>
		<meta name="viewport" content="width=device-width"/>
		
		<link rel="stylesheet" type="text/css" href="style.css"/>
		<link rel="stylesheet" type="javascript" href="addSource.js"/>

	</head>

	<body>
		<header></header>
		<div id = "content">
			<nav>
				<div id="add_source">Ajouter une source</div>
				<script src="addSource.js"></script>
				<div id = "sourcesList">Liste des sources:</div>
				<form method = "post" action = "traitement_suppression.php">
				<?php 
				include 'function_agregateur.php'; // CONTROLLER DANS VIEW !!!
				$bdd = initPdo("agregateur");
				$colonneBdd = takeAllColumnOfTable($bdd, 'source');
				displaySource($colonneBdd);  // les valeurs des checkbox correspondent aux 'id' en BDD tandis que les labels correspondent aux 'url' 
				addNewSource($bdd);
				?>
				<br/><input name = "supprimer_source" id = "supprimer_source" type= "submit" value= "Supprimer source(s)"/>

				</form>
			</nav>

			<section>
				<?php //ce php devrait etre dans une autre page je crois
				include 'Actu.php'; // CONTROLLER DANS VIEW !!!
				date_default_timezone_set('Europe/Paris'); // établit le fuseau horaire sinon 1h de d'avance dans les heures de parution sur certaines sources
				$bdd = initPdo("agregateur");
				$columnUrl = takeColumnUrlOfTable($bdd, 'source');
				$listUrlXml = takeXmlWithUrl($columnUrl);
				$ActuObjectList = createActuObjectList($listUrlXml);
				$ActuObjectList = sortByDate($ActuObjectList);
				contenuLengthCut($ActuObjectList);
				displayNewsFeed($ActuObjectList);
				?>
					
			</section>
		</div>
	</body>
</html>