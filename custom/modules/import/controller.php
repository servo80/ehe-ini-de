<?php

  namespace BB\controller;

  if(@secure !== true)
    die('forbidden');

  /**
   * @author Dirk Münker
   */
  class import extends \BB\controller {

    private
      $report = array(),
      $filename = '',
      $cmp = array(
        'is' => 'Is',
        'starts' => 'Starts with',
        'ends' => 'Ends with',
        'contains' => 'Contains'
      );

    /**
     * @return void
     */
    public function execUpload() {

      $this->filename = strftime('%Y%m%d_%H%M%S').'.csv';

      $coreHttp = \BB\http\request::get();
      $file = $coreHttp->getFile('file');

      if(\BB\file::suffix($file['name']) != 'csv' || $coreHttp->moveFileTo('file', 'temp/import', $this->filename) == false)
        $this->filename = '';

    }

    /**
     * @return void
     */
    public function execDelete() {

      $coreHttp = \BB\http\request::get();
      $im_id = $coreHttp->getInteger('im_id');

      $modelimport = \BB\model\import::get();
      $modelimport->execDelete($im_id);

    }

    /**
     * @return void
     */
    public function execImport() {

      $coreHttp = \BB\http\request::get();
      $im_id = (int)$coreHttp->getPost('im_id');
      $im_overwrite = (int)$coreHttp->getPost('im_overwrite');

      $modelImport = \BB\model\import::get();
      if($im_overwrite == 1 || $im_id == -2)
        $modelImport->execSave($im_id, $coreHttp->getParams());
      $this->report = $modelImport->execImport($coreHttp->getParams());

    }

    /**
     * @return void
     */
    public function execLoadProfile() {

      $coreHttp = \BB\http\request::get();
      $im_id = $coreHttp->getInteger('im_id');

      $modelImport = \BB\model\import::get();
      $profile = $modelImport->getProfile($im_id);

      $this->json(json_decode($profile));

    }

    /**
     * @return void
     */
    public function viewIndex() {

      // @ToDo: Import in Funktionen unterteilen

      $this->view->assign('select:encoding', \BB\form::select('encoding', mb_list_encodings(), false, 296, '', null, true));
    }

    /**
     * @return void
     */
    public function viewUpload() {

      if($this->filename == ''):
        $this->view
          ->assign('template', 'UploadError')
          ->delete('filename');
      else:
        $this->view
          ->assign('template', 'Setup')
          ->assign('filename', $this->filename);
      endif;

      $this->wrapFramework(false);
      $this->wrapForm(false);
      $this->execJS(false);

    }

    /**
     * @return void
     */
    public function viewUploadError() {
    }

    /**
     * @return void
     */
    public function viewSetup() {

      $modelField = \BB\model\field::get();
      $modelTable = \BB\model\table::get();
      $modelLanguage = \BB\model\language::get();
      $modelImport = \BB\model\import::get();

      $tables = $modelTable->getTableIDsAsNames(array(\BB\model\right::wildcard));

      $coreHttp = \BB\http\request::get();

      $delimiter = $coreHttp->getPost('delimiter');
      $encoding = $coreHttp->getPost('encoding');
      $filename = $coreHttp->getPost('filename');

      $columns = \BB\fileformat\csv::getColumns('temp/import/'.$filename, $delimiter, true);
      $fields =
        array('' =>  \BB\i18n::__(22).'...')+
        array(\BB\model\import::columnTable =>  \BB\i18n::__(3).' (ID)')+
        array(\BB\model\import::columnDataset =>  \BB\i18n::__(18).' (ID)')+
        $modelField->getFieldIDsAsNames();

      $this->view
        ->assign('filename', $filename)
        ->assign('delimiter', $delimiter)
        ->assign('encoding', $encoding)
        ->add('columns', $columns)
        ->add('fields', \BB\form::select('f_id_#column#', $fields, 0, 210))
        ->add('languages', \BB\form::select('lan_id_#column#', $modelLanguage->getLanguageIDsAsNames(), 0, 90))
        ->assign('select:tables', \BB\form::select('tbl_id', $tables, 0, 300))
        ->assign('select:parenttables', \BB\form::select('ptbl_id', array('' => '- '.\BB\i18n::__(21).' -')+$tables, 0, 300, 'onchange="$.importJS.parents_load(this.value);"'))
        ->assign('select:childtables', \BB\form::select('ctbl_id', array('' => '- '.\BB\i18n::__(25).' -')+$tables, 0, 300, 'onchange="$.importJS.children_load(this.value);"'))
        ->assign('select:profiles',
          \BB\form::select(
            'im_id',
            array(\BB\model\import::columnDataset => '- '.\BB\i18n::__(20).' -')+
            array(\BB\model\import::columnTable => '- '.\BB\i18n::__(19).' -')+
            $modelImport->getProfileIDsAsNames(),
            0, 280, 'onchange="$.importJS.change(this.value);" class="float-none"'
          )
        );

    }

    /**
     * @return void
     */
    public function viewParentFieldIDs() {

      $coreHttp = \BB\http\request::get();

      $ptbl_id = $coreHttp->getInteger('ptbl_id');
      $column = $coreHttp->getInteger('column');
      $f_id = $coreHttp->getInteger('f_id');
      $cmp = $coreHttp->getString('cmp');
      $class = $coreHttp->getString('class');

      $modelField = \BB\model\field::get();

      $this->view
        ->assign('class', $class)
        ->assign('column', $column)
        ->assign('field', \BB\form::select('pf_id_'.$column, $modelField->getFieldIDsAsNames($ptbl_id), $f_id, 210))
        ->assign('cmpfield', \BB\form::select('pcmp_'.$column, $this->cmp, $cmp, 90, 'class="noselect2"'));

      $this->wrapFramework(false);
      $this->wrapForm(false);
      $this->execJS(false);
    }

    /**
     * @return void
     */
    public function viewChildFieldIDs() {

      $coreHttp = \BB\http\request::get();

      $ctbl_id = $coreHttp->getInteger('ctbl_id');
      $column = $coreHttp->getInteger('column');
      $f_id = $coreHttp->getInteger('f_id');
      $cmp = $coreHttp->getString('cmp');
      $class = $coreHttp->getString('class');

      $modelField = \BB\model\field::get();

      $this->view
        ->assign('class', $class)
        ->assign('column', $column)
        ->assign('field', \BB\form::select('cf_id_'.$column, $modelField->getFieldIDsAsNames($ctbl_id), $f_id, 210))
        ->assign('cmpfield', \BB\form::select('ccmp_'.$column, $this->cmp, $cmp, 90, 'class="noselect2"'));

      $this->wrapFramework(false);
      $this->wrapForm(false);
      $this->execJS(false);
    }

    /**
     * @return void
     */
    public function viewReport() {
      $this->view->add('report', $this->report);
    }
  }

?>