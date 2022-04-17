<?php 


// On débute la session avec l'id du compte
session_start();
$id_utilisateur = $_SESSION['id'];

// Ouverture d'une connexion à la BDD
$PDO = new PDO('mysql:host=localhost;dbname=rando_addicts', 'root', '');
// Déclaration de la variable $message
$message = '';

// Récupération des informations
if(isset($_POST['submit']))
{
	$excursion = $_POST['excursion'];
	$date_debut = $_POST['date_debut'];
	$date_fin = $_POST['date_fin'];


  	// Préparation de la requête d'insertion (SQL)
	$pdoStat = $PDO->prepare("INSERT INTO programme VALUES (NULL, ' ', :date_debut, :date_fin, :id_excursion, :id_guide)");

	// Lier chaque marqueur à une valeur
	$pdoStat->bindValue(':date_debut', $date_debut);
	$pdoStat->bindValue(':date_fin', $date_fin);
	$pdoStat->bindValue(':id_excursion', $excursion, PDO::PARAM_INT);
	$pdoStat->bindValue(':id_guide', $id_utilisateur, PDO::PARAM_INT);

	// Exécution de la requête préparée
	$insertIsOk = $pdoStat->execute();

	// Vérification de l'exécution de la requête
	if($insertIsOk)
	{
		$message = "Votre programme a été créé !";
	}
	else
	{
		$message = "Une erreur est survenue durant la création de votre programme.";
	}
}



?>


<!DOCTYPE html>
<html>
<head>
	<title>RandoAddict-InsertionProgramme</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href=https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css>
	<link rel="stylesheet" type="text/css" href="RandoAddictStyle.css"/>
	<script src=https://code.jquery.com/jquery-3.3.1.slim.min.js></script>
	<script src=https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js></script>
	<script src=https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js></script>
</head>
<body>

	<!-- HEADBAND : NAVIGATION-->
	<!-- Utilisation d'un bandeau pour que l'utilisateur puisse se diriger rapidement là où il souhaite sur le site -->
	<div class="container-fluid" id="headband">
    <div class="row border border-dark">
      <div class="col-2 bg-success p-5">
        <p id="title_RandoAddict"> RandoAddict</p>
      </div>
      <div class="col bg-success p-4">
        <img src="img/LogoRA.png" loading="lazy" alt="logoRA">
      </div>
      <div class="col-8 bg-success">
        <div class="row justify-content-around bg-success p-5">
          <div class="col">
            <a href="boutique.html" class="btn btn-dark btn-lg btn-block" >BOUTIQUE</a> <!-- Lien vers la page "Boutique.html" -->
          </div>
          <div class="col">
            <a href="formations.html" class="btn btn-dark btn-lg btn-block" >FORMATIONS</a> <!-- Lien vers la page "Formations.html" -->
          </div>
          <div class="col">
            <a href="avis.html" class="btn btn-dark btn-lg btn-block" >AVIS</a> <!-- Lien vers la page "Avis.html" -->
          </div>
          <div class="col">
            <?php
				if($id_utilisateur != -1)         // Si l'utilisateur est connecté, il peut se déconnecter
				{
					echo "<a href='deconnexion.php' class='btn btn-info btn-lg btn-block' >DECONNEXION</a>";
				}
				else                              // Si l'utilisateur est déconnecté, il peut se connecter
				{
					echo "<a href='connexion.php' class='btn btn-info btn-lg btn-block' >SE CONNECTER</a>";
				}
			?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <br/>


  <h1>Insertion de Programme</h1> <br/>


  <!-- JUMBOTRON : PRESENTATION-->
  <div class="container">
    <div class="jumbotron">
        <h2 class="display-4">Résultat</h2>
        <p class="lead"><?= $message ?></p>
        <hr class="my-4">
        <p>Retour à la page de connexion :</p>
        <p class="lead">
          <a href="index.php" class="btn btn-dark btn-lg btn-block" >RETOUR</a> <!-- Lien vers la page "index.html" -->
        </p>
    </div>
  </div>
  <br/>


	<!-- CARD FOOTER : FOOTER-->
	<!-- Informations générales -->
	<div class="card-fluid">
  		<div class="card-header bg-secondary text-white">
    		Nous contacter : 06 29 27 60 20
  		</div>
  		<div class="card-body bg-dark text-white">
    		<h5 class="card-title">Suivez-nous sur Twitter, Facebook, Instagram, LinkedIn. </h5>
    		<a href="#" class="btn btn-success">Plus d'infos</a> <!-- Lien vers la page "RandoAddict.html" -->
    		<p class="card-text">CONDITIONS DE VENTE ET ASSURANCES , MENTIONS LÉGALES ET CGU & CONFIDENTIALITÉ ET COOKIES © TOUS DROITS RÉSERVÉS RANDO ADDICT</p>
  		</div>
	</div>

</body>
</html>