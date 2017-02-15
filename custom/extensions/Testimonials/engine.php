<?php

  namespace BB\custom\engine;

  if(@secure !== true)
    die('forbidden');

  class Testimonials extends \BB\model\engine {

    protected $path = 'custom/extensions/Veranstaltungen/';

    protected static $autoloadFolders = array(
      'custom/dao/',
      'custom/dao/model/',
      'custom/dao/element/'
    );

    /**
     * @access public
     * @return string
     */
    public function viewTestimonials(){

      $testimonialsDao = \BB\custom\model\element\dao\Testimonials::instance();
      $results = $testimonialsDao->getRows(1, array());

      $keys = array_keys($results);
      $randomKey = rand(min($keys), max($keys));
      $element = $results[$randomKey];
      $testimonial = $element->getData(1);

      $this->view->assign('testimonial', $testimonial->Testimonial);
      $this->view->assign('person', $testimonial->Zitierer);

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