<?php
function initPdo($bddName)
{
	try
	{
		$bdd = new PDO('mysql:host=localhost;dbname=agregateur;charset=utf8', 'root', '');
		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(Exception $e)
	{
	    die('Erreur : '.$e->getMessage());
	}

	return $bdd;
}
function takeAllColumnOfTable($bdd,$tableName)
{
	$bddColumn = $bdd->query('SELECT * FROM '.$tableName);
	return $bddColumn;
}
function takeColumnUrlOfTable($bdd,$tableName)
{
	$bddColumn = $bdd->query('SELECT url FROM '.$tableName);
	return $bddColumn;
}
function displaySource($bddColumn) // VIEW DANS MODELE !!!
{
	while($data = $bddColumn -> fetch())
	{
		echo "<p class = checkbox_label><input type=checkbox name=nom_source[] id=input_nom_source value =".$data['id']." /> <label class =label_nom_source for=nom_source>".$data['url']."</label></p>"; // la valeur du label est l'url mais la valeur que renverra le cochage de la checkbox est l'id dans bdd
	}
}
function addNewSource($bdd) // CONTROLLER DANS MODELE !!
{

	if (isset($_POST["newSource"]))
	{
		$urlNewSource = $_POST["newSource"];
		if ($urlNewSource != "null") //condition rajouter car parfois des "null" se glisse dans la bdd
		{
			if (preg_match("#(^http|^https)#", $urlNewSource)) 
			{
				$req = $bdd->prepare("INSERT INTO source(URL) VALUES (:newSource)");
				$req ->execute(array(
				'newSource' => ltrim($urlNewSource))); //ltrim supprime l'espace intempestif quand ajout dans bdd
			}
		}
	}
}
function takeXmlWithUrl($bddColumn) // CONTROLLER DANS MODELE!!!
{
	$urlXml = array();
		while($data = $bddColumn->fetch())
		{
			if(!empty($data['url']))
			{
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $data['url'] );
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // fais en sorte que la valeur de retour de curl_exec soit une chaine de caractère plutot quelle soit affiché directement dans le navigateur
					if (preg_match("#^https#", $data['url']))
					{
						curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
						curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,  2);
					}
					$content = curl_exec($curl);
					curl_close($curl);
				$xml = new simpleXMlElement($content);
				$urlXml[$data['url']] = $xml;
			}
		}
	return $urlXml;
}
function createActuObjectList($listUrlXml)
{
	$ActuObjectList = array();
		foreach ($listUrlXml as $key => $value) 
		{ 
			foreach ($value -> channel -> item as $v) 
			{
				$ActuObject = new Actu;
				$ActuObject -> addSource($value -> channel -> title);
				$ActuObject -> addContent($v -> description);
				$ActuObject -> addHour($v -> pubDate);
				$ActuObject -> addUrl($v -> link);
				$ActuObject -> addNewsTitle($v -> title);
				$ActuObjectList[] = $ActuObject;
			}
			
		}
	return $ActuObjectList;
}
function sortByDate($ActuObjectList)
{
	function storey_sort($timestamp_a, $timestamp_b)
	{ 
		return strtotime($timestamp_a -> postHour()) - strtotime( $timestamp_b -> postHour()); //reste pb que certaines sources ont un +0100 et dans le heure_publication du coup tri faussé
	}
	usort($ActuObjectList, "storey_sort");
	$ActuObjectList = array_reverse($ActuObjectList);
	return $ActuObjectList;
}

function contenuLengthCut($ActuObjectList)
{
	$length = 500;
	foreach ($ActuObjectList as $v)
	{
		if(strlen($v -> content() < $length)) // WTFFFFFFF le inférieur à est considéré comme supp à 
		{
			$shortContent = substr($v -> content(),0,$length).'<span class=suspensionContenu> [...]</span>';
			$v -> addContent($shortContent);
		}
	}
}
function displayNewsFeed($ActuObjectList) // VIEW DANS MODELE !!!
{
	foreach ($ActuObjectList as $v) 
	{
		
		$displayNewsTitle ="<a class = element 1 href=".$v->url().">".$v -> title()."</a>"; // traitement de <ul> sur deux variable pas ouf je pense
		$displaySource = "<span class = element 2>-".$v -> source()."-</span>";
		$displayHour = "<span class = element 3>".date("H:i \\l\\e j/n", strtotime($v -> postHour()))."</span>";
		echo("<div class= ticket><span id = titleSourceHour> $displayNewsTitle $displaySource $displayHour</span> <p id=contenu>".$v -> content()."</p></div>");
	}
}
?>

