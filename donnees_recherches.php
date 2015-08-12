<?php
$recherches = array();
$recherches['basique']['nom'] = 'Recherche de base';
$recherches['basique']['point_de_recherche_basique'] = array(2, 3, 2, 0); //Prix(x) = 2 + 3x + 2x�
$recherches['basique']['limite'] = 20;

$recherches['productivite']['nom'] = 'Recherche de productivit�'; //Augmente la productivit� de 1% par niveau
$recherches['productivite']['point_de_recherche_basique'] = array(5, 3, 1, 0);
$recherches['productivite']['limite'] = -1;
$recherches['productivite']['dependances'] = array('basique' => 1);

$recherches['qualite']['nom'] = 'Recherche de qualit�'; //Augmente la qualit� des produits de 1.25% par niveau sur la base RD (25% de la qualit� RD)
$recherches['qualite']['point_de_recherche_basique'] = array(4, 4, 2, 1);
$recherches['qualite']['limite'] = 20;
$recherches['qualite']['dependances'] = array('basique' => 1);

$recherches['entretien']['nom'] = 'Recherche d\'entretien'; //Diminue les frais mat�riels de 1%
$recherches['entretien']['point_de_recherche_basique'] = array(4, 5, 0, 0);
$recherches['entretien']['limite'] = -1;
$recherches['entretien']['dependances'] = array('basique' => 1);

?>