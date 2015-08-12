<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style type="text/css">
body {
	margin: 0px;
	/*background-color: #9FC9EB;
	font-family:"Tahoma", "Verdana", "Times New Roman", Times, serif;
	font-size: 11px;
	color: #58595e;
	background-color: #265AA2;
	top: 80px;
}
</style>
</head>
<?php
$orpaillage_or = array();
$orpaillage_or['nom'] = 'Orpaillage (or)';
$orpaillage_or['type'] = 'materiaux';
$orpaillage_or['consommations'] = array();
$orpaillage_or['employes'] = array('nombre' => 15, 'salaire' => 85);
$orpaillage_or['ressources'] = array('eau' => 0, 'electricite' => 200);
$orpaillage_or['frais_entretien'] = 50;
$orpaillage_or['productions'] = array(
	'or' => 120, 
	'cuivre' => 0.3, 
	'fer' => 0.8,
	'sable' => 0.018,
	'déchets_bruts' => 0.01,
	'déchets_matériaux' => 0.005);
$orpaillage_or['bonus'] = array(
	'minerais_chimique' => array(
		0.003, 
		'production' => 1.4, 
		'dechets' => 2.5, 
		'pollution' => 3),		
	'mercure' => array(
		0.5, 
		'production' => 1.5, 
		'dechets' => 6, 
		'pollution' => 21)
);
$orpaillage_or['terrains'] = array('fleuve' => 1);
$orpaillage_or['points'] = 0.002;
$orpaillage_or['qualite'] = array('sol' => 70, 'formation_employes' => 20, 'RD' => 10);

$orpaillage_divers = array();
$orpaillage_divers['nom'] = 'Orpaillage (divers)';
$orpaillage_divers['type'] = 'materiaux';
$orpaillage_divers['consommations'] = array();
$orpaillage_divers['employes'] = array('nombre' => 15, 'salaire' => 85);
$orpaillage_divers['ressources'] = array('eau' => 0, 'electricite' => 200);
$orpaillage_divers['frais_entretien'] = 50;
$orpaillage_divers['productions'] = array(
	'or' => 70, 
	'cuivre' => 5, 
	'fer' => 12, 
	'sable' => 0.05,
	'déchets_bruts' => 0.005,
	'déchets_matériaux' => 0.005);
$orpaillage_divers['bonus'] =array(
	'minerais_chimique' => array(
		0.003, 
		'production' => 1.2, 
		'dechets' => 1.6, 
		'pollution' => 2),		
	'mercure' => array(
		0.25, 
		'production' => 1.25, 
		'dechets' => 3, 
		'pollution' => 11)
);
$orpaillage_divers['terrains'] = array('fleuve' => 1);
$orpaillage_divers['points'] = 0.002;
$orpaillage_divers['qualite'] = array('sol' => 70, 'formation_employes' => 20, 'RD' => 10);

$filtration_eau = array();
$filtration_eau['nom'] = "Filtration d'eau (eau)";
$filtration_eau['type'] = 'materiaux';
$filtration_eau['consommations'] = array();
$filtration_eau['employes'] = array('nombre' => 4, 'salaire' => 90);
$filtration_eau['ressources'] = array('eau' => 0, 'electricite' => 225);
$filtration_eau['frais_entretien'] = 55;
$filtration_eau['productions'] = array(
	'eau' => 400,
	'déchets_bruts' => 0.2,
);
$filtration_eau['terrains'] = array('fleuve' => 1);
$filtration_eau['points'] = 0.003;
$filtration_eau['qualite'] = array('sol' => 65, 'formation_employes' => 15, 'RD' => 20);

$filtration = array();
$filtration['nom'] = "Filtration d'eau (collecte des mineraux)";
$filtration['type'] = 'materiaux';
$filtration['consommations'] = array();
$filtration['employes'] = array('nombre' => 4.5, 'salaire' => 90);
$filtration['ressources'] = array('eau' => 0, 'electricite' => 350);
$filtration['frais_entretien'] = 55;
$filtration['productions'] = array(
	'eau' => 125, 
	'sel' => 1,
	'minerais_chimique' => 0.14,
	'fer' => 2.75,
	'cuivre' => 2.75,
	'uranium' => 0.024,
	'déchets_bruts' => 0.2,
);
$filtration['terrains'] = array('fleuve' => 1);
$filtration['points'] = 0.003;
$filtration['qualite'] = array('sol' => 65, 'formation_employes' => 15, 'RD' => 20);

$repos = array();
$repos['nom'] = 'Repos';
$repos['type'] = 'agriculture';
$repos['consommations'] = array();
$repos['employes'] = array('nombre' => 0, 'salaire' => 0);
$repos['ressources'] = array('eau' => 0, 'electricite' => 0);
$repos['frais_entretien'] = 0.25;
$repos['productions'] = array(
	'graines' => 0.1,
	'déchets_bruts' => 0.00001,
	'déchets_alimentaires' => 0.00001
);
$repos['terrains'] = array('nu' => 0.4, 'champ' => 1, 'ferme' => 0.8, 'elevage' => 0.5);
$repos['points'] = 0.001;
$repos['qualite'] = array('sol' => 100);

$culture_ble = array();
$culture_ble['nom'] = 'Grandes cultures (blé)';
$culture_ble['type'] = 'agriculture';
$culture_ble['consommations'] = array('graines' => 0.15);
$culture_ble['employes'] = array('nombre' => 0.12, 'salaire' => 75);
$culture_ble['ressources'] = array('eau' => 4000, 'electricite' => 50);
$culture_ble['frais_entretien'] = 5;
$culture_ble['productions'] = array(
	'graines' => 0.01,
	'blé' => 0.5,
	'paille' => 0.012,
	'déchets_bruts' => 0.0017,
	'déchets_alimentaires' => 0.02
);
$culture_ble['bonus'] = array(
	'engrais' => array(
		0.00012, 
		'production' => 1.3, 
		'dechets' => 1.2, 
		'pollution' => 1.4),		
	'fumier' => array(
		0.00006, 
		'production' => 1.2, 
		'dechets' => 1.3, 
		'pollution' => 1),	
);
$culture_ble['terrains'] = array('nu' => 0.2, 'champ' => 1, 'ferme' => 0.5, 'elevage' => 0.3);
$culture_ble['points'] = 0.005;
$culture_ble['qualite'] = array('sol' => 50, 'graines' => 25, 'qualite_eau' => 15, 'formation_employes' => 5, 'RD' => 5);

$culture_fraises['nom'] = 'Cultures (fraises)';
$culture_fraises['type'] = 'agriculture';
$culture_fraises['consommations'] = array('graines' => 0.02);
$culture_fraises['employes'] = array('nombre' => 0.16, 'salaire' => 75);
$culture_fraises['ressources'] = array('eau' => 2500, 'electricite' => 10);
$culture_fraises['frais_entretien'] = 5;
$culture_fraises['productions'] = array(
	'graines' => 0.008,
	'fraise' => 80,
	'déchets_bruts' => 0.0006,
	'déchets_alimentaires' => 0.0021
);
$culture_fraises['bonus'] = array(
	'engrais' => array(
		0.00012, 
		'production' => 1.3, 
		'dechets' => 1.2, 
		'pollution' => 1.4),		
	'fumier' => array(
		0.00006, 
		'production' => 1.2, 
		'dechets' => 1.3, 
		'pollution' => 1),	
);
$culture_fraises['terrains'] = array('nu' => 0.2, 'champ' => 1, 'ferme' => 1, 'elevage' => 0.3);
$culture_fraises['points'] = 0.005;
$culture_fraises['qualite'] = array('sol' => 55, 'graines' => 20, 'qualite_eau' => 15, 'formation_employes' => 5, 'RD' => 5);

$culture_tomates['nom'] = 'Cultures (tomates)';
$culture_tomates['type'] = 'agriculture';
$culture_tomates['consommations'] = array('graines' => 0.01);
$culture_tomates['employes'] = array('nombre' => 0.15, 'salaire' => 75);
$culture_tomates['ressources'] = array('eau' => 2500, 'electricite' => 10);
$culture_tomates['frais_entretien'] = 5;
$culture_tomates['productions'] = array(
	'graines' => 0.002,
	'tomate' => 100,
	'déchets_bruts' => 0.0014,
	'déchets_alimentaires' => 0.0081
);
$culture_tomates['bonus'] = array(
	'engrais' => array(
		0.00012, 
		'production' => 1.3, 
		'dechets' => 1.2, 
		'pollution' => 1.4),		
	'fumier' => array(
		0.00006, 
		'production' => 1.2, 
		'dechets' => 1.3, 
		'pollution' => 1),	
);
$culture_tomates['terrains'] = array('nu' => 0.2, 'champ' => 1, 'ferme' => 1, 'elevage' => 0.3);
$culture_tomates['points'] = 0.005;
$culture_tomates['qualite'] = array('sol' => 55, 'graines' => 20, 'qualite_eau' => 15, 'formation_employes' => 5, 'RD' => 5);

$sylviculture = array();
$sylviculture['nom'] = "Sylviculture";
$sylviculture['type'] = 'materiaux';
$sylviculture['consommations'] = array();
$sylviculture['employes'] = array('nombre' => 0.9, 'salaire' => 80);
$sylviculture['ressources'] = array('eau' => 600, 'electricite' => 200);
$sylviculture['frais_entretien'] = 20;
$sylviculture['productions'] = array(
	'bois' => 750,
	'déchets_bruts' => 0.005,
	'déchets_matériaux' => 0.025
);
$sylviculture['terrains'] = array('foret' => 1);
$sylviculture['points'] = 0.004;
$sylviculture['qualite'] = array('sol' => 60, 'formation_employes' => 30, 'RD' => 10);

$papeterie = array();
$papeterie['nom'] = "Papeterie";
$papeterie['type'] = 'materiaux';
$papeterie['employes'] = array('nombre' => 5, 'salaire' => 80);
$papeterie['ressources'] = array('eau' => 18000, 'electricite' => 400);
$papeterie['consommations'] = array('bois' => 6000);
$papeterie['frais_entretien'] = 175;
$papeterie['productions'] = array(
	'papier' => 6000,
	'déchets_bruts' => 0.022,
	'déchets_matériaux' => 0.1,
	'déchets_industriels' => 0.1
);
$papeterie['terrains'] = array('nu' => 0.4, 'industriel' => 1);
$papeterie['points'] = 0.025;
$papeterie['qualite'] = array('bois' => 45, 'qualite_eau' => 10, 'formation_employes' => 20, 'RD' => 25);

$moulin = array();
$moulin['nom'] = "Moulin (farine de blé)";
$moulin['type'] = 'agroalimentaire';
$moulin['employes'] = array('nombre' => 3, 'salaire' => 90);
$moulin['ressources'] = array('eau' => 2000, 'electricite' => 100);
$moulin['consommations'] = array('blé' => 9, 'papier' => 135);
$moulin['frais_entretien'] = 125;
$moulin['productions'] = array(
	'farine_de_blé' => 9000,
	'déchets_bruts' => 0.007,
	'déchets_matériaux' => 0.01,
	'déchets_industriels' => 0.05,
	'déchets_agroalimentaires' => 0.15
);
$moulin['terrains'] = array('nu' => 0.75, 'industriel' => 1);
$moulin['points'] = 0.03;
$moulin['qualite'] = array('blé' => 70, 'papier' => 10, 'qualite_eau' => 2, 'formation_employes' => 10, 'RD' => 8);

$sel_paquet = array();
$sel_paquet['nom'] = "Sel en paquet";
$sel_paquet['type'] = 'agroalimentaire';
$sel_paquet['employes'] = array('nombre' => 3, 'salaire' => 90);
$sel_paquet['ressources'] = array('eau' => 1100, 'electricite' => 180);
$sel_paquet['consommations'] = array('sel' => 5.25, 'papier' => 140);
$sel_paquet['frais_entretien'] = 100;
$sel_paquet['productions'] = array(
	'sel_en_paquet' => 7000,
	'déchets_bruts' => 0.01,
	'déchets_matériaux' => 0.02,
	'déchets_industriels' => 0.05,
);
$sel_paquet['terrains'] = array('nu' => 0.4, 'industriel' => 1);
$sel_paquet['points'] = 0.03;
$sel_paquet['qualite'] = array('sel' => 65, 'papier' => 10, 'qualite_eau' => 1, 'formation_employes' => 4, 'RD' => 20);

$eau_bouteille = array();
$eau_bouteille['nom'] = "Eau en bouteille";
$eau_bouteille['type'] = 'agroalimentaire';
$eau_bouteille['employes'] = array('nombre' => 3, 'salaire' => 90);
$eau_bouteille['ressources'] = array('eau' => 500, 'electricite' => 250);
$eau_bouteille['consommations'] = array('eau' => 45, 'plastique' => 0.6);
$eau_bouteille['frais_entretien'] = 100;
$eau_bouteille['productions'] = array(
	'bouteille_d\'eau' => 30000,
	'déchets_bruts' => 0.05,
	'déchets_matériaux' => 0.05,
	'déchets_industriels' => 0.1,
);
$eau_bouteille['terrains'] = array('industriel' => 1);
$eau_bouteille['points'] = 0.03;
$eau_bouteille['qualite'] = array('eau' => 80, 'plastique' => 10, 'qualite_eau' => 1, 'formation_employes' => 4, 'RD' => 5);

$recherche_normale = array();
$recherche_normale['nom'] = "Recherche (normale)";
$recherche_normale['type'] = 'recherche';
$recherche_normale['employes'] = array('nombre' => 30, 'salaire' => 200);
$recherche_normale['ressources'] = array('eau' => 10000, 'electricite' => 2000);
$recherche_normale['consommations'] = array('minerais_chimique' => 0.15, 'or' => 30, 'papier' => 40);
$recherche_normale['frais_entretien'] = 1750;
$recherche_normale['productions'] = array(
	'point_de_recherche_basique' => 100
);
$recherche_normale['terrains'] = array('bureaux' => 1);
$recherche_normale['points'] = 0.2;
$recherche_normale['qualite'] = array('minerais_chimique' => 10, 'or' => 10, 'papier' => 2, 'qualite_eau' => 3, 'formation_employes' => 50, 'RD' => 25);

$exploitation_petrole = array();
$exploitation_petrole['nom'] = "Exploitation de pétrole";
$exploitation_petrole['type'] = 'materiaux';
$exploitation_petrole['consommations'] = array();
$exploitation_petrole['employes'] = array('nombre' => 200, 'salaire' => 80);
$exploitation_petrole['ressources'] = array('eau' => 10000, 'electricite' => 60000);
$exploitation_petrole['frais_entretien'] = 20000;
$exploitation_petrole['productions'] = array(
	'pétrole' => 2750,
	'déchets_bruts' => 0.05,
	'déchets_matériaux' => 0.25
);
$exploitation_petrole['terrains'] = array('pf' => 1);
$exploitation_petrole['points'] = 1;
$exploitation_petrole['qualite'] = array('sol' => 60, 'formation_employes' => 10, 'RD' => 30);

$plastique = array();
$plastique['nom'] = "Plastique";
$plastique['type'] = 'materiaux';
$plastique['employes'] = array('nombre' => 5, 'salaire' => 80);
$plastique['ressources'] = array('eau' => 1000, 'electricite' => 1500);
$plastique['consommations'] = array('pétrole' => 100);
$plastique['frais_entretien'] = 250;
$plastique['productions'] = array(
	'plastique' => 14000,
	'déchets_bruts' => 0.2,
	'déchets_matériaux' => 0.5,
	'déchets_industriels' => 0.75
);
$plastique['terrains'] = array('industriel' => 1);
$plastique['points'] = 0.04;
$plastique['qualite'] = array('pétrole' => 59, 'qualite_eau' => 1, 'formation_employes' => 10, 'RD' => 30);

$recyclage_materiaux = array();
$recyclage_materiaux['nom'] = "Recyclage de matériaux";
$recyclage_materiaux['type'] = 'materiaux';
$recyclage_materiaux['employes'] = array('nombre' => 9, 'salaire' => 85);
$recyclage_materiaux['ressources'] = array('eau' => 1500, 'electricite' => 6000);
$recyclage_materiaux['consommations'] = array('déchets_matériaux' => 2);
$recyclage_materiaux['frais_entretien'] = 500;
$recyclage_materiaux['productions'] = array(
	'acier' => 0.35,
	'plastique' => 0.35,
	'carton' => 0.3,
	'papier' => 0.2,
	'aluminium' => 0.15
);
$recyclage_materiaux['terrains'] = array('industriel' => 1);
$recyclage_materiaux['points'] = 0.08;
$recyclage_materiaux['qualite'] = array('déchets_matériaux' => 50, 'qualite_eau' => 5, 'formation_employes' => 20, 'RD' => 25);


$liste_activites_temp = array($repos, $culture_ble, $culture_fraises, $culture_tomates,
                          $filtration_eau, $filtration, $orpaillage_or, $orpaillage_divers,
			              $sylviculture, $exploitation_petrole,
						  $papeterie, $plastique,
						  $moulin, $sel_paquet, $eau_bouteille,
						  $recherche_normale,
						  $recyclage_materiaux);
			  
foreach ($liste_activites_temp as $b => $a)
{
    $liste_activites[$a['nom']] = $a;
}
?>