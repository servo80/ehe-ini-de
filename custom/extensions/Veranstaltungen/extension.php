<?php

  namespace BB\custom\extension;

  if(@secure !== true)
    die('forbidden');

  class Veranstaltungen {

    /**
     * @return void
     * @access public
     */
    public function install($extensionID) {
    }
    /**
     * @return void
     * @access public
     */
    public function uninstall() {
    }
  }

?>