<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

/**
 * Description of CitiesClass
 *
 * @author Óscar Andreu
 */
class GuiHelper {


	public static function getSelectList($data, $entityName, $selectId, $selected = '', $onChangeFunction = '') {
		global $wpdb;


		if (count($data) == 0) {
			$lResult.="No se han definido.";

		} elseif (count($data) == 1) {
			foreach ($data as $item) {
				$id = $item->id;
				$name = stripslashes($item->name);
				$lResult.="<em>$name</em><input type='hidden' name='$entityName' value='$id' />";
			}

		} else {
			if ($onChangeFunction != '')
				$onChangeFunction = 'onchange="' . $onChangeFunction . '"';
			$lResult.="<select name='$selectId' class='wgo_listing_selector' id='$selectId' $onChangeFunction>";
			$lResult.="<option value=''>Seleccione un $entityName</option>";
			foreach ($data as $item) {
				$sel = $item->id == $selected ? "selected " : null;

				$lResult.="<option value='$item->id' $sel>" . stripslashes($item->name) . "</option>";
			}
			$lResult.="</select>";
		}

		return $lResult;
	}

}

?>