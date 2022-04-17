insert into lieu values
	(1, 'Tours', 37000, 'Centre', 47.39, 0.68, NULL),
	(2, 'Villars', 42390, 'Auvergne-Rhône-Alpes', 45.46, 4.35, 500),
	(3, 'Sollières', 73500, 'Auvergne-Rhône-Alpes', 45.26, 6.80, 1230),
	(4, 'Planay', 73350, 'Auvergne-Rhône-Alpes', 45.42, 6.69, NULL),
	(5, 'St-Jean', 31240, 'Occitanie', 43.66, 1.49, NULL),
	(6, 'Voreppe', 38340, 'Auvergne-Rhône-Alpes', 45.29, 5.63, NULL),
	(7, 'Sibiril', 29250, 'Bretagne', 48.66, -4.06, NULL),
	(8, 'Roscoff', 29680, 'Bretagne', 48.72, -3.98, NULL),
	(9, 'Autrans', 38112, 'Auvergne-Rhône-Alpes', 45.16, 5.55, NULL),
	(10, 'Méaudre', 38110, 'Auvergne-Rhône-Alpes', 45.16, 5.55, NULL),
	(11, 'Albi', 81000, 'Occitanie', 43.92, 2.14, NULL),
	(12, 'Lavaur', 81500, 'Occitanie', 43.7, 1.81, NULL);


insert into role values
	(1, 'Administrateur', 'Possède tous les droits, y compris de lire / modifier la base de données.'),
	(2, 'Guide', 'Possède le droit de créer un programme.'),
	(3, 'Marcheur', 'Possède le droit de rejoindre un programme.');


insert into compte values
	(1,'PantalonCarre','Bob',23,'0623818136', 'bobeponge@gmail.com', 'bob', '2022/04/15', NULL),
	(2,'Rambo','Jhon',45,'0650508211', 'rambo@gmail.com', 'rambo', '2022/04/15', NULL),
	(3,'Blblblbl','LaDenree',122,'9701020304', 'blblblbl@gmail.com', 'blbl', '2022/04/15', NULL),
	(4,'Lafondue','Robert',31,'0678764241', 'robertlafondue@gmail.com', 'lafondue', '2022/04/15', NULL),
	(5,'Jones','Indiana',55,'0674454567', 'indianajones@gmail.com', 'jones', '2022/04/15', NULL),
	(6,'Istari','Gandalf',1540,'0607078124', 'gandalf@gmail.com', 'gandalf', '2022/04/15', NULL),
	(7,'LeGaulois','Astérix',29,'0745904592', 'asterix@gmail.com', 'asterix', '2022/04/15', NULL),
	(8,'LeGuide','Michelin',90,'0612145691', 'michelin@gmail.com', 'michelin', '2022/04/15', NULL),
	(9,'Ogre','Shrek',34,'0601014590', 'shrek@gmail.com', 'shrek', '2022/04/15', NULL);


insert into compte_role values
	(1, 2),
	(2, 2),
	(3, 2),
	(4, 2),
	(5, 2),
	(6, 2),
	(7, 2),
	(8, 2),
	(9, 2);


insert into equipement values
	(1, 'Bâton de marche', 'Un bâton de marche est un bâton utilisé par certaines personnes pour améliorer leurs appuis au sol.', 550),
	(2, 'Housse de pluie', 'Rain-cover, sur-sac à mettre sur votre sac à dos en cas de pluie.', 90),
	(3, 'Tente', 'La tente est un abri temporaire et démontable, constitué d une armature rigide couverte de toile ou de matériaux synthétiques.', 1700),
	(4, 'Serviettes microfibres', 'Les microfibres sont des fibres textiles.', 140),
	(5, 'Réchaud', 'Un réchaud est un dispositif destiné à chauffer ou à garder au chaud des aliments ou boissons.', 340);


insert into excursion values
	(1,'LoireVelo','Une découverte des châteaux de la Loire, sur des pistes cyclables et des petites routes agréables.',990,9,3,'foret',1,2),
	(2,'GlaciersVanoise','Le célèbre tour de la Vanoise en itinérance et ses plus beaux paysages.',445,6,6,'montagne',3,4),
	(3,'SentiersChartreuse','Une randonnée au cœur de montagnes réputées pour son monastère et ses paysages verdoyants.',745,4,8,'montagne',5,6),
	(4,'RandoRoscoff','Un séjour conjuguant randonnées vivifiantes en bord de mer, thalassothérapie et confort.',980,8,1,'mer',7,8),
	(5,'MeilleurVercors','A partir d un gîte confortable, un programme de randonnées variées pour découvrir les charmes du Vercors.',445,9,6,'foret',9,10),
	(6,'PatrimoineChartreuse','Une randonnée au cœur de montagnes réputées pour son monastère et ses paysages verdoyants.',745,9,3,'montagne',6,5),
	(7,'PatrimoineTarn','Un parcours entre Lozère et Aveyron, à la découverte des gorges du Tarn et de la Jonte.',990,9,3,'foret',11,12);


insert into excursion_equipement values
	(2, 1),
	(2, 2),
	(2, 3),
	(3, 1),
	(3, 2),
	(3, 3),
	(5, 1),
	(5, 2),
	(5, 3),
	(5, 4),
	(5, 5),
	(6, 1),
	(7, 1),
	(7, 2);


insert into programme values
	(1,'','2022/05/04','2022/05/08',1,7),
	(2,'','2022/05/09','2022/05/13',1,9),
	(3,'','2022/05/22','2022/05/27',2,2),
	(4,'','2022/05/16','2022/05/25',3,6),
	(5,'','2022/05/04','2022/05/08',4,1),
	(6,'','2022/05/22','2022/05/27',5,7),
	(7,'','2022/05/16','2022/05/25',6,5),
	(8,'','2022/05/04','2022/05/08',7,8);

