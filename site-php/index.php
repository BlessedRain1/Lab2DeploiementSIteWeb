<?php
require_once("dal/DAL.php");
session_start();

$category_id = 0;
if (isset($_POST['lstCategory'])) {
	$category_id = $_POST['lstCategory'];
	$_SESSION["category_id"] = $category_id;
}
elseif (isset($_SESSION["category_id"])) {
	$category_id = $_SESSION['category_id'];
}

$dal = new DAL();
$all_categories = $dal->CategoryFact()->getAllCategories();

ob_start();
?>

<br />
<h2>Listes des véhicules selon la catégorie</h2>
<br />

<form action="" method="POST">
	Catégorie :
	<select name="lstCategory">
		<option value="0">---------------------------</option>
		<?php
		foreach ($all_categories as $cat) {
			echo "<option value=\"" . $cat->id . "\"";
			if ($category_id > 0 && $category_id == $cat->id) {
				echo " selected=\"selected\"";
			}
			echo ">" . $cat->nom . "</option>";
		}
		?>
	</select>
	<input type="submit" value="OK" />
</form>
<br />
			
<table id="consommations" class="withborder">
	<?php
	if ($category_id > 0) {
		$products = $dal->ProductFact()->getProductsByCategory($category_id);
		?>
		<tr>
			<td>Modèle</td>
			<td>Ville</td>
			<td>Route</td>
			<td>Combine</td>
		</tr>
		
		<?php
		foreach ($products as $prod) {
			echo "<tr>";
			echo "	<td><a href=\"details.php?id=" . $prod->id . "\">" . $prod->nom . "</a></td>";
			echo "	<td>" . number_format($prod->ville, 2) . "</td>";
			echo "	<td>" . number_format($prod->route, 2) . "</td>";
			echo "	<td>" . number_format($prod->combine, 2) . "</td>";
			echo "</tr>";
		}
	}
	else {
		?>
		<tr>
			<td colspan="4" class="empty">
				Vous devez sélectionner une catégorie
			</td>
		</tr>
		<?php
	}
	?>
</table>
<br />

<?php
$region_content = ob_get_clean();

require('includes/template.php');
?>