<?php
$tableau_frais = Array();
foreach ($terrains_possedes as $b => $a)
{
    if ($a->activite != '')
    {
        $temps_restant = 10000000000;
		if (count($liste_activites[$a->activite]['consommations']) != 0)
		{
			foreach ($liste_activites[$a->activite]['consommations'] as $d => $c)
			{
				  $temps_restant = min($temps_restant, 86400 * $a->stock[$d]['quantite'] / $a->consommation_produit($d));
			}
		}
		
		$a->temps_restant = $temps_restant;
		
		$tableau_frais['eau'] += $a->prix_eau();
		$tableau_frais['electricite'] += $a->prix_electricite();
		$tableau_frais['salaires'] += $a->prix_salaires();
		$tableau_frais['foncier'] += $a->impots_fonciers();
		$tableau_frais['entretien'] += $a->frais_entretien();
		$frais = $a->prix_eau() + $a->prix_electricite() + $a->prix_salaires() + $a->impots_fonciers() + $a->frais_entretien();
		
		$temps_production = time() - $a->date_derniere_vague;
		$joueur_actuel->frais_eau += $a->prix_eau() / 86400;
		$joueur_actuel->frais_electricite += $a->prix_electricite() / 86400;
		$joueur_actuel->frais_salaires += $a->prix_salaires() / 86400;
		$joueur_actuel->frais_fonciers += $a->impots_fonciers() / 86400;
		$joueur_actuel->frais_entretien += $a->frais_entretien() / 86400;
		$joueur_actuel->argent -= $frais * $temps_production / 86400;
		
		$temps_production_reel = min($temps_restant, $temps_production);
		if ($temps_production_reel > 0)
		{
			$joueur_actuel->points += $liste_activites[$a->activite]['points'] * $temps_production_reel / 86400;
			$a->temps_restant = $temps_restant;
			
			$qualite_totale = 0;
			foreach ($liste_activites[$a->activite]['qualite'] as $d => $c)
			{
				$valeur = 0;
				switch ($d)
				{
				    case 'formation_employes': $valeur = $c; break;
				    case 'RD': $valeur = $a->qualite_produit_rd($d); break;
				    case 'qualite_eau': $valeur = $a->qualite_contrat_eau * $c / 100;; break;
				    case 'sol': $valeur = $a->qualite * $c / 100; break;
				    case 'base': $valeur = $c;
				    default: $valeur = $a->stock[$d]['qualite'] * $c / 100; break;
				}
				$qualite_totale += $valeur;
			}
			$frais_ressources = 0;
			
			$frais_ressources += $a->prix_eau();
			$frais_ressources += $a->prix_electricite();
	       
	        $frais_salaires = ceil($liste_activites[$a->activite]['employes']['nombre'] * $a->taille) * $liste_activites[$a->activite]['employes']['salaire'];
	        $frais_fonciers = round(prix_terrain($a->taille, $a->type, $a->qualite, $a->endroit) / 7300, 2);
		    $frais_materiels = $a->frais_entretien();
	        $frais_totaux = $frais_ressources + $frais_salaires + $frais_fonciers + $frais_materiels;
			
			foreach ($liste_activites[$a->activite]['consommations'] as $d => $c)
			{
				$frais_totaux += $a->consommation_produit($d) * $a->stock[$d]['prix_revient'];
				$a->stock[$d]['quantite'] -= $a->consommation_produit($d) * $temps_production_reel / 86400;
			}
			
			$part_produit = array();
			$part_totale = 0;
			foreach ($liste_activites[$a->activite]['productions'] as $d => $c)
			{
				$part_produit[$d] = $a->production_produit($d) * $temps_production_reel / 86400 * max(0, $infos_produits[$d]['cours']);
				$part_totale += $a->production_produit($d) * $temps_production_reel / 86400 * max(0, $infos_produits[$d]['cours']);
			}
			
			foreach ($liste_activites[$a->activite]['productions'] as $d => $c)
			{
				if ($joueur_actuel->stock[$d]['quantite'] == 0) {$joueur_actuel->stock[$d]['qualite'] = 0; $joueur_actuel->stock[$d]['prix_revient'] = 0;}
				$joueur_actuel->stock[$d]['quantite'] += $a->production_produit($d) * $temps_production_reel / 86400;
				$joueur_actuel->stock[$d]['qualite'] = ($qualite_totale * (($a->production_produit($d)  * $temps_production_reel) / 86400) + $joueur_actuel->stock[$d]['qualite'] * ($joueur_actuel->stock[$d]['quantite'] - ($a->production_produit($d)  * $temps_production_reel) / 86400)) / $joueur_actuel->stock[$d]['quantite'];
				$joueur_actuel->stock[$d]['prix_revient'] = (($frais_totaux * $part_produit[$d] / $part_totale * $temps_production_reel / 86400) + $joueur_actuel->stock[$d]['prix_revient'] * ($joueur_actuel->stock[$d]['quantite'] - ($a->production_produit($d)  * $temps_production_reel) / 86400)) / $joueur_actuel->stock[$d]['quantite'];
			}
		}
		
		$req = $bdd->prepare('UPDATE terrains SET infos = :infos, stock = :stock WHERE id = ' . $a->id);
	    $req->execute(array(
			 'infos' => $a->infos_enregistrer(true),
			 'stock' => $a->stock_enregistrer()
			 ));
    }
}

$nouvelles_infos = 'argent:' . $joueur_actuel->argent;
$nouvelles_infos .= ';points:' . $joueur_actuel->points;
$nouvelles_infos .= ';frais_eau:' . $joueur_actuel->frais_eau;
$nouvelles_infos .= ';frais_electricite:' . $joueur_actuel->frais_electricite;
$nouvelles_infos .= ';frais_salaires:' . $joueur_actuel->frais_salaires;
$nouvelles_infos .= ';frais_fonciers:' . $joueur_actuel->frais_fonciers;
$nouvelles_infos .= ';frais_entretien:' . $joueur_actuel->frais_entretien;

$texte_stock = '';
foreach ($joueur_actuel->stock as $b => $a)
{
    $texte_stock .= $b . ':' . $a['quantite'] . ',' . $a['qualite'] . ',' . $a['prix_revient'] . ';';
}

$texte_stock = substr($texte_stock, 0, -1);

$texte_rd = '';
foreach ($joueur_actuel->rd as $b => $a)
{
    $texte_rd .= $b . ':' . $a . ';';
}
$texte_rd = substr($texte_rd, 0, -1);

echo $pseudo;
$req = $bdd->prepare('UPDATE joueurs SET infos = :infos, stock = :stock, RD = :rd WHERE nom = \'' . $pseudo . '\'');
$req->execute(array(
	'infos' => $nouvelles_infos,
	'stock' => $texte_stock,
	'rd' => $texte_rd
	));
	?>