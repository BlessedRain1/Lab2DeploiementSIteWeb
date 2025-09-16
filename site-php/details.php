<?php
require_once("dal/DAL.php");

$product = null;
if (isset($_GET["id"])) {
	$dal = new DAL();
	$product = $dal->ProductFact()->getProductById($_GET["id"]);
}

ob_start();
?>

<br />

<?php
if ($product != null) {
	echo "<h2>" . $product->nom . "</h2><br />";
	?>
	<div class="row">
		<div class="col-sm-6">
			<div class="details-img">
				<img src="img/key.gif" class="img-fluid" style="max-width:200px;" />
			</div>
		</div>

		<div class="col-sm-6">
			<table id="details">
				<tr>
					<td>Déplacement:</td>
					<td><?= $product->deplacement; ?></td>
				</tr>
				<tr>
					<td>Cylindre:</td>
					<td><?= $product->cylindre; ?></td>
				</tr>
				<tr>
					<td>Transmission:</td>
					<td><?= $product->transmission; ?></td>
				</tr>
				<tr>
					<td>Traction:</td>
					<td><?= $product->traction; ?></td>
				</tr>
				<tr>
					<td>Carburant:</td>
					<td><?= $product->carburant; ?></td>
				</tr>
				<tr>
					<td>Ville:</td>
					<td><?= $product->ville; ?></td>
				</tr>
				<tr>
					<td>Route:</td>
					<td><?= $product->route; ?></td>
				</tr>
				<tr>
					<td>Combiné:</td>
					<td><?= $product->combine; ?></td>
				</tr>
			</table>
		</div>
	</div>
	<?php
}
else {
	echo "<h2>Aucun véhicule trouvé</h2>";
}
?>
<br />
<a href="index.php">Retour à la page d'accueil</a>
<br /><br />

<?php
$region_content = ob_get_clean();

require('includes/template.php');
?>