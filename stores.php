<?php
require_once 'core/init.php';
require_once 'includes/loggedin.php';
$editing = NULL;
?>

<div class="transactionwrapper">

	<?php
	//error_reporting(0);
	if (!empty($_POST['createSubmit'])) {
		$error = "";
		if (!$_POST['storeName']) {
			$error .= "First Name";
		}
		if (!$error && !$editing) {
			$store = new Store(escape($_POST['storeName']));
			if ($_POST['storeActive']) {
				$_POST['storeActive'] = 1;
			} else {
				$_POST['storeActive'] = 0;
			}
			$store->SetActive($_POST['storeActive']);
			$store->SetName(escape($_POST['storeName']));
			$store->WriteStore();
		}
	}
	if (!empty($_POST['editSubmit'])) {
		$store = new Store((int) $_POST['editStoreID']);
		$editing = True;
	}
	if (!empty($_POST['updateSubmit'])) {
		$store = new Store((int) $_POST['storeID']);
		$error = "";
		if (!$_POST['storeName']) {
			$error .= "Storename";
		}
		if (!$error) {
			if ($_POST['storeActive']) {
				$_POST['storeActive'] = 1;
			} else {
				$_POST['storeActive'] = 0;
			}
			$store->SetActive($_POST['storeActive']);
			$store->SetName(escape($_POST['storeName']));
			$store->UpdateStore();
		}
	}
	?>

	<form action="index.php?page=stores" method="post" autocomplete="off">
		<input type="hidden" id="storeID" name="storeID" value="<?php
		if (!empty($_POST['editSubmit'])) {
			echo $store->GetID();
		}
		?>">
		<table>
			<tr>
				<td><label for="storeActive">Active: </label></td>
				<td><label for="storeName">Store Name: </label></td>
			</tr>
			<tr>
				<td>
					<input type="checkbox" id="storeActive" name="storeActive" <?php
					if (!empty($_POST['editSubmit'])) {
						if($store->GetActive() == 1){
							echo "checked";
						} else {
							echo "";
						}
					} else {
						echo "checked";
					}
					?>>
				</td>
				<td>
					<input type="text" autocomplete="off" id="storeName" name="storeName" value="<?php
					if (!empty($_POST['editSubmit'])) {
						echo $store->GetStore();
					}
					?>">
				</td>
				<td>
					<?php if(!$editing) {
						echo '<input type="submit" name="createSubmit" value="Create">';
					} else {
						echo '<input type="submit" name="updateSubmit" value="Update">';
					}?>
				</td>
			</tr>
			<!-- END OF FORM -->

			<!-- PULL FROM DATABASE -->
			<?php
			$db = new DB;
			$storelist = $db->fetchStores();
			$btd = "<td><p>";
			$etd = "</p></td>";
			//var_dump($storelist);
			foreach ($storelist as $key => $value) {
				if ($value['active'] == 1) {
					$value['active'] = "Active";
					$row = '<tr class="active">';
				} else {
					$value['active'] = "Inactive";
					$row = '<tr class="inactive">';
				}
				echo
				$row .
				'<form action="index.php?page=stores" method="post" autocomplete="off"><input type="hidden" id="editStoreID" name="editStoreID" value="' . $value['id'] . '">' .
				$btd . $value['active'] . $etd .
				$btd . $value['name'] . $etd .
				'<td><input type="submit" name="editSubmit" value="Edit"></td></form></tr>';
			}
			?>

		</table>
	</form>
</div>

<div class="actionwrapper">

</div>