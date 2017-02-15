<?php

  namespace BB\custom\controller;

  use \BB\model as bbModels;
  use \BB\controller as bbController;
  use \BB\session\request as bbSession;
  use \BB\http\request as bbRequest;
  use \BB\file as bbFile;
  use \BB\autoload as bbAutoload;

  if(@secure !== true)
    die('forbidden');

  /**
   * @author Philipp Frick
   */
  class crm extends bbController {

    protected
			$callableWithoutLogin = array(
				'execCountNewsletter'
      );        
  
    const pathModule = 'custom/modules/crm/';

    const sessionKey = 'crm';

    protected static $autoloadFolders = array(
      'custom/dao/',
      'custom/dao/model/',
      'custom/dao/element/'
    );

    protected $useSmartyEngine = true;

    /**
     * @param $controllerInfo
     * @param $module
     * @param $template
     * @param $moduleBC
     * @param bool $wrapFramework
     */
    public function __construct($controllerInfo, $module, $template, $moduleBC, $wrapFramework = false) {
      $this->callAutoload('crm');

      return parent::__construct($controllerInfo, $module, $template, $moduleBC, $wrapFramework);
    }

    public function execCountNewsletter() {
    
      $coreHttp = \BB\http\request::get();
      $count = $coreHttp->getString('count');
      file_put_contents('test.txt', $count);
    
    }
    
    /**
     * @param string $className
     * @return void
     */
    public static function autoload($className) {

      $filename = bbFile::path($className, '/');
      $filename = basename($filename);

      foreach(self::$autoloadFolders as $folder):
        $pathOfModel = APP_ROOT.bbFile::path($folder.$filename.'.php');
        if(file_exists($pathOfModel)):
          include_once($pathOfModel);
        endif;
      endforeach;
    }

    public function viewConfirm() {

    }

    /**
     * @return void
     */
    public function execTestNewsletter() {

      $client = $this->getClient();
      $tableID = $this->getSelectedTableID();

      \BB\custom\model\customContentField::clearCachedSelectValues($client, $tableID);

    }

    /**
     * @return void
     */
    public function viewIndex() {
    }   

    /**
     * @return int
     */
    protected function getUserID() {

      $coreSession = bbSession::get();
      $userID = $coreSession->getUserID();

      return $userID;

    }

    /**
     * @param string $nameOfController
     * @return void
     */
    protected function callAutoload($nameOfController) {
      bbAutoload::register(
        array(
          'BB\custom\controller\\'.$nameOfController,
          'autoload'
        )
      );
    }

  }
?>