<?php

  namespace BB\custom\engine;

  if(@secure !== true)
    die('forbidden');

  class News extends \BB\model\engine {

    const limit = 3;

    protected $path = 'custom/extensions/News/';

    protected static $autoloadFolders = array(
      'custom/dao/',
      'custom/dao/model/',
      'custom/dao/element/'
    );

    /**
   * @access public
   * @return string
   */
    public function viewList(){

      $coreHttp = \BB\http\request::get();
      $orderField = new \BB\model\element\optSearchField();
      $newsDao = \BB\custom\model\element\dao\News::instance();

      $offset = $coreHttp->getInteger('offset');
      $field = $orderField->id('Newsdatum')->sortDesc();
      $results = $newsDao->getRows(1, array($field), $offset, self::limit);
      $count = $newsDao->getCount(1, array($field));

      $news = array();
      foreach($results as $newsElement):
        $newsData = $newsElement->getData(1);
        $newsData->id = $newsElement->getContentID();
        $news[] = $newsData;
      endforeach;

      $this->view->add('news', $news);
      $this->view->add('count', $count);
      $this->view->add('offset', $offset);
      $this->view->add('limit', self::limit);
      $this->view->assign('list', $this->getLink($this->page_id));
      $this->view->assign('detail', $this->getLink($this->getValue('detail')));

    }

    /**
     * @access public
     * @return string
     */
    public function viewTeaser(){

      $coreHttp = \BB\http\request::get();

      $eventsDao = \BB\custom\model\element\dao\Veranstaltungen::instance();
      $eventsSearch = new \BB\custom\model\eventsSearch($eventsDao);
      $eventsSearch->setFromTimestamp(time());
      $events = $eventsSearch->getResults(1);

      $teaser = array();
      $eventsCounter = 1;
      foreach($events as $eventEntry):
        $eventEntryData = $eventEntry->getData(1);
        if(!empty($eventEntryData->Veranstaltungsnewsbild)):

          $teaserText = substr(strip_tags($eventEntryData->Veranstaltungsbeschreibung), 0, '150');
          $teaserText = substr($teaserText, 0, strrpos($teaserText, ' ')).'...';

          $teaser[] = array(
            'image' => $eventEntryData->Veranstaltungsnewsbild,
            'headline' => str_replace('"', '', $eventEntryData->Veranstaltungstitel),
            'text' => $teaserText,
            'link' => $eventEntryData->Veranstaltungsart.'-'.$eventEntryData->VeranstaltungsSeoTitel.'.html'
          );
          $eventsCounter++;
        endif;
        if(3 == $eventsCounter) break;
      endforeach;


      $orderField = new \BB\model\element\optSearchField();
      $newsDao = \BB\custom\model\element\dao\News::instance();

      $offset = $coreHttp->getInteger('offset');
      $field = $orderField->id('Newsdatum')->sortDesc();
      $news = $newsDao->getRows(1, array($field), $offset, 1);

      foreach($news as $newsEntry):
        if(count($teaser) < 3):
          $newsEntryData = $newsEntry->getData(1);
          $teaser[] = array(
            'image' => $newsEntryData->Newsbild,
            'headline' => $newsEntryData->Newsheadline,
            'text' => nl2br(strip_tags($newsEntryData->Newsteaser)),
            'link' => $this->getLink($this->getValue('detail')).'?newsID='.$newsEntry->getContentID()
          );
        endif;
      endforeach;


      $this->view->add('news', $teaser);
      $this->view->assign('detail', $this->getLink($this->getValue('detail')));

    }

    public function viewDetail() {

      $coreHttp = \BB\http\request::get();

      $offset = $coreHttp->getInteger('offset');
      $newsID = $coreHttp->getInteger('newsID');

      $newsDao = \BB\custom\model\element\dao\News::instance();
      $newsElement = $newsDao->getDaoElement($newsID);
      $newsData = $newsElement->getData(1);
      $newsData->id = $newsElement->getContentID();

      $this->view->add('newsElement', $newsData);
      $this->view->assign('offset', $offset);
      $this->view->assign('list', $this->getLink($this->getValue('list')));

    }

    /**
     * @param string $className
     * @return void
     */
    public static function autoload($className) {

      $filename = \BB\file::path($className, '/');
      $filename = basename($filename);

      foreach(self::$autoloadFolders as $folder):
        $pathOfModel = APP_ROOT.\BB\file::path($folder.$filename.'.php');
        if(file_exists($pathOfModel)):
          include_once($pathOfModel);
        endif;
      endforeach;
    }

    /**
     * @param string $nameOfController
     * @return void
     */
    protected function callAutoload($nameOfController) {
      \BB\autoload::register(
        array(
          'BB\custom\engine\\'.$nameOfController,
          'autoload'
        )
      );
    }

  }

?>