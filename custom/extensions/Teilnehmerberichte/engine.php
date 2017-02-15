<?php

  namespace BB\custom\engine;

  if(@secure !== true)
    die('forbidden');

  class Teilnehmerberichte extends \BB\model\engine {

    protected $path = 'custom/extensions/Teilnehmerberichte/';

    protected static $autoloadFolders = array(
      'custom/dao/',
      'custom/dao/model/',
      'custom/dao/element/'
    );

    /**
     * @access public
     * @return string
     */
    public function viewReports(){

      $reportsDao = \BB\custom\model\element\dao\Teilnehmerberichte::instance();
      $results = $reportsDao->getRows(1, array());
      $reports = array();

      foreach($results as $key => $result):
        $reports[$key] = $result->getData(1);
      endforeach;

      shuffle($reports);

      $this->view->add('reports', $reports);

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