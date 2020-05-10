var addSourceButton = document.getElementById("add_source")

add_source.onclick = function test()
{
	var newSource = prompt("Entrez l'URL du flux RSS à ajouter :");
	if(/(https|http)/.test(newSource))
	{
		var xhr = new XMLHttpRequest();
		xhr.open("POST", "Main_agregateur.php", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send("newSource="+newSource);
		document.location.href="Main_agregateur.php"; // redirige vers la page principale après ajout de source
	}
	else if (newSource !== null)
	{
		alert('L\'URL a été mal renseignée. Veuillez recommencer.');
	}
}