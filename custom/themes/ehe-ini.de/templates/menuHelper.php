<?php

  namespace BB\custom\themes\eheini;

  if(@secure !== true)
    die('forbidden');

  /**
   * @author Dirk Münker
   * @since 3.0.15
   */
  class menuHelper extends \BB\template\classic {

    protected static $autoloadFolders = array(
      'custom/dao/',
      'custom/dao/model/',
      'custom/dao/element/'
    );

    /**
     * @param int $pageID
     * @return void
     */
    protected function getEvents($pageID) {

      $eventPage = \BB\db::query(
        ' SELECT page_filename'.
        ' FROM '.\BB\config::get('db:prefix').'web_objects'.
        ' LEFT JOIN '.\BB\config::get('db:prefix').'web_contents'.
        ' ON cn_obj_id = obj_id'.
        ' LEFT JOIN '.\BB\config::get('db:prefix').'web_pages'.
        ' ON page_id = cn_page_id'.
        ' WHERE obj_md5 = "event"',
        true
      );

      $modelContent = \BB\model\content::get();
      $type = $modelContent->getValue('Website', 'Veranstaltungsart', $pageID);

      if(!empty($type)):

        require_once('custom/dao/element/Veranstaltungen.php');
        require_once('custom/dao/model/Veranstaltungen.php');

        $eventsDao = \BB\custom\model\element\dao\Veranstaltungen::instance();
        $eventsSearch = new \BB\custom\model\eventsSearch($eventsDao);
        $eventsSearch->setFromTimestamp(time());
        $eventsSearch->setType($type);
        $results = $eventsSearch->getResults(1, true);

        $events = array();
        foreach($results as $result):
          $eventID = $result->getContentID();
          $events[$eventID] = $result->getData(1);
        endforeach;

        $menuString = '';
        if(count($events) > 0):
          $menuString = '<ol class="subsub">';
          foreach($events as $eventID => $eventData):
            $menuString .= '<li><a href="de/'.$eventPage['page_filename'].'.html?eventID='.$eventID.'">'.strftime('%d.%m.%Y', $eventData->Veranstaltungsstartdatum).'</a></li>';
          endforeach;
          $menuString .= '</ol>';
        endif;

        echo $menuString;

      else:
        echo '';
      endif;

    }

  }

?>