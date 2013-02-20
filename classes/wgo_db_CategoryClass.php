<?php
include_once(WGO_BD_BASE_DIR.'classes/wgo_db_GuiHelper.php');
/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

/**
 * Description of CategoryClass
 *
 * @author Diario
 */
class CategoryClass {

	public $id;
	public $name;

	public static function searchInfo($id = '', $name = '') {
		global $wpdb;

		//avoid sql injection
		$name = mysql_real_escape_string($name);
		$id = mysql_real_escape_string($id);

		$where = "WHERE 1=1";
		if ($id != '')
			$where .= " AND id='$id'";

		if ($name != '')
			$where .= " AND name='$name'";

		$query = "SELECT id, name FROM " . $wpdb->prefix . "wgo_categories $where order by name";
		//echo '<p>'.$query;

		return $wpdb->get_results($query);
	}

	public static function getCategoryName($id) {
		$lResult = "N/A";

		//avoid sql injection
		$id = mysql_real_escape_string($id);

		$search = CategoryClass::searchInfo($id);
		if (count($search) > 0)
			$lResult = $search[0]->name;

		return $lResult;
	}

	public static function getCategoryId($name) {
		if ($name == null || $name == '')
			return null;

		//avoid sql injection
		$name = mysql_real_escape_string($name);

		$search = CategoryClass::searchInfo('', $name);
		if (count($search) > 0)
			$lResult = $search[0]->id;

		return $lResult;
	}

	public static function getCategoryList($selected = '', $onChangeFunction = '') {
		$categories = CategoryClass::searchInfo();

		return GuiHelper::getSelectList($categories, 'categoría', 'wgo_listing_cat_id', $selected, $onChangeFunction);
	}

}

?>
