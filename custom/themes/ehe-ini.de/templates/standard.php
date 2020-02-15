<?php

	namespace BB\custom\themes\eheini;
	
	if(@secure !== true)
		die('forbidden');
	
	class standard {

		/**
		 * @param \BB\template\classic $pageTmpl
		 * @param $tree_id
		 * @param $lan_id
		 * @param array $page_ids
		 */
		public static function modeStage(\BB\template\classic $pageTmpl, $tree_id, $lan_id, $page_ids = array()) {

			self::assignSlideShowImages($pageTmpl);
			
		}

		/**
		 * @param \BB\template\classic $pageTmpl
		 */
		protected function assignSlideShowImages($pageTmpl) {

			$slideShowDirectory = new \BB\directory('share/public/Slideshow');
			$slideShowImages = $slideShowDirectory->getFiles();

			$slideShowListElements = array();
			foreach($slideShowImages as $slideShowImage):
				$slideShowListElements[] = '<li><img src="image/1/'.$slideShowImage->path.'?w=980&h=255" alt=""></li>';
			endforeach;

			$pageTmpl->assign('slideShowImages', implode("\r\n", $slideShowListElements));

		}


		public static function modePage(\BB\template\classic $pageTmpl, $tree_id, $lan_id, $page_id) {

      $coreHttp = \BB\http\request::get();
      $seoTitle = $coreHttp->getString('seotitle');

      if(!empty($seoTitle)):
        $eventsDao = \BB\custom\model\element\dao\Veranstaltungen::instance();
        $eventsSearch = new \BB\custom\model\eventsSearch($eventsDao);
        $eventsSearch->setSeoTitle($seoTitle);
        $results = $eventsSearch->getResults(1, false, true);
        $event = $results[0];
        $eventData = $event->getData(1);
        $eventTitle = $eventData->Veranstaltungstitel;

        $pageTmpl->assign('seotitle', !empty($eventTitle) ? ' - '.$eventTitle : '');
      endif;

      $pageTmpl->assign('seotitle', '');

    }

    /*
    public static function modeMail(\BB\template\classic $pageTmpl, $tree_id, $lan_id, $page_id) {
      $tree_id = (int)$tree_id;
      $lan_id = (int)$lan_id;
      $page_id = (int)$page_id;
    }
    */

	}
	
?>