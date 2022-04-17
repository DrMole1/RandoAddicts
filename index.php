<?php 

// On débute la session avec l'id du compte
session_start();
// Déclaration d'une variable tampon
$id_utilisateur = -1;

// Ouverture d'une connexion à la BDD. Nom de la BDD : rando_addicts
$PDO = new PDO('mysql:host=localhost;dbname=rando_addicts', 'root', '');

if(isset($_SESSION['id']))
{
	$id_utilisateur = $_SESSION['id'];

	// Requête préparée pour trouver le nom de l'utilisateur en cours
	$pdoStat = $PDO->prepare("SELECT nom FROM compte WHERE Id_Compte = :id;");
	$pdoStat->bindValue(':id', $id_utilisateur, PDO::PARAM_INT);
	// Exécution de la requête préparée
	$executeIsOk = $pdoStat->execute();
	// Récupération des résultats
	$nom = $pdoStat->fetch();


	// Requête préparée pour trouver le prenom de l'utilisateur en cours
	$pdoStat = $PDO->prepare("SELECT prenom FROM compte WHERE Id_Compte = :id;");
	$pdoStat->bindValue(':id', $id_utilisateur, PDO::PARAM_INT);
	// Exécution de la requête préparée
	$executeIsOk = $pdoStat->execute();
	// Récupération des résultats
	$prenom = $pdoStat->fetch();
}
	


// Requête préparée pour trouver toutes les valeurs de la BDD issue de la table excursion
$pdoStat = $PDO->prepare("SELECT * FROM excursion");
// Exécution de la requête préparée
$executeIsOk = $pdoStat->execute();
// Récupération des résultats
$excursions = $pdoStat->fetchAll();

// Randomisation des excursions à présenter : A chaque fois que l'utilisateur va sur la page Index, il voit 4 excursions différentes prises au hasard dans la base de données
$pdoStat = $PDO->prepare("SELECT COUNT(Id_Excursion) FROM excursion"); // Nombre d'excursions présentes dans la BDD
$executeIsOk = $pdoStat->execute();
$nb_excursions = $pdoStat->fetch();
$tab_alea = array(-1, -1, -1, -1); // Initialisation d'un tableau pour avoir des excursions aléatoires SANS DOUBLONS !
$nb_alea = -1;
for ($i=0; $i < 4; $i++) { 
	while($nb_alea == $tab_alea[0] || $nb_alea == $tab_alea[1] || $nb_alea == $tab_alea[2] || $nb_alea == $tab_alea[3]) {
		$nb_alea = rand(0, $nb_excursions[0] - 1);
	}
	$tab_alea[$i] = $nb_alea;
}

// Récupération des lieux de début via une jointure
$pdoStat = $PDO->prepare("SELECT ville, id_excursion FROM excursion INNER JOIN lieu ON excursion.Lieu_Debut = lieu.Id_Lieu ORDER BY id_excursion;"); // On souhaite la ville de départ pour chaque excursion
$executeIsOk = $pdoStat->execute();
$lieu_debut = $pdoStat->fetchAll();

// Récupération des Coups de Coeur : Les excursions "Coup de Coeur" sont les 3 dernières excursions présentes dans la table excursion
$pdoStat = $PDO->prepare("SELECT * FROM excursion ORDER BY id_excursion DESC LIMIT 3;"); // Les 3 excursions les plus récentes
$executeIsOk = $pdoStat->execute();
$coups_de_coeur = $pdoStat->fetchAll();

// Récupération des Guides via une jointure
$pdoStat = $PDO->prepare("SELECT * FROM compte INNER JOIN compte_role ON compte.Id_Compte = compte_role.Id_Compte AND compte_role.Id_Role = 2 ORDER BY compte.Id_Compte;"); // Les utilisateurs qui possèdent un droit de Guide (Id_Role = 2)
$executeIsOk = $pdoStat->execute();
$guides = $pdoStat->fetchAll();

?>



<!DOCTYPE html>
<html>
<head>
	<title>RandoAddict</title>
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
		<div class="row border border-dark bg-success">
			<div class="col-lg-2 col-md-1 bg-success p-5">
				<p id="title_RandoAddict"> RandoAddict</p>
			</div>
			<div class="col bg-success p-4">
				<img src="img/LogoRA.png" loading="lazy" alt="logoRA">
			</div>
			<div class="col-lg-8 col-md-6 col-sm-4">
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
				<div class="row justify-content-around bg-secondary p-3 border border-dark">
					<div class="col">
						<a href="#nos_excursions" class="link-light text-white">Nos Excursions</a> <!-- Ancre vers "Nos Excursions"-->
					</div>
					<div class="col">
						<a href="#nos_coupsdecoeur" class="link-light text-white">Nos Coups de Coeur</a> <!-- Ancre vers "Nos Coups de Coeur"-->
					</div>
					<div class="col">
						<a href="#nos_guides" class="link-light text-white">Nos Guides</a> <!-- Ancre vers "Nos Guides"-->
					</div>
				</div>
			</div>
		</div>
	</div>
	<br/>

	<?php  
		if($id_utilisateur != -1)         // Si l'utilisateur est connecté, il voit un message de bienvenue
		{
			echo "<p> Bonjour " . $nom[0] . " " . $prenom[0] . " !" . "</p>";
		}
	?>


	<!-- JUMBOTRON : PRESENTATION-->
	<!-- Présentation succinte et attrayante de Rando Addicte -->
	<div class="container">
		<div class="jumbotron">
  			<h2 class="display-4">Rando Addict</h2>
  			<p class="lead">Amoureux de nature et de marche ? Trouvez votre bonheur avec RandoAddict !</p>
  			<img src="img/Rando01.png" loading="lazy" alt="rando_img01">
  			<img src="img/Rando02.png" loading="lazy" alt="rando_img02">
  			<img src="img/Rando03.png" loading="lazy" alt="rando_img03">
  			<hr class="my-4">
  			<p>Un large choix de promenades et de randonnées s'offre à vous. Qu'attendez-vous ?</p>
  			<p class="lead">
    			<a class="btn btn-success btn-lg" href="rando_addict.html" role="button">Qui sommes-nous ?</a> <!-- Lien vers la page "RandoAddict.html" -->
  			</p>
		</div>
	</div>
	<br/>

	<h1 id="nos_excursions">Nos Excursions</h1>	<br/>
	<h3>Votre voyage selon vos envies !</h3>


	<!-- CARDS : NOS EXCURSIONS-->	
	<!-- Présentation de quelques excursions dans un format de cards -->
	<div class="container">
		<div class="row">
			<div class="col">
				<div class="card" style="width: 14rem;">
					<?php
						// Gestion de l'affiche de la photo appropriée en concaténant le chemin de la photo dans le dossier local avec le numéro de l'excursion
						$indexPhoto = $tab_alea[0] + 1;
						echo "<img class='card-img-top' src='img/Excu0" . $indexPhoto . ".png' loading='lazy' alt='Img_Card'>";
					?>
  					<div class="card-body">
    					<h5 class="card-title"><strong> <?= $excursions[$tab_alea[0]]['Nom'] ?> </strong></h5>
    					<p class="card-text"> <?= $excursions[$tab_alea[0]]['Description'] ?> </p>
    					<ul class="list-group list-group-flush">
    						<li class="list-group-item">
    							<div class="row">
    								<div class="col">
    									<img src="img/LogoDifficulte.png" alt="difficulte">
    								</div>
    								<div class="col">
    								<?php
    									// Gestion de la couleur Bootstrap de la difficulté selon cette dernière
    									if($excursions[$tab_alea[0]]['Difficulte'] <= 3) {
    										echo "<p class='difficulty text-success'>" . $excursions[$tab_alea[0]]['Difficulte'] . "/ 10 </p>";
    									} else if ($excursions[$tab_alea[0]]['Difficulte'] <= 6) {
    										echo "<p class='difficulty text-warning'>" . $excursions[$tab_alea[0]]['Difficulte'] . "/ 10 </p>";
    									} else {
    										echo "<p class='difficulty text-danger'>" . $excursions[$tab_alea[0]]['Difficulte'] . "/ 10 </p>";
    									}
    								?>
    								</div>
    							</div>		
    						</li>
    						<li class="list-group-item">
    							<div class="row">
    								<div class="col">
    									<img src="img/LogoLieu.png" alt="lieu">
    								</div>
    								<div class="col">
    									<p class="location align-middle mt-2"> <?= $lieu_debut[$tab_alea[0]][0] ?> </p>
    								</div>
    							</div>	
    						</li>
    						<li class="list-group-item">
    							<div class="row">
    								<div class="col">
    									<img src="img/LogoPrix.png" alt="prix">
    								</div>
    								<div class="col">
    									<p class="price align-middle mt-2"> <?= $excursions[$tab_alea[0]]['Tarif'] ?> </p>
    								</div>
    							</div>	
    						</li>
  						</ul>
    					</br><a href="excursions.php" class="btn btn-success">Découvrir</a> <!-- Lien vers la page "excursions.php" -->
  					</div>
				</div>	
			</div>
			<div class="col">
				<div class="card" style="width: 14rem;">
  					<?php
						// Gestion de l'affiche de la photo appropriée en concaténant le chemin de la photo dans le dossier local avec le numéro de l'excursion
						$indexPhoto = $tab_alea[1] + 1;
						echo "<img class='card-img-top' src='img/Excu0" . $indexPhoto . ".png' loading='lazy' alt='Img_Card'>";
					?>
  					<div class="card-body">
    					<h5 class="card-title"><strong> <?= $excursions[$tab_alea[1]]['Nom'] ?> </strong></h5>
    					<p class="card-text"> <?= $excursions[$tab_alea[1]]['Description'] ?> </p>
    					<ul class="list-group list-group-flush">
    						<li class="list-group-item">
    							<div class="row">
    								<div class="col">
    									<img src="img/LogoDifficulte.png" alt="difficulte">
    								</div>
    								<div class="col">
    								<?php
    									// Gestion de la couleur Bootstrap de la difficulté selon cette dernière
    									if($excursions[$tab_alea[1]]['Difficulte'] <= 3) {
    										echo "<p class='difficulty text-success'>" . $excursions[$tab_alea[1]]['Difficulte'] . "/ 10 </p>";
    									} else if ($excursions[$tab_alea[1]]['Difficulte'] <= 6) {
    										echo "<p class='difficulty text-warning'>" . $excursions[$tab_alea[1]]['Difficulte'] . "/ 10 </p>";
    									} else {
    										echo "<p class='difficulty text-danger'>" . $excursions[$tab_alea[1]]['Difficulte'] . "/ 10 </p>";
    									}
    								?>
    								</div>
    							</div>	
    						</li>
    						<li class="list-group-item">
    							<div class="row">
    								<div class="col">
    									<img src="img/LogoLieu.png" alt="lieu">
    								</div>
    								<div class="col">
    									<p class="location align-middle mt-2"><?= $lieu_debut[$tab_alea[1]][0] ?></p>
    								</div>
    							</div>	
    						</li>
    						<li class="list-group-item">
    							<div class="row">
    								<div class="col">
    									<img src="img/LogoPrix.png" alt="prix">
    								</div>
    								<div class="col">
    									<p class="price align-middle mt-2"><?= $excursions[$tab_alea[1]]['Tarif'] ?></p>
    								</div>
    							</div>	
    						</li>
  						</ul>
    					</br><a href="excursions.php" class="btn btn-success">Découvrir</a> <!-- Lien vers la page "excursions.php" -->
  					</div>
				</div>	
			</div>
			<div class="col">
				<div class="card" style="width: 14rem;">
  					<?php
						// Gestion de l'affiche de la photo appropriée en concaténant le chemin de la photo dans le dossier local avec le numéro de l'excursion
						$indexPhoto = $tab_alea[2] + 1;
						echo "<img class='card-img-top' src='img/Excu0" . $indexPhoto . ".png' loading='lazy' alt='Img_Card'>";
					?>
  					<div class="card-body">
    					<h5 class="card-title"><strong> <?= $excursions[$tab_alea[2]]['Nom'] ?> </strong></h5>
    					<p class="card-text"> <?= $excursions[$tab_alea[2]]['Description'] ?> </p>
    					<ul class="list-group list-group-flush">
    						<li class="list-group-item">
    							<div class="row">
    								<div class="col">
    									<img src="img/LogoDifficulte.png" alt="difficulte">
    								</div>
    								<div class="col">
    								<?php
    									// Gestion de la couleur Bootstrap de la difficulté selon cette dernière
    									if($excursions[$tab_alea[2]]['Difficulte'] <= 3) {
    										echo "<p class='difficulty text-success'>" . $excursions[$tab_alea[2]]['Difficulte'] . "/ 10 </p>";
    									} else if ($excursions[$tab_alea[2]]['Difficulte'] <= 6) {
    										echo "<p class='difficulty text-warning'>" . $excursions[$tab_alea[2]]['Difficulte'] . "/ 10 </p>";
    									} else {
    										echo "<p class='difficulty text-danger'>" . $excursions[$tab_alea[2]]['Difficulte'] . "/ 10 </p>";
    									}
    								?>
    								</div>
    							</div>	
    						</li>
    						<li class="list-group-item">
    							<div class="row">
    								<div class="col">
    									<img src="img/LogoLieu.png" alt="lieu">
    								</div>
    								<div class="col">
    									<p class="location align-middle mt-2"><?= $lieu_debut[$tab_alea[2]][0] ?></p>
    								</div>
    							</div>	
    						</li>
    						<li class="list-group-item">
    							<div class="row">
    								<div class="col">
    									<img src="img/LogoPrix.png" alt="prix">
    								</div>
    								<div class="col">
    									<p class="price align-middle mt-2"><?= $excursions[$tab_alea[2]]['Tarif'] ?></p>
    								</div>
    							</div>	
    						</li>
  						</ul>
    					</br><a href="excursions.php" class="btn btn-success">Découvrir</a> <!-- Lien vers la page "excursions.php" -->
  					</div>
				</div>	
			</div>
			<div class="col">
				<div class="card" style="width: 14rem;">
  					<?php
						// Gestion de l'affiche de la photo appropriée en concaténant le chemin de la photo dans le dossier local avec le numéro de l'excursion
						$indexPhoto = $tab_alea[3] + 1;
						echo "<img class='card-img-top' src='img/Excu0" . $indexPhoto . ".png' loading='lazy' alt='Img_Card'>";
					?>
  					<div class="card-body">
    					<h5 class="card-title"><strong> <?= $excursions[$tab_alea[3]]['Nom'] ?> </strong></h5>
    					<p class="card-text"> <?= $excursions[$tab_alea[3]]['Description'] ?> </p>
    					<ul class="list-group list-group-flush">
    						<li class="list-group-item">
    							<div class="row">
    								<div class="col">
    									<img src="img/LogoDifficulte.png" alt="difficulte">
    								</div>
    								<div class="col">
    								<?php
    									// Gestion de la couleur Bootstrap de la difficulté selon cette dernière
    									if($excursions[$tab_alea[3]]['Difficulte'] <= 3) {
    										echo "<p class='difficulty text-success'>" . $excursions[$tab_alea[3]]['Difficulte'] . "/ 10 </p>";
    									} else if ($excursions[$tab_alea[3]]['Difficulte'] <= 6) {
    										echo "<p class='difficulty text-warning'>" . $excursions[$tab_alea[3]]['Difficulte'] . "/ 10 </p>";
    									} else {
    										echo "<p class='difficulty text-danger'>" . $excursions[$tab_alea[3]]['Difficulte'] . "/ 10 </p>";
    									}
    								?>
    								</div>
    							</div>	
    						</li>
    						<li class="list-group-item">
    							<div class="row">
    								<div class="col">
    									<img src="img/LogoLieu.png" alt="lieu">
    								</div>
    								<div class="col">
    									<p class="location align-middle mt-2"><?= $lieu_debut[$tab_alea[3]][0] ?></p>
    								</div>
    							</div>	
    						</li>
    						<li class="list-group-item">
    							<div class="row">
    								<div class="col">
    									<img src="img/LogoPrix.png" alt="prix">
    								</div>
    								<div class="col">
    									<p class="price align-middle mt-2"><?= $excursions[$tab_alea[3]]['Tarif'] ?></p>
    								</div>
    							</div>	
    						</li>
  						</ul>
    					</br><a href="excursions.php" class="btn btn-success">Découvrir</a> <!-- Lien vers la page "excursions.php" -->
  					</div>
				</div>	
			</div>
		</div>	
	</div> <br/>


	<div class="align">
		<p><strong>Envie de découvrir toutes nos excursions ?</strong></p>
  		<a href="excursions.html" class="btn btn-success">Voir toutes les excursions</a>
	</div> <br/> <br/> <br/>


	<h1 id="nos_coupsdecoeur">Nos Coups de coeur</h1> <br/>
	<h3>Craquer pour notre sélection !</h3>


	<!-- CAROUSEL : NOS COUPS DE COEUR-->
	<!-- Présentation de quelques excursions très bien notées avec une plus grande image en format carousel -->
	<div id="carouselExampleIndicators" class="carousel slide w-50 d-flex align-items-center carousel_caption" data-ride="carousel">
  		<ol class="carousel-indicators">
    		<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
    		<li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
    		<li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
  		</ol>
  		<div class="carousel-inner">
    		<div class="carousel-item active"> <!-- Première slide Carousel -->
    			<?php
					// Gestion de l'affiche de la photo appropriée en concaténant le chemin de la photo dans le dossier local avec le numéro de l'excursion
					$indexPhoto = 1;
					echo "<img class='d-block w-100' src='img/CoupDeCoeur0" . $indexPhoto . ".png' loading='lazy' alt='Slide'>";
				?>
      			<div class="carousel-caption d-none d-md-block">
    				<h3> <?= $coups_de_coeur[0]['Nom'] ?> </h3>
    				<p> <?= $coups_de_coeur[0]['Description'] ?> </p>
    				<a href="excursions.php" class="btn btn-success">Découvrir</a> <!-- Lien vers la page "excursions.php" -->
  				</div>
    		</div>
    		<div class="carousel-item"> <!-- Deuxième slide Carousel -->
      			<?php
					// Gestion de l'affiche de la photo appropriée en concaténant le chemin de la photo dans le dossier local avec le numéro de l'excursion
					$indexPhoto = 2;
					echo "<img class='d-block w-100' src='img/CoupDeCoeur0" . $indexPhoto . ".png' loading='lazy' alt='Slide'>";
				?>
      			<div class="carousel-caption d-none d-md-block">
    				<h3> <?= $coups_de_coeur[1]['Nom'] ?> </h3>
    				<p> <?= $coups_de_coeur[1]['Description'] ?> </p>
    				<a href="excursions.php" class="btn btn-success">Découvrir</a> <!-- Lien vers la page "excursions.php" -->
  				</div>
    		</div>
    		<div class="carousel-item"> <!-- Troisième slide Carousel -->
      			<?php
					// Gestion de l'affiche de la photo appropriée en concaténant le chemin de la photo dans le dossier local avec le numéro de l'excursion
					$indexPhoto = 3;
					echo "<img class='d-block w-100' src='img/CoupDeCoeur0" . $indexPhoto . ".png' loading='lazy' alt='Slide'>";
				?>
      			<div class="carousel-caption d-none d-md-block">
    				<h3> <?= $coups_de_coeur[2]['Nom'] ?> </h3>
    				<p> <?= $coups_de_coeur[2]['Description'] ?> </p>
    				<a href="excursions.php" class="btn btn-success">Découvrir</a> <!-- Lien vers la page "excursions.php" -->
  				</div>
    		</div>
  		</div>
  		<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev"> <!-- Bouton "précédent" du carousel -->
    		<span class="carousel-control-prev-icon" aria-hidden="true"></span>
    		<span class="sr-only">Précédent</span>
  		</a>
  		<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next"> <!-- Bouton "suivant" du carousel -->
    		<span class="carousel-control-next-icon" aria-hidden="true"></span>
    		<span class="sr-only">Suivant</span>
  		</a>
	</div> <br/> <br/> <br/>


	<h1 id="nos_guides">Nos Guides</h1> <br/>
	<h3>Rencontrez nos marcheurs passionnés !</h3> <br/>


	<!-- COLLAPSE : NOS GUIDES -->
	<!-- Présentation des guides reconnus par Rando Addict en format collapse -->
	<p class="align">
		<button class="btn btn-dark btn-lg" type="button" data-toggle="collapse" data-target="#collapseExample01" aria-expanded="false" aria-controls="collapseExample">
    		01
  		</button>
  		<button class="btn btn-dark btn-lg" type="button" data-toggle="collapse" data-target="#collapseExample02" aria-expanded="false" aria-controls="collapseExample">
    		02
  		</button>
  		<button class="btn btn-dark btn-lg" type="button" data-toggle="collapse" data-target="#collapseExample03" aria-expanded="false" aria-controls="collapseExample">
    		03
  		</button>
	</p>


	<!-- Première page de collapse concernant les guides -->
	<div class="collapse center" id="collapseExample01">
  		<div class="card card-body align-items-center" style="width: 80rem;">
    		<div class="container">
    			<div class="row">
    				<div class="card card-body w-25 m-1" style="width: 14rem;">
    					<h5 class="card-title"><strong> <?= $guides[0]['Nom'] ?> <?= $guides[0]['Prenom'] ?> </strong></h5>
    					<?php
							// Gestion de l'affiche de la photo appropriée en concaténant le chemin de la photo dans le dossier local avec le numéro de l'excursion
							$indexPhoto = $guides[0]['Id_Compte'];
							echo "<img src='img/Guide0" . $indexPhoto . ".png' loading='lazy' alt='guide01' style='width: 11rem;' class='photo_caption'>";
						?>
    					<ul class="list-group list-group-flush">
    						<li class="list-group-item"> <?= $guides[0]['Age'] ?> ans </li>
    						<li class="list-group-item"> <?= $guides[0]['Numero'] ?> </li>
    						<li class="list-group-item"> <?= $guides[0]['Email'] ?> </li>
  						</ul>
  					</div>
  					<div class="card card-body w-25 m-1" style="width: 14rem;">
    					<h5 class="card-title"><strong> <?= $guides[1]['Nom'] ?> <?= $guides[1]['Prenom'] ?> </strong></h5>
    					<?php
							// Gestion de l'affiche de la photo appropriée en concaténant le chemin de la photo dans le dossier local avec le numéro de l'excursion
							$indexPhoto = $guides[1]['Id_Compte'];
							echo "<img src='img/Guide0" . $indexPhoto . ".png' loading='lazy' alt='guide01' style='width: 11rem;' class='photo_caption'>";
						?>
    					<ul class="list-group list-group-flush">
    						<li class="list-group-item"> <?= $guides[1]['Age'] ?> ans </li>
    						<li class="list-group-item"> <?= $guides[1]['Numero'] ?> </li>
    						<li class="list-group-item"> <?= $guides[1]['Email'] ?> </li>
  						</ul>
  					</div>
  					<div class="card card-body w-25 m-1" style="width: 14rem;">
    					<h5 class="card-title"><strong> <?= $guides[2]['Nom'] ?> <?= $guides[2]['Prenom'] ?> </strong></h5>
    					<?php
							// Gestion de l'affiche de la photo appropriée en concaténant le chemin de la photo dans le dossier local avec le numéro de l'excursion
							$indexPhoto = $guides[2]['Id_Compte'];
							echo "<img src='img/Guide0" . $indexPhoto . ".png' loading='lazy' alt='guide01' style='width: 11rem;' class='photo_caption'>";
						?>
    					<ul class="list-group list-group-flush">
    						<li class="list-group-item"> <?= $guides[2]['Age'] ?> ans </li>
    						<li class="list-group-item"> <?= $guides[2]['Numero'] ?> </li>
    						<li class="list-group-item"> <?= $guides[2]['Email'] ?> </li>
  						</ul>
  					</div>
    			</div>
    		</div>
  		</div>
	</div>


	<!-- Deuxième page de collapse concernant les guides -->
	<div class="collapse center" id="collapseExample02">
  		<div class="card card-body align-items-center" style="width: 80rem;">
    		<div class="container">
    			<div class="row">
    				<div class="card card-body w-25 m-1" style="width: 14rem;">
    					<h5 class="card-title"><strong> <?= $guides[3]['Nom'] ?> <?= $guides[3]['Prenom'] ?> </strong></h5>
    					<?php
							// Gestion de l'affiche de la photo appropriée en concaténant le chemin de la photo dans le dossier local avec le numéro de l'excursion
							$indexPhoto = $guides[3]['Id_Compte'];
							echo "<img src='img/Guide0" . $indexPhoto . ".png' loading='lazy' alt='guide01' style='width: 11rem;' class='photo_caption'>";
						?>
    					<ul class="list-group list-group-flush">
    						<li class="list-group-item"> <?= $guides[3]['Age'] ?> ans </li>
    						<li class="list-group-item"> <?= $guides[3]['Numero'] ?> </li>
    						<li class="list-group-item"> <?= $guides[3]['Email'] ?> </li>
  						</ul>
  					</div>
  					<div class="card card-body w-25 m-1" style="width: 14rem;">
    					<h5 class="card-title"><strong> <?= $guides[4]['Nom'] ?> <?= $guides[4]['Prenom'] ?> </strong></h5>
    					<?php
							// Gestion de l'affiche de la photo appropriée en concaténant le chemin de la photo dans le dossier local avec le numéro de l'excursion
							$indexPhoto = $guides[4]['Id_Compte'];
							echo "<img src='img/Guide0" . $indexPhoto . ".png' loading='lazy' alt='guide01' style='width: 11rem;' class='photo_caption'>";
						?>
    					<ul class="list-group list-group-flush">
    						<li class="list-group-item"> <?= $guides[4]['Age'] ?> ans </li>
    						<li class="list-group-item"> <?= $guides[4]['Numero'] ?> </li>
    						<li class="list-group-item"> <?= $guides[4]['Email'] ?> </li>
  						</ul>
  					</div>
  					<div class="card card-body w-25 m-1" style="width: 14rem;">
    					<h5 class="card-title"><strong> <?= $guides[5]['Nom'] ?> <?= $guides[5]['Prenom'] ?> </strong></h5>
    					<?php
							// Gestion de l'affiche de la photo appropriée en concaténant le chemin de la photo dans le dossier local avec le numéro de l'excursion
							$indexPhoto = $guides[5]['Id_Compte'];
							echo "<img src='img/Guide0" . $indexPhoto . ".png' loading='lazy' alt='guide01' style='width: 11rem;' class='photo_caption'>";
						?>
    					<ul class="list-group list-group-flush">
    						<li class="list-group-item"> <?= $guides[5]['Age'] ?> ans </li>
    						<li class="list-group-item"> <?= $guides[5]['Numero'] ?> </li>
    						<li class="list-group-item"> <?= $guides[5]['Email'] ?> </li>
  						</ul>
  					</div>
    			</div>
    		</div>
  		</div>
	</div>


	<!-- Troisième page de collapse concernant les guides -->
	<div class="collapse center" id="collapseExample03">
  		<div class="card card-body align-items-center" style="width: 80rem;">
    		<div class="container">
    			<div class="row">
    				<div class="card card-body w-25 m-1" style="width: 14rem;">
    					<h5 class="card-title"><strong> <?= $guides[6]['Nom'] ?> <?= $guides[6]['Prenom'] ?> </strong></h5>
    					<?php
							// Gestion de l'affiche de la photo appropriée en concaténant le chemin de la photo dans le dossier local avec le numéro de l'excursion
							$indexPhoto = $guides[6]['Id_Compte'];
							echo "<img src='img/Guide0" . $indexPhoto . ".png' loading='lazy' alt='guide01' style='width: 11rem;' class='photo_caption'>";
						?>
    					<ul class="list-group list-group-flush">
    						<li class="list-group-item"> <?= $guides[6]['Age'] ?> ans </li>
    						<li class="list-group-item"> <?= $guides[6]['Numero'] ?> </li>
    						<li class="list-group-item"> <?= $guides[6]['Email'] ?> </li>
  						</ul>
  					</div>
  					<div class="card card-body w-25 m-1" style="width: 14rem;">
    					<h5 class="card-title"><strong> <?= $guides[7]['Nom'] ?> <?= $guides[7]['Prenom'] ?> </strong></h5>
    					<?php
							// Gestion de l'affiche de la photo appropriée en concaténant le chemin de la photo dans le dossier local avec le numéro de l'excursion
							$indexPhoto = $guides[7]['Id_Compte'];
							echo "<img src='img/Guide0" . $indexPhoto . ".png' loading='lazy' alt='guide01' style='width: 11rem;' class='photo_caption'>";
						?>
    					<ul class="list-group list-group-flush">
    						<li class="list-group-item"> <?= $guides[7]['Age'] ?> ans </li>
    						<li class="list-group-item"> <?= $guides[7]['Numero'] ?> </li>
    						<li class="list-group-item"> <?= $guides[7]['Email'] ?> </li>
  						</ul>
  					</div>
  					<div class="card card-body w-25 m-1" style="width: 14rem;">
    					<h5 class="card-title"><strong> <?= $guides[8]['Nom'] ?> <?= $guides[8]['Prenom'] ?> </strong></h5>
    					<?php
							// Gestion de l'affiche de la photo appropriée en concaténant le chemin de la photo dans le dossier local avec le numéro de l'excursion
							$indexPhoto = $guides[8]['Id_Compte'];
							echo "<img src='img/Guide0" . $indexPhoto . ".png' loading='lazy' alt='guide01' style='width: 11rem;' class='photo_caption'>";
						?>
    					<ul class="list-group list-group-flush">
    						<li class="list-group-item"> <?= $guides[8]['Age'] ?> ans </li>
    						<li class="list-group-item"> <?= $guides[8]['Numero'] ?> </li>
    						<li class="list-group-item"> <?= $guides[8]['Email'] ?> </li>
  						</ul>
  					</div>
    			</div>
    		</div>
  		</div>
	</div>
	

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