<?php

// Déclaration de variable
$nb_occurence[0] = -1;

// Récupération de l'email et du mdp via la méthode POST (action sur la même page, cyclique (voir le formulaire plus bas))
if(isset($_POST['submit']))
{
  $email = $_POST['email'];
  $mdp = $_POST['mdp'];

  // Ouverture d'une connexion à la BDD. Nom de la BDD : rando_addicts
  $PDO = new PDO('mysql:host=localhost;dbname=rando_addicts', 'root', '');
  // Requête préparée pour trouver s'il existe une occurence d'un compte présent dans la BDD ayant pour même email et même mdp
  $pdoStat = $PDO->prepare("SELECT COUNT(*) FROM compte WHERE Email = :email AND Mot_de_passe = :mdp ;");
  // Lier chaque marqueur à une valeur
  // bindValue(marqueur, valeur, type)
  $pdoStat->bindValue(':email', $email, PDO::PARAM_STR);
  $pdoStat->bindValue(':mdp', $mdp, PDO::PARAM_STR);
  // Exécution de la requête préparée
  $executeIsOk = $pdoStat->execute();
  // Récupération des résultats. 0 = pas de compte trouvé, 1 = compte existant
  $nb_occurence = $pdoStat->fetch();

  // Si le compte existe, on ouvre la session
  if($nb_occurence[0] == 1)
  {
    // On récupère l'ID du compte
    $pdoStat = $PDO->prepare("SELECT Id_Compte FROM compte WHERE Email = :email AND Mot_de_passe = :mdp ;");
    $pdoStat->bindValue(':email', $email, PDO::PARAM_STR);
    $pdoStat->bindValue(':mdp', $mdp, PDO::PARAM_STR);
    $executeIsOk = $pdoStat->execute();
    $id_compte = $pdoStat->fetch();

    // On débute la session avec l'id du compte
    session_start();
    $_SESSION["id"]=$id_compte[0];

    // On renseigne la date de connexion de l'utilisateur comme dernière date de connexion (avec un UPDATE de la table COMPTE)
    $pdoStat = $PDO->prepare("UPDATE compte SET Date_derniere_connexion=:date_derniere_connexion WHERE Id_Compte = :id_session;");
    $date_derniere_connexion = new DateTime();
    $date_derniere_connexion = $date_derniere_connexion->format('Y-m-d');
    $pdoStat->bindValue(':date_derniere_connexion', $date_derniere_connexion);
    $pdoStat->bindValue(':id_session', $id_compte[0], PDO::PARAM_INT);
    $executeIsOk = $pdoStat->execute();

    // Redirection vers l'index
    header('Location: index.php');
    exit();
  }
}





?>




<!DOCTYPE html>
<html>
<head>
	<title>RandoAddict-Connexion</title>
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


  <h1>Se Connecter</h1> <br/>


  <!-- JUMBOTRON : PRESENTATION-->
  <div class="container">
    <div class="jumbotron">
        <h2 class="display-4">Connexion</h2>
        <p class="lead">Veuillez rentrer vos identifiants.</p>

        <?php
        // On avertit l'utilisateur si un champs est vide ou incorrect
          if(!is_null($nb_occurence[0]))
          {
            if($nb_occurence[0] == 0)
            {
              echo "<p class='text-danger'> Champs vides ou incorrects </p>";
            }
          }
        ?>

        <div>  
          <form method="post" action="connexion.php"> <!-- Formulaire en méthode POST-->

            <label for="email">Email</label>
            <input type="text" name="email" id="email"> </br>

            <label for="mdp">Mot de passe</label>
            <input type="password" name="mdp" id="mdp"> </br>

            <p><input class="btn btn-success btn-lg" type="submit" name="submit" value="Se Connecter"></p>
          </form>
        </div>

        <hr class="my-4">

        <p>Vous ne possédez pas encore de compte ?</p>
        <button class="btn btn-dark btn-lg mb-2" type="button" data-toggle="collapse" data-target="#collapseExample01" aria-expanded="false" aria-controls="collapseExample">
          Créer un nouveau compte
        </button> <br/>

        <div class="collapse" id="collapseExample01">
          <h5>Créer un compte</h5>
          <form action="insertion.php" method="post">

          <p>
            <label for="nom">Nom</label>
            <input id="nom" type="text" name="nom">
          </p>

          <p>
            <label for="prenom">Prenom</label>
            <input id="prenom" type="text" name="prenom">
          </p>

          <p>
            <label for="age">Age</label>
            <input id="age" type="text" name="age">
          </p>

          <p>
            <label for="tel">Téléphone</label>
            <input id="tel" type="text" name="tel">
          </p>

          <p>
            <label for="email">Adresse Electronique</label>
            <input id="email" type="email" name="email">
          </p>

          <p>
            <label for="mdp">Mot de Passe</label>
            <input id="mdp" type="password" name="mdp">
          </p>

          <p>
            <label for="mdp2">Vérification Mot de Passe</label>
            <input id="mdp2" type="password" name="mdp2">
          </p>

          <p><input class="btn btn-success btn-lg" type="submit" name="submit" value="Enregistrer"></p>
    
          </form>
        </div>

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