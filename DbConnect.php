
<?php

// Définit la connection à la base de données, retourne la connection.

// Include des autres fichiers .php
include_once("Constantes.php"); // Inclut une seule fois le fichier Constantes.php
include_once("DbOperations.php"); // Inclut une seule fois le fichier DbOperations.php
include_once("Client.php"); // Inclut une seule fois le fichier Client.php

// Bloc Try-Catch pour relever les erreurs en cas d'échec à la connexion
try
{
	// Connexion à la DB avec les constantes de Constantes.php
	$conn = mysqli_connect($url, $user, $mdp, $db);
	$conn_post = new mysqli($url, $user, $mdp, $db);
}
catch(Exception $e)
{
	var_dump($e->getMessage());  // Envoie du message d'erreur
}


?>

