<?php
include ('debut.php');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
    <head>
        <title>Le marché</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <link rel="stylesheet" media="screen" type="text/css" title="Vert et bleu" href="css.css" />
	    <script type="text/javascript" src="includes/overlib.js"></script>
    </head>
    <body>
    <?php
    include ('menu.php');
    ?>
    <center>
    <?php
    $req = $bdd->query("SELECT * FROM marche");
    for ($i = 0 ; $marche[$i] = $req->fetch() ; $i++)
    {
		$marche[$i]['faux_id'] = $i;
    }
    
    for ($i = 0 ; $i < count($marche) ; $i++)
    {
 		$marche_trie[$marche[$i]['produit']][$marche[$i]['type']][count($marche_trie[$marche[$i]['produit']][$marche[$i]['type']])] = $marche[$i];
    }
    ?>
    <center>Choisissez le produit:<br />
    <form method = "post" action = "?">
    Choisissez le type:<br />
    <select name = "type">
    <option value = "vente">Achat</option>
    <option value = "achat">Vente</option>
    </select>
    <br />
    <input type = "submit" value = "Valider" />
    </form>
    </center>
    
    <?php
    $assoc = array('achat' => 'Acheter',
    'vente' => 'Vendre');
    if (isset($_POST['type']))
    {
		echo '<table>
		<tr><th>Produit</th><th>Nombre d\'offres</th><th>Cours</th><th>Action</th></tr>';
		foreach ($infos_produits as $b => $a)
		{
			echo '<tr><td>' . $b . '</td><td>' . count($marche_trie[$b][$_POST['type']]) . '</td><td>' . $a['cours'] . '</td>';
			if ($_POST['type'] == 'achat' && count($marche_trie[$b][$_POST['type']]) > 0)
			{
				echo '<td><a href = "?action=achat&amp;produit=' . $b . '">Vendre</a></td>';
			}
			
			else if ($_POST['type'] == 'vente' && count($marche_trie[$b][$_POST['type']]) > 0)
			{
				echo '<td><a href = "?action=vente&amp;produit=' . $b . '">Acheter</a></td>';
			}
			
			else
			{ 
				echo '<td>Non disponible</td>';
			}
			
			echo '</tr>';
		}
		echo '</table>';
    }
    
    if (isset($_GET['action'], $_GET['produit']))
    {
		echo '<table>';
		if ($_GET['action'] == 'achat')
		{
			echo '<tr><th>Produit</th><th>Acheteur</th><th>Prix d\'achat</th><th>Quantité maximale</th><th>Qualité minimale demandée</th><th>Quantité à vendre (max: ' . ecriture_stock($joueur_actuel->stock[$_GET['produit']]['quantite'], 2). ' (Q' . round($joueur_actuel->stock[$_GET['produit']]['qualite'], 2) . '))</th></tr>';
		}
		
		else
		{
			echo '<tr><th>Produit</th><th>Vendeur</th><th>Prix de vente</th><th>Quantité disponible</th><th>Qualité du produit</th><th>Quantité à acheter</th></tr>';
		}
		
		foreach ($marche_trie[$_GET['produit']][$_GET['action']] as $b => $a)
		{
			echo '<tr><td>' . $_GET['produit'] . '</td><td>' . $a['origine'] . '</td><td>' . $a['prix'] . '</td><td>' . ecriture_stock($a['quantite'], 2) . '</td><td>' . $a['qualite'] . '</td>';
			if ($_GET['action'] == 'vente')
			{
				?>
				<td>
				<form method = "post" action = "?">
				<input type = "hidden" name = "action" value = "achat" />
				<input type = "hidden" name = "id" value = "<?php echo $a['faux_id']?>" />
				<input name = "quantite" />
				<input type = "submit" value = "Acheter" />
				</form>
				</td>
				<?php
			}
			
			else
			{
				?>
				<td>
				<form method = "post" action = "?">
				<input type = "hidden" name = "action" value = "vente" />
				<input type = "hidden" name = "id" value = "<?php echo $a['faux_id']?>" />
				<input name = "quantite" />
				<input type = "submit" value = "Vendre" />
				</form>
				</td>
				<?php
			}
			echo '</tr>';
		}
		echo '</table>';
    }
    
    if (isset($_POST['action']))
    {
		$id = $_POST['id'];
		$produit = $marche[$id]['produit'];
		$prix = $marche[$id]['prix'];
		print_r($marche[$id]);
		if ($_POST['action'] == 'achat')
		{
			if ($marche[$id]['quantite'] < $_POST['quantite'])
			{
				echo 'Vous avez entré une quantité trop importante. Transaction annulée.<br />';
			}
			
			else
			{
				$joueur_actuel->argent -= $prix * $_POST['quantite'];
				$joueur_actuel->stock[$produit]['qualite'] = ($_POST['quantite'] * $marche[$id]['qualite'] + $joueur_actuel->stock[$produit]['qualite'] * $joueur_actuel->stock[$produit]['quantite']) / ($joueur_actuel->stock[$produit]['quantite'] + $_POST['quantite']);
				$joueur_actuel->stock[$produit]['prix_revient'] = ($_POST['quantite'] * $prix + $joueur_actuel->stock[$produit]['prix_revient'] * $joueur_actuel->stock[$produit]['quantite']) / ($joueur_actuel->stock[$produit]['quantite'] + $_POST['quantite']);
				
				$joueur_actuel->stock[$produit]['quantite'] += $_POST['quantite'];
				$marche[$id]['quantite'] -= $_POST['quantite'];
				$req = $bdd->prepare('UPDATE marche SET quantite = :quantite WHERE id = ' . ($id + 1));
			    $req->execute(array(
					 'quantite' => $marche[$id]['quantite']
					 ));
				
				$texte_details = $produit . '/' . $_POST['quantite'] . '/' . $prix . '/' . $marche[$id]['qualite'];
				$req = $bdd->prepare('INSERT INTO operations(type, valeur, details) VALUES(:type, :valeur, :details)');
				$req->execute(array(
			 'type' => 'achat_marche',
			 'valeur' => $prix * $_POST['quantite'],
			 'details' => $texte_details
			 ));
				
			}
		}
		
		if ($_POST['action'] == 'vente')
		{
			if ($joueur_actuel->stock[$produit]['quantite'] < $_POST['quantite'])
			{
				echo 'Vous avez entré une quantité trop importante. Transaction annulée.<br />';
			}
			
			if ($joueur_actuel->stock[$produit]['qualite'] < $marche[$id]['qualite'])
			{
				echo 'Votre produit est de qualité insuffisante. Transaction annulée.';
			}
			
			else
			{
				$joueur_actuel->argent += $prix * $_POST['quantite'];
				$joueur_actuel->stock[$produit]['quantite'] -= $_POST['quantite'];
				$marche[$id]['quantite'] -= $_POST['quantite'];
				$req = $bdd->prepare('UPDATE marche SET quantite = :quantite WHERE id = ' . ($marche[$id]['id']));
			    $req->execute(array(
					 'quantite' => $marche[$id]['quantite']
					 ));
					 
				$texte_details = $produit . '/' . $_POST['quantite'] . '/' . $prix . '/' . $marche[$id]['qualite'] ;
				$req = $bdd->prepare('INSERT INTO operations(type, valeur, details) VALUES(:type, :valeur, :details)');
				$req->execute(array(
			 'type' => 'vente_marche',
			 'valeur' => $prix * $_POST['quantite'],
			 'details' => $texte_details
			 ));
			}
		}
    }
    ?>
    
    </center>
    <?php
    include ('fin.php');
    ?>
    </body>
</html>