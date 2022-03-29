
<?php

// Classe contenant les différentes requêtes liées au CRUD.

// Include des autres fichiers .php
include_once("Constantes.php"); // Inclut une seule fois le fichier Constantes.php
include_once("DbConnect.php"); // Inclut une seule fois le fichier DbConnect.php
include_once("Client.php"); // Inclut une seule fois le fichier Client.php

// On inclut la connexion à la base
require_once('DbConnect.php');

// Affectation de la méthode 
$method = $_SERVER['REQUEST_METHOD'];


if($method == "POST" && $_POST['METHOD'] == "create") // ===================================== CREATE =====================================
{
	// Récupération des valeurs du formulaire (méthode POST) et affectation aux variables
	$ncli = $_POST['NCLI'];
	$nom = $_POST['NOM'];
	$prenom = $_POST['PRENOM'];
	$age = $_POST['AGE'];
	$numero = $_POST['NUMERO'];

	// Insertion des valeurs dans la db
	$sql = "INSERT INTO marcheur VALUES ('".$ncli."','".$nom."','".$prenom."','".$age."','".$numero."')";


	// Récupération du programme
	$idprogram = $_POST['ID_Program'];

	// Insertion des valeurs dans la db
	$sql2 = "INSERT INTO reservation VALUES ('" . $ncli . "','" . $idprogram."')";

	if($conn_post->query($sql) === true)
	{

	}
	else
	{
		var_dump("error" . mysqli_error($conn));
	}

	if($conn_post->query($sql2) === true)
	{

	}
	else
	{
		var_dump("error" . mysqli_error($conn));
	}

	// On récupère le contenu du fichier
    $texte = file_get_contents('journal.txt');

    // On récupère la date et l'heure courante
    $horaire = date('Y-m-d H:i:s');
            
    // On ajoute notre nouveau texte à l'ancien
    $texte .= "\n" . $horaire . " : insertion marcheur " . $ncli;

    // On ajoute notre nouveau texte à l'ancien
    $texte .= "\n" . $horaire . " : insertion reservation " . $idprogram;
            
    // On écrit tout le texte dans notre fichier
    file_put_contents('journal.txt', $texte);

	// Fermeture de la connexion
	$conn_post->close();
}

?>