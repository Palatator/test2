<?php
include ('debut.php');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
   <head>
       <title>Stock</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
       <link rel="stylesheet" media="screen" type="text/css" title="Vert et bleu" href="css.css" />
	   <script type="text/javascript" src="includes/overlib.js"></script>
   </head>
   <body>
   <script type = "text/javascript">
   function actualiser_prix()
   {
       var taille = document.getElementById('taille').value;
       taille = parseFloat(taille);
       var type = document.getElementById('type');
       type = type.options[type.selectedIndex].value;
       var qualite = document.getElementById('qualite').value;
       qualite = parseFloat(qualite);
       var endroit = document.getElementById('endroit');
       endroit = endroit.options[endroit.selectedIndex].value;
       var coef_type = 0;
       var coef_2 = 0;
       
       switch (type)
       {
          case 'nu':
	       coef_type = 5000; coef_2 = (75 + qualite) * (75 + qualite) / 15625;
	       break;
	       
	       case 'foret':
	       coef_type = 6000; coef_2 = (75 + qualite) * (75 + qualite) / 15625;
	       break;
	       
	       case 'champ':
	       coef_type = 7000; coef_2 = (75 + qualite) * (75 + qualite) / 15625;
	       break;
	       
	       case 'ferme':
	       coef_type = 8000; coef_2 = (75 + qualite) * (75 + qualite) / 15625;
	       break;
	       
	       case 'elevage':
	       coef_type = 10000; coef_2 = (75 + qualite) * (75 + qualite) / 15625;
	       break;
	       
	       case 'fleuve':
	       coef_type = 25000; coef_2 = (75 + qualite) * (75 + qualite) / 15625;
	       break;
		   
				 case 'pf':
	       coef_type = 2500000; coef_2 = (75 + qualite) * (75 + qualite) / 15625;
	       break;
	       
	       case 'industriel':
	       coef_type = 50000;
	       switch (endroit)
	       {
	           case 'peripherie': coef_2 = 1; break;
		       case 'banlieue': coef_2 = 2; break;
		       case 'centre-ville': coef_2 = 4; break;
		       case 'quartier-affaires': coef_2 = 8; break;
	       }
	       break;
	       
	       case 'bureaux':
	       coef_type = 100000;
	       switch (endroit)
	       {
	           case 'peripherie': coef_2 = 1; break;
		       case 'banlieue': coef_2 = 2; break;
		       case 'centre-ville': coef_2 = 4; break;
		       case 'quartier-affaires': coef_2 = 8; break;
	       }
	       break;
       }
       
       var prix_final = taille * coef_type * coef_2;
       prix_final = Math.round(prix_final);
       var champ_prix = document.getElementById('champ_prix');
       champ_prix.value = "Prix: " + prix_final;
   }
   
   function prix_eau()
   {
       var base = document.getElementById('coef_base_eau').value;
       base = parseFloat(base);
       var qualite = document.getElementById('nouvelle_qualite_eau').value;
       qualite = parseFloat(qualite);
       
       var prix_final = base * Math.pow(2, (qualite - 50) / 50);
       prix_final = Math.round(prix_final);
       var champ_prix_eau = document.getElementById('champ_prix_eau');
       champ_prix_eau.value = "Prix: " + prix_final;
   }
   </script>
   <?php include ('menu.php');?>
   <center>
   <?php
    if (isset($_GET['acheter_terrain_no']))
    {
        $terrain_achete = $terrains[$_GET['acheter_terrain_no']];
        $joueur_actuel->argent -= $terrain_achete->prix();
        $terrain_achete->possesseur = $pseudo;
        $req = $bdd->prepare('UPDATE terrains SET possesseur = :possesseur WHERE id = ' . $_GET['acheter_terrain_no']);
	    $req->execute(array(
	 		 'possesseur' => $terrain_achete->possesseur
	 		 ));
			 
		$texte_details = 'Achat du terrain ' . $_GET['acheter_terrain_no'];
	    $req = $bdd->prepare('INSERT INTO operations(type, valeur, details, joueur) VALUES(:type, :valeur, :details, :pseudo)');
	    $req->execute(array(
			 'type' => 'achat_terrain',
			 'valeur' => $terrain_achete->prix(),
			 'details' => $texte_details,
			 'pseudo' => $pseudo
			 ));
		$joueur_actuel->points += $terrain_achete->prix() / 1000000;
			
	    echo 'Vous avez bien acheté votre terrain';
   }
   
    if (isset($_POST['selection_activite']))
	{
	   $nouvelle_activite = $_POST['selection_activite'];
	   $terrains[$_GET['voir_terrain_no']]->activite = $nouvelle_activite;
	   $req = $bdd->prepare('UPDATE terrains SET activite = :activite, infos = :infos WHERE id = ' . $_GET['voir_terrain_no']);
	   $terrains[$_GET['voir_terrain_no']]->date_derniere_vague = time();
	   $req->execute(array(
			'activite' => $nouvelle_activite,
			'infos' => $terrains[$_GET['voir_terrain_no']]->infos_enregistrer()
			));
	    echo "L'activité a bien étée modifiée";
	}
	
	if (isset($_POST['importation_produit']))
	{
	    $quantite = $_POST['quantite_importer'];
	    if ($quantite > $joueur_actuel->stock[$_POST['importation_produit']]['quantite'])
	    {
			echo "a";
	    }
	    
	    else
	    {
	        $joueur_actuel->stock[$_POST['importation_produit']]['quantite'] -= $quantite;
	    	$terrains[$_GET['voir_terrain_no']]->stock[$_POST['importation_produit']]['quantite'] += $quantite;
			$terrains[$_GET['voir_terrain_no']]->stock[$_POST['importation_produit']]['qualite'] = ($terrains[$_GET['voir_terrain_no']]->stock[$_POST['importation_produit']]['qualite'] * ($terrains[$_GET['voir_terrain_no']]->stock[$_POST['importation_produit']]['quantite'] - $quantite) + $quantite * $joueur_actuel->stock[$_POST['importation_produit']]['qualite']) / $terrains[$_GET['voir_terrain_no']]->stock[$_POST['importation_produit']]['quantite'];;
			$terrains[$_GET['voir_terrain_no']]->stock[$_POST['importation_produit']]['prix_revient'] = ($terrains[$_GET['voir_terrain_no']]->stock[$_POST['importation_produit']]['prix_revient'] * ($terrains[$_GET['voir_terrain_no']]->stock[$_POST['importation_produit']]['quantite'] - $quantite) + $quantite * $joueur_actuel->stock[$_POST['importation_produit']]['prix_revient']) / $terrains[$_GET['voir_terrain_no']]->stock[$_POST['importation_produit']]['quantite'];;
	    
		    $req = $bdd->prepare('UPDATE terrains SET stock = :stock WHERE id = ' . $_GET['voir_terrain_no']);
		    $req->execute(array(
				 'stock' => $terrains[$_GET['voir_terrain_no']]->stock_enregistrer()
				 ));
	    }
	}
	
	if (isset($_POST['nouvelle_qualite_eau']))
	{
		$terrains[$_GET['voir_terrain_no']]->qualite_contrat_eau = $_POST['nouvelle_qualite_eau'];
		$req = $bdd->prepare('UPDATE terrains SET infos = :infos WHERE id = ' . $_GET['voir_terrain_no']);
	    $req->execute(array(
			 'infos' => $terrains[$_GET['voir_terrain_no']]->infos_enregistrer()
			 ));
	}
	
   if (1)
   {
   ?>
       <a href = "?creer_terrains=oui">Creer un terrain</a><br />
       <a href = "?acheter_terrains=oui">Acheter un terrain</a><br />
       <a href = "?voir_terrains=oui">Voir vos terrains</a>
      <?php
   }
   ?>
   <hr />
   <?php
   if (isset($_GET['creer_terrains']))
   {
        ?>
	    Veuillez spécifier les caractéristiques de votre terrain
	    <form action = "?" method = "post">
	    <input  type = "hidden" name = "confirmer_terrain" value = "oui" />
	    <label for = "taille">Taille:</label><input id = "taille" name = "taille" /> ha<br />
	    <label for = "type">Type de terrain:</label>
	    <select name="type" id="type">
           <option value="nu">Terrain nu (5.000€ / ha)</option>
	         <option value="foret">Forêt (6.000€ / ha)</option>
           <option value="champ">Champ (7.000€ / ha)</option>
           <option value="ferme">Ferme (8.000€ / ha)</option>
           <option value="elevage">Elevage (10.000€ / ha)</option>
           <option value="fleuve">Bordure de fleuve (25.000€ / ha)</option>
           <option value="industriel">Zone industrielle (50.000€ / ha)</option>
           <option value="bureaux">Bureaux (100.000€ / ha)</option>
				   <option value="pf">Plate-forme pétrolière (2.500.000€ / ha)</option>
	    </select><br />
	    <label for = "qualite">Qualité du sol (entre 0 et 100):</label><input id = "qualite" name = "qualite" /><em>(sauf pour Zone industrielle et Bureaux)</em><br />
	    <label for = "endroit">Localisation géographique</label>(pour Zone industrielle et Bureaux)</em>
	    <select name="endroit" id="endroit">
           <option value="peripherie">En périphérie (x1)</option>
           <option value="banlieue">En banlieue (x2)</option>
           <option value="centre-ville">Au centre-ville (x4)</option>
           <option value="quartier-affaires">Dans le quartier d'affaires (x8)</option>
	    </select>
	    <pre>Note: le prix des terrains est calculé ainsi:
	    Prix = taille * (qualité + 75)² * prix de la catégorie) / 15625 sauf pour Zone industrielle et Bureaux
	    Prix = taille * prix de la catégorie * multiplicateur pour Zone industrielle et Bureaux
	    </form>
	    <input type = "button" value = "Calculer le prix" onclick = "actualiser_prix()" /><input id = "champ_prix" value = "Prix: " /><br />
	    <input type = "submit" value = "Acheter le terrain" />
	   <?php
   }
   if (isset($_POST['confirmer_terrain']))
   {
       $taille = $_POST['taille'];
       $type = $_POST['type'];
       $qualite = $_POST['qualite'];
       $endroit = $_POST['endroit'];
       
        $prix_final = prix_terrain($taille, $type, $qualite, $endroit);
        $req = $bdd->prepare('INSERT INTO terrains(type, possesseur, infos) VALUES(:type, :possesseur, :infos)');
	    $infos = 'taille:' . $taille . ';qualite:' . $qualite . ';endroit:' . $endroit;
	    
	    $req->execute(array(
			'type' => $type,
			'possesseur' => '',
			'infos' => $infos,
			));
       echo 'Vous avez bien acheté ce terrain pour le prix de ' . $prix_final . ' euros.';
   }
   
   if (isset($_GET['acheter_terrains']))
   {
        $req = $bdd->query("SELECT * FROM terrains WHERE `possesseur` = ''");
        for ($i = 0 ; $terrain_temp = $req->fetch() ; $i++)
	    {
	        $terrains_disponibles[$terrain_temp['id']] = new Terrain($terrain_temp);
	    }
	    ?>
	    <table><caption>Terrains disponibles</caption>
	    <tr><td>Id</td><td>Taille</td><td>Type</td><td>Qualité</td><td>Localisation</td><td>Prix</td><td>Acheter</td></tr>
	    <?php
	    foreach ($terrains_disponibles as $id => $terrain_actuel)
	    {
			echo texte_terrain($terrain_actuel, 'acheter');
	    }
	    ?>
	    </table>
	    <?php
   }
   
   
   
   if (isset($_GET['voir_terrains']))
   {
	    ?>
	    <table><caption>Terrains disponibles</caption>
	    <tr><td>Id</td><td>Taille</td><td>Type</td><td>Qualité</td><td>Localisation</td><td>Prix</td><td>Productions restantes<td>Aller sur ce terrain</td></tr>
	    <?php
	    foreach ($terrains_possedes as $id => $terrain_actuel)
	    {
			echo texte_terrain($terrain_actuel, 'voir');
	    }
	    ?>
	    </table>
	    <?php
   }
   
   if (isset($_GET['voir_terrain_no']))
   {
       $terrain_actuel = &$terrains[$_GET['voir_terrain_no']];
	   ?>
	   <table><caption>Statut du terrain</caption>
	   <tr><td>Numero du terrain</td><td><?php echo $_GET['voir_terrain_no']; ?></td></tr>
	   <tr><td>Taille du terrain</td><td><?php echo $terrain_actuel->taille; ?> ha</td></tr>
	   <tr><td>Type du terrain</td><td><?php echo $terrain_actuel->type; ?></td></tr>
	   <?php
	   if ($terrain_actuel->type != 'industriel' && $terrain_actuel->type != 'bureaux')
	   {
			?>
			<tr><td>Qualité du sol</td><td><?php echo $terrain_actuel->qualite; ?> / 100</td></tr>
	        <?php
	   }
	   
	   else
	   {
			?>
			<tr><td>Emplacement</td><td><?php echo $terrain_actuel->endroit; ?></td></tr>
	        <?php
	   }
	   ?>
	   <tr><td>Activité</td><td><?php echo $terrain_actuel->activite; ?></td></tr>
	   <?php
	   if ($terrain_actuel->activite != '')
	   {
	       ?>
	       <tr><td>Eau</td><td>
	       <?php 
	       $frais_ressources = 0;
	       if ($liste_activites[$terrain_actuel->activite]['ressources']['eau'] == 0)
	       {
				echo 'Non';
	       }
	       
	       else if ($liste_activites[$terrain_actuel->activite]['ressources']['eau'] != 0 && $terrain_actuel->qualite_contrat_eau == '')
	       {
				echo "<strong>Vous n'avez pas défini la qualité de votre contrat d'eau.</strong>";
	       }
	       
	       else
	       {
				echo $terrain_actuel->quantite_eau(); ?> m³ par jour (<?php echo $terrain_actuel->prix_eau() ?> € par jour) (Q<?php echo round($terrain_actuel->qualite_contrat_eau);?>)
				<?php
				$frais_ressources += $terrain_actuel->prix_eau();
			}
	       ?>
	       </td>
	       <tr><td>Electricité</td><td>
	       <?php 
	       if ($liste_activites[$terrain_actuel->activite]['ressources']['electricite'] != 0)
	       {
				echo $terrain_actuel->prix_electricite() / 0.065; ?> kWh par jour (<?php echo $terrain_actuel->prix_electricite(); ?> € par jour)</td><?php
				$frais_ressources += $terrain_actuel->prix_electricite();
	       }
	       
	       else
	       {
				echo 'Non';
	       }
	       $frais_salaires = ceil($liste_activites[$terrain_actuel->activite]['employes']['nombre'] * $terrain_actuel->taille) * $liste_activites[$terrain_actuel->activite]['employes']['salaire'];
	       $frais_fonciers = round(prix_terrain($terrain_actuel->taille, $terrain_actuel->type, $terrain_actuel->qualite, $terrain_actuel->endroit) / 7300, 2);
		   $frais_materiels = $terrain_actuel->frais_entretien();
	       ?>
	       <tr><td>Coûts salariaux</td><td><?php echo ceil($liste_activites[$terrain_actuel->activite]['employes']['nombre'] * $terrain_actuel->taille); ?> employés (<?php echo $terrain_actuel->prix_salaires()?> € par jour)</td>
	       <tr><td>Impôts fonciers</td><td><?php echo $terrain_actuel->impots_fonciers();?> € par jour</td>
	       <tr><td>Frais d'entretien</td><td><?php echo number_format($terrain_actuel->frais_entretien(), 2);?>€</td>
	       <?php
	       $frais_totaux = $frais_ressources + $frais_salaires + $frais_fonciers + $frais_materiels;?>
			<tr><td>Frais totaux</td><td><?php echo number_format($frais_totaux);?> €</td></tr>
			<?php
	   }
	   ?>
	   <tr><td>Action sur ce terrain:</td>
	   <td><ul>
	   <li><a href = "?voir_terrain_no=<?php echo $_GET['voir_terrain_no'];?>&amp;modifier_activite=oui">Modifier l'activité</a></li>
	   <li><a href = "?voir_terrain_no=<?php echo $_GET['voir_terrain_no'];?>&amp;changer_taille=oui">Changer la taille</a></li>
	   <li><a href = "?voir_terrain_no=<?php echo $_GET['voir_terrain_no'];?>&amp;changer_qualite=oui">Augmenter la qualite</a></li>
	   <?php if ($liste_activites[$terrain_actuel->activite]['ressources']['eau'] != 0) {?><li><a href = "?voir_terrain_no=<?php echo $_GET['voir_terrain_no'];?>&amp;changer_contrat_eau=oui">Changer la qualité du contrat d'eau</a></li><?php }?>
	   </ul></td></tr>
	   </table>
	   <hr />
	   <?php
	   if (isset($_GET['modifier_activite']))
       {
            ?>
			<form action = "?voir_terrain_no=<?php echo $_GET['voir_terrain_no'];?>" method = "post">
			<label for = "selection_activite">Selectionnez votre activite</label>
			<select name = "selection_activite" id = "selection_activite">
			<?php
			foreach ($liste_activites as $b => $a)
			{
			    if (array_key_exists($terrain_actuel->type, $a['terrains']))
			    {
			        echo '<option value = "' . $a['nom'] . '">' . $a['nom'] . '</option>';
			    }
			}
			?>
			</select>
			<input type = "submit" value = "Valider" />
			</form>
	        <?php
       }
       
       if (isset($_GET['changer_contrat_eau']))
       {
            ?>
			<form action = "?voir_terrain_no=<?php echo $_GET['voir_terrain_no'];?>" method = "post">
			<label for = "nouvelle_qualite_eau">Selectionnez la nouvelle qualité d'eau</label>
			<input name = "nouvelle_qualite_eau" id = "nouvelle_qualite_eau" /><br />
			<input type = "hidden" id = "coef_base_eau" name = "coef_base_eau" value = "<?php echo $liste_activites[$terrain_actuel->activite]['ressources']['eau'] * $terrain_actuel->taille * 0.0025;?>" />
			<input type = "button" value = "Calculer le prix" onclick = "prix_eau()" /><input id = "champ_prix_eau" value = "Prix: " /><br />
			<input type = "submit" value = "Valider" />
			</form>
	        <?php
       }
       ?>
       <hr />
       <?php
       if ($terrain_actuel->activite != '')
       {
			?>
			<table><caption>Productions quotidiennes</caption>
			<tr><td>Produit</td><td>Quantité</td></tr>
			<?php
			foreach ($liste_activites[$terrain_actuel->activite]['productions'] as $b => $a)
			{
				echo '<tr><td>' . $b . '</td><td>' . ecriture_stock($terrain_actuel->production_produit($b), 2) . '</td></tr>';
			}
			?>
			</table>
			
			<hr />
			<table><caption>Consommations quotidiennes</caption>
			<tr><td>Produit</td><td>Quantité</td><td>Stock actuel</td><td>Action</td></tr>
			<?php
			foreach ($liste_activites[$terrain_actuel->activite]['consommations'] as $b => $a)
			{
				echo '<tr><td>' . $b . '</td><td>' . ecriture_stock($terrain_actuel->consommation_produit($b), 2) . '</td><td>' . ecriture_stock($terrain_actuel->stock[$b]['quantite'], 2) . ' (Q' . round($terrain_actuel->stock[$b]['qualite']) . ')</td>
				<td>
				<form method = "post">
				<input type = "hidden" name = "importation_produit" value = "' . $b . '" />
				<label for = "quantite_importer">Importer ce produit (' . ecriture_stock($joueur_actuel->stock[$b]['quantite'], 2) . ' en stock (Q' . round($joueur_actuel->stock[$b]['qualite']) . '))</label>
				<input name = "quantite_importer" required />
				<input type = "submit" value = "Importer" />
				</form>
				</td></tr>';
			}
			?>
			</table>
			<br />
			<?php
			//Calcul des temps de vagues
			$temps_restant = 0;
			if (count($liste_activites[$terrain_actuel->activite]['consommations']) != 0)
			{
				$temps_restant = 10000000000;
				foreach ($liste_activites[$terrain_actuel->activite]['consommations'] as $b => $a)
				{
					$temps_restant = min($temps_restant, 86400 * $terrain_actuel->stock[$b]['quantite'] / $terrain_actuel->consommation_produit($b));
				}
			}
			echo 'Temps de production restant: ' . format_duree($temps_restant);
			$terrain_actuel->temps_restant = $temps_restant;
			?>
			<hr />
			<table><caption>Qualité</caption>
			<tr><td>Objet</td><td>Maximum</td><td>Actuel</td></tr>
			<?php
			$qualite_totale = 0;
			foreach ($liste_activites[$terrain_actuel->activite]['qualite'] as $b => $a)
			{
				echo '<tr><td>' . $b . '</td><td>' . $a . '</td><td>';
				$valeur = 0;
				
				switch ($b)
				{
				    case 'formation_employes': $valeur = $a; break;
				    case 'RD': $valeur = $terrain_actuel->qualite_produit_rd($b); break;
				    case 'qualite_eau': $valeur = $terrain_actuel->qualite_contrat_eau * $a / 100;; break;
				    case 'sol': $valeur = $terrain_actuel->qualite * $a / 100; break;
				    case 'base': $valeur = $a;
				    default: $valeur = $terrain_actuel->stock[$b]['qualite'] * $a / 100; break;
				}
				$qualite_totale += $valeur;
				echo round($valeur, 2);
				echo '</td></tr>';
			}
			?>
			<tr><th>Total</th><th>100</th><th><?php echo round($qualite_totale, 2);?></th></tr>
			</table>
			<hr>
			<?php
			$quantite_totale_production = 0;
			foreach ($liste_activites[$terrain_actuel->activite]['productions'] as $b => $a)
			{
				if (!preg_match("#déchets#", $b) || !preg_match("#graine#", $b) && $terrainèactyek->activite != 'Repos')
				{
					$quantite_totale_production += $terrain_actuel->production_produit($b);
				}
			}	
			?>
			<table><caption>Frais pour 1 kg de produit</caption>
			<tr><th>Objet</th><th>Consommation pour 1 kg</th><th>Prix de revient de 1kg</th><th>Sous-total</th>
			<tr><td>Frais</td><td style = "background-color:rgb(128,128,128);"></td><td style = "background-color:rgb(128,128,128);"></td><td><?php echo round($frais_totaux / $quantite_totale_production, 3 - log10($frais_totaux / $quantite_totale_production));?>€</td></tr>
			<?php
			foreach ($liste_activites[$terrain_actuel->activite]['consommations'] as $b => $a)
			{
				$frais_consommation += $terrain_actuel->consommation_produit($b) * $terrain_actuel->stock[$b]['prix_revient'];
				echo '<tr><td>' . $b . '</td><td>' . round_adapte($terrain_actuel->consommation_produit($b) / $quantite_totale_production, 3) . '</td><td>' . round_adapte($terrain_actuel->stock[$b]['prix_revient'], 3) . '</td><td>' . round_adapte($terrain_actuel->consommation_produit($b) / $quantite_totale_production * $terrain_actuel->stock[$b]['prix_revient'], 3) . '</td></tr>';
			}
			$frais_reels = $frais_totaux + $frais_consommation;
			?>
			<tr><th>Total</th><th style = "background-color:rgb(128,128,128);"></th><th style = "background-color:rgb(128,128,128);"></th><th><?php echo round_adapte($frais_reels / $quantite_totale_production, 5);?>€</th></tr>
			</table>
			<?php
       }
   }
   include ('fin.php');
   ?>
   </center>
   </body>
</html>