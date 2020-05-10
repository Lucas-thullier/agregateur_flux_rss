<?php
try
{
	$bdd = new PDO('mysql:host=localhost;dbname=agregateur;charset=utf8', 'root', '');
	$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());
}

if (isset($_POST['supprimer_source'])) // si on a appuyer sur le bouton supprimer source
{ 
	if (!empty($_POST['nom_source'])) // si il y a bien un checkbox de cocher
	{ 
		foreach ($_POST['nom_source'] as $value) // suppression des lignes de bdd en fonction de l'id
		{     
			$sql = "DELETE FROM `source` WHERE `id`='$value'";
			$test = $bdd->prepare($sql);
			$test ->execute();
		}
		header('Location: Main_agregateur.php'); //redirige vers la page principale après supressions
		exit();
	}	
}else
{
	echo("echec de la suppression");
}?>