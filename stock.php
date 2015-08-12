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
   <?php include ('menu.php');?>
   <center>
   <table>
   <tr><td>Produit</td><td>Quantité</td><td>Cours</td><td>Qualité</td><td>Prix de revient</td><td>Valeur</td></tr>
   <?php
   foreach ($joueur_actuel->stock as $b => $a)
   {
	    if ($b != '')
	    {
			echo '<tr><td>' . $b . '</td><td>' . ecriture_stock($a['quantite'], 2) . '</td><td>' . number_format($infos_produits[$b]['cours'], -log10($infos_produits[$b]['cours']) + 2) . '€</td><td>' . round($a['qualite'], 2) . '</td><td>' . number_format($a['prix_revient'], -log10($a['prix_revient']) + 3) . '€</td><td>' . number_format($infos_produits[$b]['cours'] * $a['quantite']) . '€</td></tr>';
		}
   }
   ?>
   </center>
   </body>
</html>
<?php include ('fin.php');