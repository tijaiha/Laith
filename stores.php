<?php 
require_once 'core/init.php';
require_once 'functions/ui.php';
//require_once 'includes/loggedin.php';

?>

<div class="transactionwrapper">
	<?php
	//error_reporting(0);


	// If create store submit is pressed
	if (!empty($_POST['createSubmit'])) {
								
		$error = "";

		// Check if field is populated : throw an eror.
		if (!$_POST['storeName']) {
			$error .= "Store Name";
		}

		// Create new store object using submitted name
		$store = new Store(escape($_POST['storeName']));


		// To-Do:
		// THROW ERROR IF STORE EXISTS!
		//


		// If there are no results with the store name
		if (!$store->GetResult){

			// Check for form population errors
			if (!$error) {

				// Populate $_POST['storeActive'] with a 1 or 0 
				// (check box defaults to "on" or null)
				if ($_POST['storeActive']) {
					$_POST['storeActive'] = 1;
				} else {
					$_POST['storeActive'] = 0;
				}

				// Set Store class properties to values from POST
				$store->SetActive($_POST['storeActive']);
				$store->SetName(escape($_POST['storeName']));
				
				// Write the store to database
				$store->WriteStore();

			}
		}
	}

	// If edit store is pressed
	if (!empty($_POST['editSubmit'])) {

		// Create new store object populated with
		// store ID passed via POST
		$store = new Store((int) $_POST['storeID']);
	}

	?>

	<form action="index.php?page=stores" method="post" autocomplete="off">
		<input type="hidden" id="storeID" name="storeID" value="<?php

		// Populate hidden form with store ID that is being edited
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

					// Populate form check box if edited store is active
					// else default the box to checked for creating new store
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

					// Populate store name form field with edited store 
					if (!empty($_POST['editSubmit'])) {
						echo $store->GetName();
					}
					?>">
				</td>
				<td>
					<input type="submit" name="createSubmit">
				</td>
			</tr>
			<!-- END OF FORM -->

			<!-- PULL FROM DATABASE -->
			<?php

			// Create database object, connect, and fetch a list of all stores.
			$db = new DB();
			$storelist = $db->fetchStores();

			// Vars for creating html table
			$btd = "<td><p>";
			$etd = "</p></td>";

			// Create and populate table with all stores.
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
				'<form action="index.php?page=stores" method="post" autocomplete="off"><input type="hidden" id="storeID" name="storeID" value="' . $value['id'] . '">' .
				$btd . $value['active'] . $etd .
				$btd . $value['name'] . $etd . 
				'<td><input type="submit" name="editSubmit" value="Edit"></td></form></tr>';

			}?>

		</table>
	</form>
</div>

<div class="actionwrapper">

</div>