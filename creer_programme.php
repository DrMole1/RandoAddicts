<?php

session_start(); // Récupération de la session

// Déclaration d'une variable tampon
$id_utilisateur = -1;

// Ouverture d'une connexion à la BDD. Nom de la BDD : rando_addicts
$PDO = new PDO('mysql:host=localhost;dbname=rando_addicts', 'root', '');

if(isset($_SESSION['id']))
{
  $id_utilisateur = $_SESSION['id'];

}





?>




<!DOCTYPE html>
<html>
<head>
	<title>RandoAddict-CreerProgramme</title>
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


  <h1>Créer un Programme</h1> <br/>

  <!-- JUMBOTRON : PRESENTATION-->
  <div class="container">
    <div class="jumbotron">
        <h2 class="display-4">Options de Guide</h2>
        <p class="lead">Renseignez les propriétés de votre programme :</p>

        <form action="insertion_programme.php" method="post">

          <label for="excursion">Choisir une excursion :</label>
          <select name="excursion" id="excursion">
            <?php
              // Requête pour récupérer tous les id et noms des excursions disponibles et les mettre en options du select
              $pdoStat = $PDO->prepare("SELECT Id_Excursion, Nom FROM excursion;");
              $executeIsOk = $pdoStat->execute();
              $excursions = $pdoStat->fetchAll();
              foreach ($excursions as $excursion => $val) {
                echo "<option value='" . $val['Id_Excursion'] . "'>" . $val['Id_Excursion'] . " - " . $val['Nom'] . "</option>";
              }
            ?>
          </select>
          <br/>

          <?php
            $date_now = new DateTime();
            $date_now = $date_now->format('Y-m-d');
          ?>

          <label for="date_debut">Date de Début :</label>
          <?php echo "<input type='date' id='date_debut' name='date_debut' value='" . $date_now . "' min='" . $date_now . "' max='2024-12-31'>"; ?>

          <br/>

          <label for="date_fin">Date de Fin :</label>
          <?php echo "<input type='date' id='date_fin' name='date_fin' value='" . $date_now . "' min='" . $date_now . "' max='2024-12-31'>"; ?>

          <p><input class="btn btn-success btn-lg" type="submit" name="submit" value="Enregistrer"></p>
        </form>

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