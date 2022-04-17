<?php 
	

// Ouverture d'une connexion à la BDD
$PDO = new PDO('mysql:host=localhost;dbname=rando_addicts', 'root', '');
// Déclaration de la variable $message
$message = "ERROR";

// Récupération des informations
if(isset($_POST['submit']))
{
	$nom = $_POST['nom'];
	$prenom = $_POST['prenom'];
	$age = $_POST['age'];
	$tel = $_POST['tel'];
	$email = $_POST['email'];
	$mdp = $_POST['mdp'];
	$mdp2 = $_POST['mdp2'];

	if($nom == '' || $prenom == '' || $age == '' || $tel == '' || $email == '' || $mdp == '' || $mdp2 == '') // Retourne une erreur si un champs au moins est vide
	{
		$message = "Erreur à la création du compte ! Un ou plusieurs champs sont vides !";
	}
	else if ($mdp != $mdp2) // Retourne une erreur si les mots de passe ne sont pas identiques
	{
		$message = "Erreur à la création du compte ! Les mots de passe saisis sont différents !";
	}
	else
	{
		// Retourne une erreur si l'adresse mail est déjà utilisée
		$pdoStat = $PDO->prepare("SELECT COUNT(*) FROM compte WHERE Email = :email;");
  		$pdoStat->bindValue(':email', $email, PDO::PARAM_STR);
  		$executeIsOk = $pdoStat->execute();
  		// Récupération des résultats. 0 = pas de compte trouvé, 1 = compte existant
  		$nb_occurence = $pdoStat->fetch();
  		// Si le compte existe, message d'erreur
  		if($nb_occurence[0] == 1)
  		{
  			$message = "Erreur à la création du compte ! L'adresse mail fournie est déjà utilisée !";
  		}
  		else // Sinon, création du compte
  		{
  			// Préparation de la requête d'insertion (SQL)
			$pdoStat = $PDO->prepare('INSERT INTO compte VALUES (NULL, :nom, :prenom, :age, :tel, :email, :mdp, :date_creation, NULL)');

			// Lier chaque marqueur à une valeur
			// bindValue(marqueur, valeur, type)
			$pdoStat->bindValue(':nom', $nom, PDO::PARAM_STR);
			$pdoStat->bindValue(':prenom', $prenom, PDO::PARAM_STR);
			$pdoStat->bindValue(':age', $age, PDO::PARAM_INT);
			$pdoStat->bindValue(':tel', $tel, PDO::PARAM_STR);
			$pdoStat->bindValue(':email', $email, PDO::PARAM_STR);
			$pdoStat->bindValue(':mdp', $mdp, PDO::PARAM_STR);
			$date_creation = new DateTime();
			$date_creation = $date_creation->format('Y-m-d');
			$pdoStat->bindValue(':date_creation', $date_creation);

			// Exécution de la requête préparée
			$insertIsOk = $pdoStat->execute();

			// Vérification de l'exécution de la requête
			if($insertIsOk)
			{
				$message = "Votre compte a été créé !";

				// Insertion du role Marcheur pour le nouveau compte créé : utilisation d'une sous requete
				$pdoStat = $PDO->prepare('INSERT INTO compte_role VALUES ((SELECT Id_Compte FROM compte WHERE Email = :email), 3);');
				$pdoStat->bindValue(':email', $email, PDO::PARAM_STR);
				$insertRoleIsOk = $pdoStat->execute();

				// Envoie d'un mail au nouveau compte
				/*$destinataire = $email;
				// Pour les champs $expediteur / $copie / $destinataire, séparer par une virgule s'il y a plusieurs adresses
				$expediteur = 'bastienprob@gmail.com';
				//$copie = 'adresse@fai.com';
				//$copie_cachee = 'adresse@fai.com';
				$objet = 'Bienvenue à Rando Addicts !'; // Objet du message
				$headers  = 'MIME-Version: 1.0' . "\n"; // Version MIME
				$headers .= 'Reply-To: '.$expediteur."\n"; // Mail de reponse
				$headers .= 'From: "Nom_de_expediteur"<'.$expediteur.'>'."\n"; // Expediteur
				$headers .= 'Delivered-to: '.$destinataire."\n"; // Destinataire      
				$message = 'Votre compte Rando Addicts a bien été créé ! Bonnes randonnées ! :)';
				if (mail($destinataire, $objet, $message, $headers)) // Envoi du message
				{
				    echo 'Votre message a bien été envoyé ';
				}
				else // Non envoyé
				{
				    echo "Votre message n'a pas pu être envoyé";
				}*/
			}
  		}
	}
}



?>


<!DOCTYPE html>
<html>
<head>
	<title>RandoAddict-Insertion</title>
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


  <h1>Création de Compte</h1> <br/>


  <!-- JUMBOTRON : PRESENTATION-->
  <div class="container">
    <div class="jumbotron">
        <h2 class="display-4">Résultat</h2>
        <p class="lead"><?= $message ?></p>
        <hr class="my-4">
        <p>Retour à la page de connexion :</p>
        <p class="lead">
          <a href="connexion.php" class="btn btn-dark btn-lg btn-block" >RETOUR</a> <!-- Lien vers la page "connexion.html" -->
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