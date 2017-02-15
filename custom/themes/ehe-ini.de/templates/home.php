<?php

	namespace BB\custom\themes\eheini;
	
	if(@secure !== true)
		die('forbidden');

	require_once('standard.php');

	class home extends standard {

		public static function modeStage(\BB\template\classic $pageTmpl, $tree_id, $lan_id, $page_ids = array()) {

			parent::modeStage($pageTmpl, $tree_id, $lan_id, $page_ids);

		}

	}
	
?>