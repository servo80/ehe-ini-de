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
      $orderField = new \BB\model\element\optSearchField();
      $newsDao = \BB\custom\model\element\dao\News::instance();

      $offset = $coreHttp->getInteger('offset');
      $field = $orderField->id('Newsdatum')->sortDesc();
      //$results = $newsDao->getRows(1, array($field), $offset, self::limit);

      $news = array();
      foreach($results as $newsElement):
        $newsData = $newsElement->getData(1);
        $newsData->id = $newsElement->getContentID();
        $news[] = $newsData;
      endforeach;

      $this->view->add('news', $news);
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