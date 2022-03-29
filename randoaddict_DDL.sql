
create table EXCURSION(
	ID numeric NOT NULL primary key,
	Nom varchar(20) NOT NULL,
	Description varchar(254) NOT NULL,
	Tarif numeric NOT NULL,
	Capacite numeric NOT NULL,
	Difficulte numeric NOT NULL,
	Terrain ENUM('foret', 'marais', 'montagne', 'urbain', 'mer', 'desert') NOT NULL,
	Lieu_Debut varchar(50) NOT NULL,
	Lieu_Fin varchar(50) NOT NULL
)Engine=InnoDB;


create table MARCHEUR(
	ID numeric NOT NULL primary key,
	Nom varchar(20) NOT NULL,
	Prenom varchar(20) NOT NULL,
	Age numeric NOT NULL,
	Numero varchar(10) NOT NULL
)Engine=InnoDB;


create table PROGRAMME(
	ID numeric NOT NULL primary key,
	Description varchar(254),
	Date_Debut date NOT NULL,
	Date_Fin date NOT NULL,
	ID_Excursion numeric NOT NULL,
	ID_Guide numeric NOT NULL,
	foreign key (ID_Excursion) references EXCURSION(ID) on update cascade,
	foreign key (ID_Guide) references MARCHEUR(ID) on update cascade
)Engine=InnoDB;


create table RESERVATION(
	ID_Marcheur numeric NOT NULL,
	ID_Programme numeric NOT NULL,
	foreign key (ID_Marcheur) references MARCHEUR(ID) on update cascade,
	foreign key (ID_Programme) references PROGRAMME(ID) on update cascade,
	constraint PK_Reservation primary key (ID_Marcheur, ID_Programme)
)Engine=InnoDB;