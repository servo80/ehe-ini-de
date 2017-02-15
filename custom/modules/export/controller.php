<?php

  namespace BB\controller;

  if(@secure !== true)
    die('forbidden');

  /**
   * @author Dirk Münker
   */
  class export extends \BB\controller {

    /**
     * @return void
     */
    public function execDelete() {

      $coreHttp = \BB\http\request::get();
      $ex_id = (int)$coreHttp->getGet('ex_id');

      $modelExport = \BB\model\export::get();
      $modelExport->execDelete($ex_id);

    }

    /**
     * @return void
     */
    public function execExport() {

      $coreHttp = \BB\http\request::get();
      $ex_id = (int)$coreHttp->getPost('ex_id');
      $ex_overwrite = (int)$coreHttp->getPost('ex_overwrite');

      $modelExport = \BB\model\export::get();
      if($ex_overwrite == 1 || $ex_id == -2)
        $modelExport->execSave($ex_id, $coreHttp->getParams());
      $filename = $modelExport->writeXlsx(array(
        'languageID' => (int)$coreHttp->getPost('lan_id'),
        'tableID' => (int)$coreHttp->getPost('tbl_id'),
        'childTableIDs' => (array)$coreHttp->getPost('child_tbl_ids'),
        'fieldIDs' => (array)$coreHttp->getPost('f_id'),
        'htmlAsPlain' => (int)$coreHttp->getPost('ex_htmlAsPlain') == 1,
        'IDAsPlain' => (int)$coreHttp->getPost('ex_IDAsPlain') == 1
      ));

      \BB\http\header::download($filename, 'Export.xlsx', true);

    }

    /**
     * @return void
     */
    public function execLoadProfile() {

      $coreHttp = \BB\http\request::get();
      $ex_id = (int)$coreHttp->getGet('ex_id');

      $modelExport = \BB\model\export::get();
      $profile = $modelExport->getProfile($ex_id);

      $this->json(json_decode($profile['ex_profile']));
    }

    /**
     * @return void
     */
    public function viewIndex() {

      $modelField = \BB\model\field::get();
      $modelTable = \BB\model\table::get();
      $modelLanguage = \BB\model\language::get();
      $modelExport = \BB\model\export::get();

      $tables = $modelTable->getTableIDsAsNames(array(\BB\model\right::wildcard));
      $fieldIDsOfTables = array();

      foreach($tables as $tableID => $tableName):
        $fieldIDsOfTables[$tableID] = $modelField->getFieldIDs($tableID);
      endforeach;

      $this->view
        ->add('fields', $modelField->getFields())
        ->json('fieldIDsOfTables', $fieldIDsOfTables)
        ->assign('select:tables', \BB\form::select('tbl_id', $tables, 0, 300))
        ->assign('select:childtables', \BB\form::select('child_tbl_ids', $tables, 0, 300, null, 10))
        ->assign('select:languages', \BB\form::select('lan_id', $modelLanguage->getLanguageIDsAsNames(), 0, 300))
        ->assign('select:profiles',
          \BB\form::select(
            'ex_id',
            array(-1 => '- '.\BB\i18n::__(11).' -')+
            array(-2 => '- '.\BB\i18n::__(10).' -')+
            $modelExport->getProfileIDsAsNames(),
            0, 300, 'onchange="$.exportJS.change(this.value);" class="float-none"'
          )
        );
    }
  }

?>