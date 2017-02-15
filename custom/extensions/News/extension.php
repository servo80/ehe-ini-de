<?php

  namespace BB\custom\extension;

  if(@secure !== true)
    die('forbidden');

  class News {

    /**
     * @return void
     * @access public
     */
    public function install($extensionID) {
    }
    /**
     * @return void
     * @return void
     * @access public
     */
    public function uninstall() {
    }
  }

?>