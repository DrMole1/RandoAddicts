<?php

// On débute la session avec l'id du compte
session_start();
$id_utilisateur = $_SESSION['id'];
$id_programme = $_SESSION['programme_a_reserver'];

// Déclaration d'une variable message
$message = '';

// Ouverture d'une connexion à la BDD. Nom de la BDD : rando_addicts
$PDO = new PDO('mysql:host=localhost;dbname=rando_addicts', 'root', '');

// On regarde si le même utilisateur n'a pas déjà fait la même réservation
$pdoStat = $PDO->prepare('SELECT COUNT(*) FROM reservation WHERE reservation.Id_Compte = :id_compte AND reservation.Id_Programme = :id_programme;');
// Lier chaque marqueur à une valeur
$pdoStat->bindValue(':id_compte', $id_utilisateur, PDO::PARAM_INT);
$pdoStat->bindValue(':id_programme', $id_programme, PDO::PARAM_INT);
// Exécution de la requête préparée
$insertIsOk = $pdoStat->execute();
$isExist = $pdoStat->fetch();

if($isExist[0]) // La réservation existe déjà
{
  $message = 'Erreur ! Votre réservation existe déjà pour ce programme.';
}
else        // La réservation n'existe pas
{
  // Insertion des valeurs dans la table des réservations
  $pdoStat = $PDO->prepare('INSERT INTO reservation VALUES (:id_compte, :id_programme);');
  // Lier chaque marqueur à une valeur
  $pdoStat->bindValue(':id_compte', $id_utilisateur, PDO::PARAM_INT);
  $pdoStat->bindValue(':id_programme', $id_programme, PDO::PARAM_INT);
  // Exécution de la requête préparée
  $insertIsOk = $pdoStat->execute();

  $message = 'Vous avez réservé votre excursion avec succès !';
}



?>





<!DOCTYPE html>
<html>
<head>
	<title>RandoAddict-Reservation</title>
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
            <a href="rando_addict.html" class="btn btn-dark btn-lg btn-block" >RANDO ADDICT</a> <!-- Lien vers la page "Rando_Addict.html" -->
          </div>
        </div>
      </div>
    </div>
  </div>
  <br/>


  <h1>Réservation</h1> <br/>

  <!-- JUMBOTRON : PRESENTATION-->
  <div class="container">
    <div class="jumbotron">
        <h2 class="display-4">Réservation</h2>
        <p class="lead"> <?= $message ?> </p>
        <hr class="my-4">
        <p>Retour à la page principale :</p>
        <p class="lead">
          <a href="index.php" class="btn btn-dark btn-lg btn-block" >RETOUR</a> <!-- Lien vers la page "Index.html" -->
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