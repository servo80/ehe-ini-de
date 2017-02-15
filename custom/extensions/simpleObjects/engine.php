<?php

  namespace BB\custom\engine;

  if(@secure !== true)
    die('forbidden');

  class simpleObjects extends \BB\model\engine {

    protected $path = 'custom/extensions/simpleObject1.0/';

    /**
     * @access public
     * @return string
     */
    public function viewStartTeaser(){

    }

  }

?>