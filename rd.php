<?php
include ('debut.php');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
   <head>
       <title>RD</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
       <link rel="stylesheet" media="screen" type="text/css" title="Vert et bleu" href="css.css" />
	   <script type="text/javascript" src="includes/overlib.js"></script>
   </head>
   <body>
   <?php include ('menu.php');
   if (isset($_POST['recherche']))
   {
		$r = $recherches[$_POST['recherche']];
		$niveau = max(0, $joueur_actuel->rd[$_POST['recherche']]);
		$prix_basique = $r['point_de_recherche_basique'][0] + $niveau * $r['point_de_recherche_basique'][1] + $niveau * $niveau * $r['point_de_recherche_basique'][2] + $niveau * $r['point_de_recherche_basique'][3];
		if ($prix_basique <= $joueur_actuel->stock['point_de_recherche_basique']['quantite'])
		{
			$joueur_actuel->stock['point_de_recherche_basique']['quantite'] -= $prix_basique;
			$joueur_actuel->rd[$_POST['recherche']]++;
		}
   }
   print_r($joueur_actuel);
   ?>
   <center>
   <table><caption>Recherches standard</caption>
   <tr><th>Recherche</th><th>Niveau actuel (/ niveau maximal)</th><th>Points de recherches basiques pour le prochain niveau(<?php echo round($joueur_actuel->stock['point_de_recherche_basique']['quantite'], 2);?> disponibles)</th><th>Lancer la recherche</th>
   <?php
   $recherches_basiques = array('basique', 'productivite', 'qualite', 'entretien');

   foreach ($recherches_basiques as $b => $a)
   {
		$r = $recherches[$a];
		$niveau = max(0, $joueur_actuel->rd[$a]);
		$prix_basique = $r['point_de_recherche_basique'][0] + $niveau * $r['point_de_recherche_basique'][1] + $niveau * $niveau * $r['point_de_recherche_basique'][2] + $niveau * $r['point_de_recherche_basique'][3];
		echo '<tr><td>' . $r['nom'] . '</td><td>' . max(0, $joueur_actuel->rd[$a]) . '</td><td>' . $prix_basique . '</td><td>';
		if ($prix_basique <= $joueur_actuel->stock['point_de_recherche_basique']['quantite'])
		{
		?>
		<form method = "post">
		<input type = "hidden" name = "recherche" value = "<?php echo $a;?>" />
		<input type = "submit" value = "Rechercher" />
		</form>
		<?php
		}
		
		else
		{
			echo 'Pas assez de points de recherche';
		}
		
		echo '</td></tr>';
   }
   ?>
   </table>
   </center>
   </body>
</html>
<?php include ('fin.php');