<?php

// On débute la session avec l'id du compte
session_start();
// Déclaration d'une variable tampon
$id_utilisateur = -1;
$message_deconnecte = 0;
$isGuide = 0;
// Ouverture d'une connexion à la BDD. Nom de la BDD : rando_addicts
$PDO = new PDO('mysql:host=localhost;dbname=rando_addicts', 'root', '');

if(isset($_SESSION['id']))
{
  $id_utilisateur = $_SESSION['id'];

  // Requête pour savoir si l'utilisateur possède des droits de Guide
  $pdoStat = $PDO->prepare("SELECT COUNT(*) FROM compte_role WHERE Id_Compte = :id_compte AND Id_Role = 2;");
  $pdoStat->bindValue(':id_compte', $id_utilisateur, PDO::PARAM_INT);
  $executeIsOk = $pdoStat->execute();
  $resultGuide = $pdoStat->fetch();

  if($resultGuide[0] == 1)
  {
    $isGuide = 1;
  }
}

if(isset($_POST['submit']))
{
  $nb_programme = $_POST['ID_Program'];
  
  if($id_utilisateur == -1)   // Envoie d'un message pour signaler que l'utilisateur n'est pas connecté
  {
    $message_deconnecte = 1;
  }
  else                        // Redirection vers la page réservation
  {
    $_SESSION['programme_a_reserver']=$nb_programme;
    // Redirection vers page réservation
    header('Location: reservation.php');
    exit();
  }
}

// Requête préparée pour trouver toutes les valeurs de la BDD issue de la table excursion
$pdoStat = $PDO->prepare("SELECT * FROM excursion");
// Exécution de la requête préparée
$executeIsOk = $pdoStat->execute();
// Récupération des résultats
$excursions = $pdoStat->fetchAll();


// Récupération des lieux de début via une jointure
$pdoStat = $PDO->prepare("SELECT ville, id_excursion FROM excursion INNER JOIN lieu ON excursion.Lieu_Debut = lieu.Id_Lieu ORDER BY id_excursion;"); // On souhaite la ville de départ pour chaque excursion
$executeIsOk = $pdoStat->execute();
$lieu_debut = $pdoStat->fetchAll();


?>





<!DOCTYPE html>
<html>
<head>
	<title>RandoAddict-Excursions</title>
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

  <?php
  // On avertit l'utilisateur qu'il n'est pas connecté
  if($message_deconnecte == 1)
    {
      echo "<p class='text-danger'> Connectez-vous avec de réserver. </p>";
    }
  ?>

  <?php
  // Si l'utilisateur a des droits de Guide, il peut créer un programme
  if($isGuide == 1)
    {
      echo "<a href='creer_programme.php' class='btn btn-dark btn-lg btn-block' > CREER UN PROGRAMME </a>";
    }
  ?>
	

	<h1 id="nos_excursions">Nos Excursions</h1>	<br/>
	<h3>Votre voyage selon vos envies !</h3> <br/>

  
  <?php

    foreach ($excursions as $excursion => $val) {

      echo "<div class='container'>";
        echo "<div class='row'>";
          echo "<div class='card mt-4' style='width: 70rem;'>";
            echo "<h1 class='card-title'><strong>" . $val['Nom'] . "</strong></h1>";
            echo "<div class='row'>";
              echo "<div class='col-4'>";
                echo "<img class='card-img-top m-4' src='img/Excu0" . ($excursion + 1) . ".png' loading='lazy' alt='Img_Card' style='width: 16rem;'>";
              echo "</div>";
              echo "<div class='col-4'>";
                echo "<img class='card-img-top m-4' src='img/Excu0" . ($excursion + 1) . "-B.png' loading='lazy' alt='Img_Card' style='width: 16rem;'>";
              echo "</div>";
              echo "<div class='col-4'>";
                echo "<img class='card-img-top m-4' src='img/Excu0" . ($excursion + 1) . "-C.png' loading='lazy' alt='Img_Card' style='width: 16rem;'>";
              echo "</div>";
            echo "</div>";
            echo "<p class='card-text m-2'>" . $val['Description'] . "</p>";
            echo "<div class='card-body'>";
              echo "<ul class='list-group list-group-flush'>";
                echo "<li class='list-group-item'>";
                  echo "<div class='row'>";
                    echo "<div class='col-2'>";
                      echo "<img src='img/LogoDifficulte.png' alt='difficulte'>";
                    echo "</div>";
                    echo "<div class='col-2'>";
                      // Gestion de la couleur Bootstrap de la difficulté selon cette dernière
                      if($val['Difficulte'] <= 3) {
                        echo "<p class='difficulty text-success'>" . $val['Difficulte'] . "/ 10 </p>";
                      } else if ($val['Difficulte'] <= 6) {
                        echo "<p class='difficulty text-warning'>" . $val['Difficulte'] . "/ 10 </p>";
                      } else {
                        echo "<p class='difficulty text-danger'>" . $val['Difficulte'] . "/ 10 </p>";
                      }
                    echo "</div>";
                    echo "<div class='col-2'>";
                      echo "<img src='img/LogoLieu.png' alt='lieu'>";
                    echo "</div>";
                    echo "<div class='col-2'>";
                      echo "<p class='location align-middle mt-2'>" . $lieu_debut[$excursion][0] . "</p>";
                    echo "</div>";
                    echo "<div class='col-2'>";
                      echo "<img src='img/LogoPrix.png' alt='prix'>";
                    echo "</div>";
                    echo "<div class='col-2'>";
                      echo "<p class='price align-middle mt-2'>" . $val['Tarif'] . "</p>";
                    echo "</div>";
                  echo "</div>";    
                echo "</li>";
              echo "</ul>";
              echo "<br/>";
              echo "<button class='btn btn-dark btn-lg' type='button' data-toggle='collapse' data-target='#collapseExample0" . $excursion . "' aria-expanded='false' aria-controls='collapseExample'>";
                echo "Nos Programmations";
              echo "</button>";
              echo "<br/>";
              echo "<div class='collapse mt-2' id='collapseExample0" . $excursion . "'>";
                echo "<div class='container'>";

                  // Requête préparée pour trouver toutes les valeurs de la table programme quand Id_Excursion = $excursion
                  $pdoStat = $PDO->prepare("SELECT * FROM programme WHERE Id_Excursion = :id_excursion;");
                  // Bind des valeurs
                  $pdoStat->bindValue(':id_excursion', ($excursion + 1), PDO::PARAM_INT);
                  // Exécution de la requête préparée
                  $executeIsOk = $pdoStat->execute();
                  // Récupération des résultats
                  $programmes = $pdoStat->fetchAll();

                  foreach ($programmes as $programme => $val2) {
                    echo "<div class='row'>";
                    echo "<div class='card card-body w-25 m-1' style='width: 14rem;'>";
                      echo "<ul class='list-group list-group-flush'>";
                        echo "<div class='row'>";
                          echo "<div class='col-2'>";
                            echo "<img src='img/LogoDebut.png' alt='debut'>";
                          echo "</div>";
                          echo "<div class='col-2'>";
                            echo "<p class='price align-middle mt-2'>" . $val2['Date_Debut'] . "</p>";
                          echo "</div>";
                          echo "<div class='col-2'>";
                            echo "<img src='img/LogoFin.png' alt='fin'>";
                          echo "</div>";
                          echo "<div class='col-2'>";
                            echo "<p class='price align-middle mt-2'>" . $val2['Date_Fin'] . "</p>";
                          echo "</div>";
                          echo "<div class='col-2'>";
                            echo "<img src='img/LogoGuide.png' alt='guide'>";
                          echo "</div>";
                          echo "<div class='col-2'>";

                          // Récupération du nom du guide via une jointure
                          $pdoStat = $PDO->prepare("SELECT Nom, Prenom FROM compte INNER JOIN programme ON compte.Id_Compte = programme.Id_Guide WHERE programme.Id_Excursion = :id_programme;"); // On souhaite le nom du guide pour chaque programme
                          // Bind des valeurs
                          $pdoStat->bindValue(':id_programme', ($excursion + 1), PDO::PARAM_INT);
                          $executeIsOk = $pdoStat->execute();
                          $nom_guide = $pdoStat->fetchAll();

                            echo "<p class='price align-middle mt-2'>" . $nom_guide[($programme)]['Nom'] . " " . $nom_guide[($programme)]['Prenom'] . "</p>";
                          echo "</div>";
                        echo "</div>";

                        // Formulaire
                        echo "<form class='align' action='excursions.php' method='post'>";
                          echo "<input type = 'hidden' id = 'ID_Program' name = 'ID_Program' value = '" . $val2['Id_Programme'] . "' />";
                          echo "<input class='btn btn-success btn-lg' type='submit' name='submit' value='Réserver'>";
                        echo "</form>";

                      echo "</ul>";
                    echo "</div>"; 
                    echo "</div>";
                  }
                  
                echo "</div>";
              echo "</div>";
            echo "</div>";
          echo "</div>";  
        echo "</div>";
      echo "</div>";

    }

  ?>


  <br/>

  <a href="index.php" class="btn btn-dark btn-lg btn-block" >RETOUR</a> <!-- Lien vers la page "Index.html" -->
  <br/> <br/>
	

	<!-- CARD FOOTER : FOOTER-->
	<!-- Informations générales -->
	<div class="card-fluid">
  		<div class="card-header bg-secondary text-white">
    		Nous contacter : 06 29 27 60 20
  		</div>
  		<div class="card-body bg-dark text-white">
    		<h5 class="card-title">Suivez-nous sur Twitter, Facebook, Instagram, LinkedIn. </h5>
    		<a href="rando_addict.html" class="btn btn-success">Plus d'infos</a> <!-- Lien vers la page "RandoAddict.html" -->
    		<p class="card-text">CONDITIONS DE VENTE ET ASSURANCES , MENTIONS LÉGALES ET CGU & CONFIDENTIALITÉ ET COOKIES © TOUS DROITS RÉSERVÉS RANDO ADDICT</p>
  		</div>
	</div>

</body>
</html>