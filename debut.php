<?php
session_start();
error_reporting(E_ALL & ~(E_NOTICE));
$bdd = new PDO('mysql:host=localhost;dbname=jeu8', 'root', '');
$equipements = $bdd->query("SELECT * FROM equipement");
$joueurs = $bdd->query("SELECT * FROM joueurs");
$pseudo = 'totoleperroquet';

for ($i = 0 ; $temp = $joueurs->fetch() ; $i++) 
{
	$liste_joueurs[$temp['nom']] = new Joueur($temp);
}

$req = $bdd->query("SELECT * FROM terrains");
for ($i = 0 ; $terrain_temp = $req->fetch() ; $i++)
{
    $terrains[$terrain_temp['id']] = new Terrain($terrain_temp);
}

$req = $bdd->query("SELECT * FROM infos_produits");
for ($i = 0 ; $temp = $req->fetch() ; $i++)
{
    $infos_produits[$temp['nom']] = $temp;
}

for ($i = 1 ; $i <= count($terrains) ; $i++)
{
	if ($terrains[$i]->possesseur == $pseudo)
	{
		$terrains_possedes[$terrains[$i]->id] = &$terrains[$i];
	}
}

include ("donnees_activites.php");
include ("donnees_recherches.php");

class Joueur
{
    var $id;
    var $nom;
    var $argent;
    var $points;
    var $stock;
	var $rd;
    
    public function __construct ($tableau)
	{
	    $this->id = $tableau['id'];
	    $this->nom = $tableau['nom'];
	    $tableau_temp = explode(';', $tableau['infos']);
	    foreach ($tableau_temp as $b => $a)
	    {
	    	$paire = explode(':', $a);
		    $infos[$paire[0]] = $paire[1];
	    }
	    
	    $stock_temp = explode(';', $tableau['stock']);
	    foreach ($stock_temp as $b => $a)
	    {
					$paire = explode(':', $a);
					$paire[1] = explode(',', $paire[1]);
					$this->stock[$paire[0]]['quantite'] = $paire[1][0];
					$this->stock[$paire[0]]['qualite'] = $paire[1][1];
					$this->stock[$paire[0]]['prix_revient'] = $paire[1][2];
	    }
	    
	    $this->argent = $infos['argent'];
	    $this->points = $infos['points'];
		$this->frais_eau = $infos['frais_eau'];
	    $this->frais_electricite = $infos['frais_electricite'];
		$this->frais_salaires = $infos['frais_salaires'];
	    $this->frais_fonciers = $infos['frais_fonciers'];
		$this->frais_entretien = $infos['frais_entretien'];
		
			$rd_temp = explode(';', $tableau['RD']);
	    foreach ($rd_temp as $b => $a)
	    {
				 $paire = explode(':', $a);
				 $this->rd[$paire[0]] = $paire[1];
			}
	}
}

class Terrain
{
    var $id;
    var $possesseur;
    var $taille;
    var $type;
    var $qualite;
    var $endroit;
    var $activite;
    var $temps_restant;
    var $stock;
    var $date_derniere_vague;
    var $qualite_contrat_eau;
    
    public function __construct ($tableau)
	{
	    $this->id = $tableau['id'];
	    $this->possesseur = $tableau['possesseur'];
	    $this->type = $tableau['type'];
	    $tableau_temp = explode(';', $tableau['infos']);
	    foreach ($tableau_temp as $b => $a)
	    {
	    	$paire = explode(':', $a);
		    $infos[$paire[0]] = $paire[1];
	    }
	    
	    $stock_temp = explode(';', $tableau['stock']);
	    foreach ($stock_temp as $b => $a)
	    {
	    	$paire = explode(':', $a);
		    $paire[1] = explode(',', $paire[1]);
		    $this->stock[$paire[0]]['quantite'] = $paire[1][0];
		    $this->stock[$paire[0]]['qualite'] = $paire[1][1];
		    $this->stock[$paire[0]]['prix_revient'] = $paire[1][2];
	    }
	    
	    $this->taille = $infos['taille'];
	    $this->qualite = $infos['qualite'];
	    $this->endroit = $infos['endroit'];
	    $this->temps_restant = $infos['temps_restant'];
	    $this->activite = $tableau['activite']; 
	    $this->date_derniere_vague = $infos['date_derniere_vague']; 
	    $this->qualite_contrat_eau = $infos['qualite_contrat_eau']; 
	}
	
	public function prix ()
	{
	    return prix_terrain($this->taille, $this->type, $this->qualite, $this->endroit);
	}
	
	public function stock_enregistrer ()
	{
		$texte_stock = '';
		foreach ($this->stock as $b => $a)
		{
		    if ($b != '' && $a['quantite'] != '')
		    {
				$texte_stock .= $b . ':' . $a['quantite'] . ',' . floatval($a['qualite']) . ',' . floatval($a['prix_revient']) . ';';
		    }
		}
		$texte_stock = substr($texte_stock, 0, -1);
		return $texte_stock;
	}
	
	public function infos_enregistrer ($production = false)
	{
		$texte_infos = 'taille:' . $this->taille . ';';
		$texte_infos .= 'qualite:' . $this->qualite . ';';
		$texte_infos .= 'endroit:' . $this->endroit . ';';
		$texte_infos .= 'temps_restant:' . $this->temps_restant . ';';
		
		if ($production)
		{
			$texte_infos .= 'date_derniere_vague:' . time() . ';';
		}
		
		else
		{
			$texte_infos .= 'date_derniere_vague:' . $this->date_derniere_vague . ';';
		}
		$texte_infos .= 'qualite_contrat_eau:' . $this->qualite_contrat_eau;
		
		return $texte_infos;
	}
	
	public function prix_electricite ()
	{
		global $liste_activites;
		return $liste_activites[$this->activite]['ressources']['electricite'] * $this->taille * $liste_activites[$this->activite]['terrains'][$this->type] * 0.065;
	}
	
	public function quantite_eau ()
	{
		global $liste_activites;
		return $liste_activites[$this->activite]['ressources']['eau'] * $liste_activites[$this->activite]['terrains'][$this->type] * $this->taille;
	}
	
	public function prix_eau ()
	{
		global $liste_activites;
		return $this->quantite_eau() * pow(2, ($this->qualite_contrat_eau - 50) / 50) * 0.0025;
	}
	
	public function prix_salaires ()
	{
			global $liste_activites;
			return ceil($liste_activites[$this->activite]['employes']['nombre'] * $this->taille) * $liste_activites[$this->activite]['employes']['salaire'];
	}
	
	public function impots_fonciers ()
	{
			global $liste_activites;
			return round(prix_terrain($this->taille, $this->type, $this->qualite, $this->endroit) / 365 * 0.05, 2);
	}
	
	public function production_produit ($produit)
	{
		global $liste_activites;
		global $joueur_actuel;
		return $liste_activites[$this->activite]['productions'][$produit] * $this->taille * $liste_activites[$this->activite]['terrains'][$this->type] * (1 + 0.0075 * $joueur_actuel->rd['productivite']);
	}
	
	public function consommation_produit ($produit)
	{
		global $liste_activites;
		global $joueur_actuel;
		return $liste_activites[$this->activite]['consommations'][$produit] * $this->taille * $liste_activites[$this->activite]['terrains'][$this->type] * (1 + 0.0075 * $joueur_actuel->rd['productivite']);
	}
	
	public function qualite_produit_rd ($produit)
	{
		global $liste_activites;
		global $joueur_actuel;
		$part_commune = $liste_activites[$this->activite]['qualite']['RD'] * 0.25 * $joueur_actuel->rd['qualite'] / 20;
		$total = $part_commune;
		return $part_commune;
	}
	
	public function frais_entretien ()
	{
		global $liste_activites;
		global $joueur_actuel;
		$frais_entretien = $liste_activites[$this->activite]['frais_entretien'] * $this->taille * exp(-0.0075 * $joueur_actuel->rd['entretien']);
		return $frais_entretien;
	}
}

$joueur_actuel = $liste_joueurs[$pseudo];

function prix_terrain ($taille, $type, $qualite, $endroit)
{
    $coef_type = 0; $coef_2 = 0;
       
       switch ($type)
       {
           case 'nu':
	       $coef_type = 5000; $coef_2 = (75 + $qualite) * (75 + $qualite) / 15625;
	       break;
	       
	       case 'foret':
	       $coef_type = 6000; $coef_2 = (75 + $qualite) * (75 + $qualite) / 15625;
	       break;
	       
	       case 'champ':
	       $coef_type = 7000; $coef_2 = (75 + $qualite) * (75 + $qualite) / 15625;
	       break;
	       
	       case 'ferme':
	       $coef_type = 8000; $coef_2 = (75 + $qualite) * (75 + $qualite) / 15625;
	       break;
	       
	       case 'elevage':
	       $coef_type = 10000; $coef_2 = (75 + $qualite) * (75 + $qualite) / 15625;
	       break;
	       
	       case 'fleuve':
	       $coef_type = 25000; $coef_2 = (75 + $qualite) * (75 + $qualite) / 15625;
	       break;
		   
		     case 'pf':
	       $coef_type = 2500000; $coef_2 = (75 + $qualite) * (75 + $qualite) / 15625;
	       break;
	       
	       case 'industriel':
	       $coef_type = 50000;
	       switch ($endroit)
	       {
	           case 'peripherie': $coef_2 = 1; break;
		       case 'banlieue': $coef_2 = 2; break;
		       case 'centre-ville': $coef_2 = 4; break;
		       case 'quartier-affaires': $coef_2 = 8; break;
	       }
	       break;
	       
	       case 'bureaux':
	       $coef_type = 100000;
	       switch ($endroit)
	       {
	           case 'peripherie': $coef_2 = 1; break;
		       case 'banlieue': $coef_2 = 2; break;
		       case 'centre-ville': $coef_2 = 4; break;
		       case 'quartier-affaires': $coef_2 = 8; break;
	       }
	       break;
       }
    return $taille * $coef_type * $coef_2;
}

function lire_infos_terrain ($tableau)
{
    $tableau_temp = explode(';', $tableau);
    foreach ($tableau_temp as $b => $a)
    {
        $paire = explode(':', $a);
	    $infos[$paire[0]] = $paire[1];
    }
    return $infos;
}

function texte_terrain ($terrain, $action)
{
    global $joueur_actuel;
    $texte = '';
    $texte .=  '<tr><td>' . $terrain->id . '</td>';
    $texte .=  '<td>' . $terrain->taille . ' ha</td>';
    $texte .=  '<td>' . $terrain->type . '</td>';
    if ($terrain->type != 'industriel' && $terrain->type != 'bureaux') {$texte .=  '<td>' . $terrain->qualite . '</td>';}
    else {$texte .= '<td style = "background-color: rgb(128, 128, 128);"></td>';}
    if ($terrain->type == 'industriel' || $terrain->type == 'bureaux') {$texte .=  '<td>' . $terrain->endroit . '</td>';}
    else {$texte .= '<td style = "background-color: rgb(128, 128, 128);"></td>';}
    $texte .= '<td>' . number_format(prix_terrain($terrain->taille, $terrain->type, $terrain->qualite, $terrain->endroit)) . ' €</td>';
    
    if ($action == 'acheter')
    {
	    if ($joueur_actuel->argent >= prix_terrain($terrain->taille, $terrain->type, $terrain->qualite, $terrain->endroit))
	    {
		$texte .= '<td>' . '<a href = "?acheter_terrain_no=' . $terrain->id . '">Acheter ce terrain</a></td>';
	    }
	    
	    else
	    {
		$texte .= '<td>' . 'Désolé vous êtes trop pauvre' . '</td>';
	    }
	}
	 
	if ($action == 'voir')
    {
		$valeur_couleur = 180 - 180 * exp(-pow($terrain->temps_restant / 86400, 0.4) / 5);
		$texte .= '<td style = "' . teinte_vers_rgb($valeur_couleur) . '">' . format_duree($terrain->temps_restant) . '</td>';
		$texte .= '<td>' . '<a href = "?voir_terrain_no=' .$terrain->id . '">Voir ce terrain</a></td>';
    }    
    
    
    $texte .= '</tr>';
    
    if ($terrain->id >= 1)
    {
		return $texte;
	}
} 

function format_duree ($valeur)
{
	if ($valeur < 60)
	{
		$valeur_arrondie = round($valeur);
		return $valeur_arrondie . ' s';
	}
	
	else if ($valeur < 3600)
	{
		$valeur_arrondie = round($valeur);
		return intval($valeur_arrondie / 60) . ' m ' . ($valeur_arrondie % 60) . ' s';
	}
	
	else if ($valeur < 86400)
	{
		$valeur_arrondie = round($valeur);
		return intval($valeur_arrondie / 3600) . ' h ' . intval(($valeur_arrondie % 3600) / 60) . ' m';
	}
	
	else
	{
		$valeur_arrondie = round($valeur);
		return intval($valeur_arrondie / 86400) . ' j ' . intval(($valeur_arrondie % 86400) / 3600) . ' h';
	}
}

function teinte_vers_rgb($teinte)
{
	if ($teinte <= 60)
	{
		return 'background-color:rgb(255,' . round($teinte * 255 / 60) . ',0);';
	}
	
	else if ($teinte <= 120)
	{
		return 'background-color:rgb(' . round((120 - $teinte) * 255 / 60) . ',255,0);';
	}
	
	else if ($teinte <= 180)
	{
		return 'background-color:rgb(0, 255, ' . round(($teinte - 120) * 255 / 60) . ');';
	}
	
	else
	{
	
	}
}

function ecriture_stock ($x, $n)
{
	if ($x == 0) {return 0;}
	$puiss = floor(log10($x));
	$nb = round($x / pow(10, $puiss), $n);
	return '' . $nb . ' x 10<sup>' . $puiss . '</sup>';
}

function round_adapte ($x, $n)
{
	return round($x, $n - log10($x));
}
?>