<?php

  namespace BB\custom\controller;

  use BB\custom\model\crmSpooler;

  if(@secure !== true)
    die('forbidden');

  /**
   * @author Dirk Münker
   */
  class crmData extends \BB\controller {
    
    const crmTable = 'Kontakte';

    protected $callableWithoutLogin = array(
      'execSetRead'
    );

    protected
      $submenu = array(
        'Index' => array(
          array('name' => '{__(11)}', 'tpl' => 'Edit', 'action' => '','icon' => 'fa-plus-square','rights' => array('create')),
          array('name' => '{__(100)}', 'tpl' => 'SortColumns', 'action' => '', 'icon' => 'fa-sort-numeric-desc'),
          array('name' => '{__(96)}', 'tpl' => 'Index', 'action' => 'Unselect', 'icon' => 'fa-circle-o')
        )
      ),
      $context = array(
        'Index' => array(
          array('name' => '{__(16)}', 'click' => '$.crmData.list.edit()','icon' => 'fa-pencil','context' => 'content'),
          array('name' => '{__(54)}', 'click' => '$.crmData.list.versions()','icon' => 'fa-history','context' => 'content'),
          //array('name' => '{__(17)}', 'click' => '$.content.list.copy_no_relations()','icon' => 'fa-clipboard','rights' => array('create'),'context' => 'content'),
          //array('name' => '{__(18)}', 'click' => '$.content.list.copy_with_relations()','icon' => 'fa-clipboard','rights' => array('create'),'context' => 'content'),
          //array('name' => '{__(82)}', 'click' => '$.content.list.move()','icon' => 'fa-share','rights' => array('edit'),'context' => 'content'),
          array('name' => '{__(19)}', 'click' => '$.crmData.list.deleteDataset()','icon' => 'fa-times','rights' => array('delete'),'context' => 'content')
        ),
        'Relations' => array(
          array('name' => '{__(16)}', 'click' => '$.crmData.relations.editDataset()','icon' => 'fa-pencil','context' => 'parents'),
          array('name' => '{__(16)}', 'click' => '$.crmData.relations.editDataset()','icon' => 'fa-pencil','context' => 'children'),
          array('name' => '{__(88)}', 'click' => '$.crmData.relations.edit()','icon' => 'fa-pencil','context' => 'children'),
          array('name' => '{__(21)}', 'click' => '$.crmData.relations.remove()','icon' => 'fa-unlink','rights' => array('delete'),'context' => 'children')
        ),
        'Fields' => array(
          array('name' => '{__(98)}', 'click' => '$.crmData.fields.editField()', 'icon' => 'fa-file-text-o', 'context' => 'field'),
          array('name' => '{__(22)}', 'click' => '$.crmData.fields.deleteField()','icon' => 'fa-times','rights' => array('delete'),'context' => 'field')
        )
      )
    ;

    protected $tableID = 0;

    /**
     * @return array
     */
    private $searchFields = null;

    /**
     * @return void
     */
    public function execCatchByCoParentID() {

      $coreHttp = \BB\http\request::get();
      $co_parent_id = $coreHttp->getInteger('co_parent_id');

      $_SESSION[$this->module]['collection_add']['co_parent_id'] = $co_parent_id;

    }

    /**
     * @return void
     */
    public function execCatchByCnID() {

      $coreHttp = \BB\http\request::get();
      $modelContent = \BB\model\content::get();

      $contentID = (int)$coreHttp->getGet('cn_id');

      $module = 'collection';
      if($coreHttp->issetGet('catchmod'))
        $module = $coreHttp->getGet('catchmod');

      $_SESSION[$this->module]['Index']['module'] = $module;
      $_SESSION[$this->module]['Index']['remember'] =
        $_SESSION[$this->module]['Index'];

      $tableID = $modelContent->getTableID($contentID);
      $this->execSwitchTable($tableID, 'Index');

    }

    /**
     * @return integer
     */
    public function execCopyNoRelations() {

      $modelContent = \BB\model\content::get();
      $modelField = \BB\model\field::get();
      $coreHttp = \BB\http\request::get();
      $coreSession = \BB\session\request::get();

      $contentID = $coreHttp->getGet('cn_id');
      $newContentID = $modelContent->execCopy($contentID, $coreSession->getUserID());

      $fieldIDs = $modelField->getFieldIDs($contentID, 'content');
      $modelField->execRelateByArray($newContentID, 'content', $fieldIDs);

      return $newContentID;
    }

    /**
     * @return void
     */
    public function execCopyWithRelations() {

      $coreHttp = \BB\http\request::get();
      $modelContent = \BB\model\content::get();

      $contentID = $this->execCopyNoRelations();
      $modelContent->execCopyRelations($coreHttp->getGet('cn_id'), $contentID);

    }

    /**
     * @return void
     */
    public function execCreatePassword() {

      $modelContent = \BB\model\content::get();
      $this->plain($modelContent->execCreatePassword());

    }

    /**
     * @return void
     */
    public function execDelete() {

      $coreHttp = \BB\http\request::get();
      $contentIDs = $coreHttp->getString('cn_ids');
      $contentIDs = (array)explode(',', $contentIDs);

      $modelContent = \BB\model\content::get();
      foreach($contentIDs as $contentID):
        $modelContent->execDelete($_SESSION[$this->module][$this->template]['tbl_id'], $contentID);
      endforeach;

    }

    /**
     * @return void
     */
    public function execRemoveFromSelection() {

      $coreHttp = \BB\http\request::get();
      $contentIDs = $coreHttp->getString('cn_ids');
      $contentIDs = (array)explode(',', $contentIDs);

      foreach($contentIDs as $contentID):
        unset($_SESSION[$this->module][$this->template]['selection'][$contentID]);
      endforeach;

    }

    /**
     * @return void
     */
    public function execAddLinesToSelection() {

      $coreHttp = \BB\http\request::get();
      $contentIDs = $coreHttp->getString('cn_ids');
      $contentIDs = (array)explode(',', $contentIDs);

      foreach($contentIDs as $contentID):
        $_SESSION[$this->module][$this->template]['selection'][$contentID] = $contentID;
      endforeach;

    }

    /**
     * @return void
     */
    public function execDeleteField() {

      $coreHttp = \BB\http\request::get();
      $fieldID = $coreHttp->getGet('f_id');
      $contentID = $coreHttp->getGet('cn_id');

      $modelField = \BB\model\field::get();
      $modelField->execUnrelate($contentID, 'content', $fieldID);

    }

    /**
     * @return void
     */
    public function execDragDrop() {
      $coreHttp = \BB\http\request::get();

      switch($coreHttp->getGet('name')):
        case 'children':
          $modelContent = \BB\model\content::get();

          $ids = explode(',',$coreHttp->getGet('id_src'));

          foreach($ids as $id):
            $modelContent->execDragDrop(
              $_SESSION[$this->module]['RelationsAdd']['ptbl_id'],
              $_SESSION[$this->module]['RelationsAdd']['tbl_id'],
              $coreHttp->getGet('rel'),
              $id,
              $coreHttp->getGet('id_tgt')
            );
            $this->setRoute($this->module, 'Relations');
          endforeach;
          break;
        case 'field':
          $modelField = \BB\model\field::get();
          $modelField->execDragDrop(
            $coreHttp->getGet('id_src'),
            $coreHttp->getGet('id_tgt'),
            $coreHttp->getGet('rel'),
            'content'
          );
          $this->setRoute($this->module, 'Fields');
          break;
        case 'column':
          $fieldIDsAsNames = $this->getSortedFields();
          $fieldIDSrc = $coreHttp->getInteger('id_src');
          $fieldIDTgt = $coreHttp->getInteger('id_tgt');
          $tableID = $coreHttp->getInteger('rel');
          $sortedFieldIDs = array();
          $fieldIDs = array_keys($fieldIDsAsNames);

          if(!in_array($fieldIDSrc, $fieldIDs))
            return;
          if(!in_array($fieldIDTgt, $fieldIDs) && $fieldIDTgt != 0)
            return;

          if($fieldIDTgt == 0):
            $sortedFieldIDs[] = $fieldIDSrc;
          endif;

          foreach($fieldIDs as $fieldID):
            if($fieldID != $fieldIDSrc)
              $sortedFieldIDs[] = (int)$fieldID;

            if($fieldID == $fieldIDTgt)
              $sortedFieldIDs[] = $fieldIDSrc;
          endforeach;

          $jsonOfSortedFieldIDs = json_encode($sortedFieldIDs);
          setcookie('content[sortedFieldIDs]['.$tableID.']', $jsonOfSortedFieldIDs);
          $_COOKIE['content']['sortedFieldIDs'][$tableID] = $jsonOfSortedFieldIDs;

          $this->setRoute($this->module, 'SortColumns');
          break;
      endswitch;
    }

    /**
     * @return void
     */
    public function execEditCellSave() {

      $coreHttp = \BB\http\request::get(
        new \BB\http\nofilter()
      );
      $languageID = $coreHttp->getInteger('lan_id');
      $tableID = $coreHttp->getInteger('tbl_id');
      $contentID = $coreHttp->getInteger('cn_id');
      $fieldID = $coreHttp->getInteger('f_id');

      $modelContent = \BB\model\content::get();
      $modelTable = \BB\model\table::get();
      $modelField = \BB\model\field::get();

      $modelContent->execSaveForm(array(
        'tableType' => $modelTable->getType($tableID),
        'languageID' => $languageID,
        'tableID' => $tableID,
        'contentID' => $contentID,
        'fields' => array($modelField->getField($fieldID)),
        'data' => $coreHttp->getParams()
      ));
      $modelContent->clearCache($tableID, $fieldID, $contentID, $languageID);
      $value = $modelContent->getValue($tableID, $fieldID, $contentID, $languageID);

      $this->plain($modelContent->getClearValue($fieldID, $value, $languageID));

    }

    /**
     * @return void
     */
    public function execFlipOffset() {

      $coreHttp = \BB\http\request::get();

      $max = $this->getResultsMaxCount();
      $offset = $coreHttp->getInteger('offset');

      $_SESSION[$this->module][$this->template]['offset'] = max(
        0,
        min(
          $offset,
          floor($max / $_SESSION[$this->module][$this->template]['limit'])*$_SESSION[$this->module][$this->template]['limit']
        )
      );

    }

    /**
     * @return void
     */
    public function execFlipPage() {

      $coreHttp = \BB\http\request::get();

      $max = $this->getResultsMaxCount();
      $page = $coreHttp->getInteger('page')-1;

      $_SESSION[$this->module][$this->template]['offset'] = max(
        0,
        min(
          $page*$_SESSION[$this->module][$this->template]['limit'],
          floor($max / $_SESSION[$this->module][$this->template]['limit'])*$_SESSION[$this->module][$this->template]['limit']
        )
      );
    }

    /**
     * @return void
     */
    public function execInProgress() {

      $coreHttp = \BB\http\request::get();
      $coreSession = \BB\session\request::get();

      $tableID = $coreHttp->getInteger('tbl_id');

      $modelContent = \BB\model\content::get();
      $modelContent->execInProgress(
        $tableID,
        (array)explode(',', (string)$coreHttp->getParam('cn_ids')),
        $coreSession->getUserID()
      );
      $this->void();

    }

    /**
     * @return void
     */
    public function execMove() {

      $coreHttp = \BB\http\request::get();
      $contentID = $coreHttp->getGet('cn_id');
      $tableID = $coreHttp->getGet('tbl_id');

      $modelContent = \BB\model\content::get();
      $modelContent->execMove($contentID, $tableID);

    }

    /**
     * @return void
     */
    public function execOpenCloseGroup() {

      $coreHttp = \BB\http\request::get();
      $tblgr_id = $coreHttp->getGet('tblgr_id');

      if(in_array($tblgr_id, @(array)$_SESSION[$this->module][$this->template]['tblgr_ids'])):
        unset($_SESSION[$this->module][$this->template]['tblgr_ids'][$tblgr_id]);
      else:
        $_SESSION[$this->module][$this->template]['tblgr_ids'][$tblgr_id] = $tblgr_id;
      endif;

    }

    /**
     * @return void
     */
    public function execRelationAdd() {

      $coreHttp = \BB\http\request::get();
      $contentIDs = $coreHttp->getString('cn_ids');

      $modelContent = \BB\model\content::get();
      $modelContent->execRelate(
        $_SESSION[$this->module]['RelationsAdd']['ptbl_id'],
        $_SESSION[$this->module]['RelationsAdd']['tbl_id'],
        $_SESSION[$this->module]['RelationsAdd']['cn_id'],
        explode(',', $contentIDs)
      );
    }

    /**
     * @return void
     */
    public function execRelationRemove() {

      $coreHttp = \BB\http\request::get();
      $contentIDOfRelation = $coreHttp->getInteger('cr_id');

      $modelContent = \BB\model\content::get();
      $modelContent->execUnrelate($contentIDOfRelation);

    }

    /**
     * @return void
     * @author Devon Le Duke
     */
    public function execRelationsRemove() {
      $coreHttp = \BB\http\request::get();
      $crIDs = $coreHttp->getGet('cr_ids');
      $crIDs = explode('x', $crIDs);

      $modelContent = \BB\model\content::get();

      foreach($crIDs as $crID):
        $modelContent->execUnrelate($crID);
      endforeach;
    }

    /**
     * @return void
     */
    public function execSave() {

      $coreHttp = \BB\http\request::get(
        new \BB\http\nofilter()
      );
      $coreSession = \BB\session\request::get();

      $modelField = \BB\model\field::get();
      $modelTable = \BB\model\table::get();
      $modelContent = \BB\model\content::get();

      $languageIDs = (array)$this->getLanguageIDs();
      $languageIDs = $this->getAllowedLanguageIDsIfCurrentAreForbidden($languageIDs);

      $tableID = $coreHttp->getInteger('tbl_id');
      $relationTableID = $coreHttp->getInteger('rtbl_id');
      $relationContentID = $coreHttp->getInteger('rcn_id');
      $contentIDs = $coreHttp->getString('cn_ids');
      $contentIDs = $modelContent->getArrayFromComma($contentIDs, array(0));

      $fieldGroup = $coreHttp->getInteger('f_group');
      $data = $coreHttp->getParams();

      $tableType = $modelTable->getType($tableID);

      if($coreHttp->issetPost('f_1_'.\BB\model\field::user)):

        $modelUser = \BB\model\user::get();
        $tableIDs = $modelUser->getBackendUserTableIDs();
        $username = $coreHttp->getString('f_1_'.\BB\model\field::user);
        $User = $modelUser->execSearchUserByUsername($username, $tableIDs);

        if(@$User['id'] != 0 && !in_array(@$User['id'], $contentIDs)):
          $this->plain('exists');
        endif;
      endif;

      $savedContentIDs = array();
      $allowedEditLanguageIDs = $this->getAllowedEditLanguageIDs();

      foreach($contentIDs as $contentID):
        foreach($languageIDs as $languageID):
          $isAllowedToEditLanguage = $modelContent->isAllowedToEditLanguage($languageID, $allowedEditLanguageIDs);
          if(!$isAllowedToEditLanguage)
            continue;
          $fieldsOfGroup = $modelField->getFieldsOfGroup($tableID, $fieldGroup);

          $contentID = $modelContent->execSaveForm(
            array(
              'tableType'  => $tableType,
              'languageID' => $languageID,
              'tableID'    => $tableID,
              'contentID'  => $contentID,
              'contentIDs' => $contentIDs,
              'fields'     => $fieldsOfGroup,
              'data'       => $data,
              'userID'     => $coreSession->getUserID()
            )
          );
          $savedContentIDs[] = $contentID;
        endforeach;
      endforeach;

      // Datensatz zu Eltern verknüpfen
      if($relationContentID != 0):
        $modelContent->execRelate(
          $relationTableID,
          $tableID,
          $relationContentID,
          $savedContentIDs
        );
        $_SESSION[$this->module]['Index']['resetAfterRelating'] = array(
          'rcn_id' => $relationContentID,
          'rtbl_id' => $relationTableID
        );
        $this->setRoute('content', 'Edit');
        return;
      endif;

      $editContentID = $coreHttp->getInteger('editContentID');

      if($editContentID != 0):
        $savedContentIDs = array($editContentID);
        $this->tableID = $modelContent->getTableID($editContentID);
        $this->setRoute('content', 'Edit');
      endif;

      if(isset($_SESSION[$this->module]['Index']['module']) && $this->template == 'Index'):
        $this->setRoute($_SESSION[$this->module]['Index']['module'], 'Index');
        $this->execPrepareSessionCleanUp('Index');
      endif;

      $coreHttp->setParam('cn_ids', implode(',', $savedContentIDs));
    }

    /**
     * @author Philipp Frick
     * @return void
     */
    public function execSearch() {

      $coreHttp = \BB\http\request::get();

      if($this->template == 'Search')
        $this->template = $coreHttp->getString('useTpl');

      $tableID = @(int)$_SESSION[$this->module][$this->template]['tbl_id'];
      $_SESSION[$this->module][$this->template][$tableID]['q'] = $coreHttp->getArray('searchFields');
      $_SESSION[$this->module][$this->template][$tableID]['searchTypes'] = $coreHttp->getArray('searchTypes');
      $_SESSION[$this->module][$this->template]['offset'] = 0;

    }

    /**
     * @return void
     */
    public function execSetMail() {

      $coreHttp = \BB\http\request::get();

      $modelArticle = \BB\model\article::get();
      $modelArticle->execSaveUserMail(
        $coreHttp->getGet('article_id'),
        $coreHttp->getGet('u_id'),
        $coreHttp->getGet('rel'),
        $coreHttp->getGet('on')
      );

      $this->void();
    }

    /**
     * @return void
     */
    public function execSwitchEditLanguage() {

      $coreHttp = \BB\http\request::get();
      $languageIDs = explode(',', $coreHttp->getParam('lan_ids'));
      $languageID = $coreHttp->getInteger('lan_id');

      if($languageID != 0):
        if(in_array($languageID, $languageIDs)):
          $key = array_search($languageID, $languageIDs);
          unset($languageIDs[$key]);
        else:
          $languageIDs[] = $languageID;
        endif;
      endif;

      if(empty($languageIDs))
        $languageIDs = array(1);

      $coreHttp->setParam('lan_id', null);
      $_SESSION[$this->module][$this->template]['lan_ids'] = $languageIDs;
    }

    /**
     * @return void
     */
    public function execSwitchField() {

      $coreHttp = \BB\http\request::get();

      $tableID = @(int)$_SESSION[$this->module][$this->template]['tbl_id'];
      $_SESSION[$this->module][$this->template][$tableID]['sf_id'] = $coreHttp->getInteger('sf_id');

      $modelField = \BB\model\field::get();
      $modelContent = \BB\model\content::get();
      $coreSession = \BB\session\request::get();

      if($_SESSION[$this->module][$this->template][$tableID]['sf_id'] == -1):
        $field = array(
          'f_monolingual' => 1,
          'f_type' => 'varchar',
          'f_form' => 'input',
          'f_width' => 150,
          'f_height' => 1,
        );
      else:
        $field = $modelField->getField($_SESSION[$this->module][$this->template][$tableID]['sf_id']);
      endif;

      $field['f_width'] = -1;
      switch($field['f_form']):
        case 'tree_ids':
          $field['f_form'] = 'tree_id';
          break;
        case 'cn_ids':
          $field['f_form'] = 'cn_id';
          break;
        case 'page_ids':
          $field['f_form'] = 'page_id';
          break;
        case 'tbl_ids':
          $field['f_form'] = 'tbl_id';
          break;
        case 'ptpl_ids':
          $field['f_form'] = 'tpl_id';
          break;
        case 'lan_ids':
          $field['f_form'] = 'lan_id';
          break;
        case 'multiselect':
          $field['f_form'] = 'select';
          break;
        case 'input':
        case 'notice':
        case 'text':
        case 'iframe':
        case 'radio':
        case 'csv':
        case 'cumulus':
        case 'file':
        case 'datetime':
        case 'date':
        case 'fon':
        case 'fax':
        case 'password':
          $field['f_form'] = 'input';
          break;
      endswitch;

      $this->plain(
        $modelContent->getField(array(
          'languageID' => 1,
          'fieldName' => 'q',
          'fieldValue' => $_SESSION[$this->module][$this->template][$tableID]['q'],
          'field' => $field,
          'userID' => $coreSession->getUserID()
        ))
      );
    }

    /**
     * @return void
     */
    public function execSwitchLanguage() {

      $coreHttp = \BB\http\request::get();
      $_SESSION[$this->module][$this->template]['lan_id'] = $coreHttp->getInteger('lan_id');

    }

    /**
     * @return void
     */
    public function execSwitchLimit() {

      $coreHttp = \BB\http\request::get();
      $_SESSION[$this->module][$this->template]['limit'] = min(200, $coreHttp->getInteger('limit'));
      $_SESSION[$this->module][$this->template]['offset'] = 0;

    }

    /**
     * @return void
     */
    public function execSwitchMailsTable() {

      $coreHttp = \BB\http\request::get();
      $tableID = $coreHttp->getInteger('tbl_id');

      $_SESSION[$this->module][$this->template]['tbl_id'] = $tableID;

    }

    /**
     * @return void
     */
    public function execSwitchOrder() {

      $coreHttp = \BB\http\request::get();
      $orderFieldID = $coreHttp->getGet('of_id');
      $odirection = $coreHttp->getString('odirection');
      $tableID = @(int)$_SESSION[$this->module][$this->template]['tbl_id'];

      if(!in_array($odirection, array('asc', 'desc')))
        $odirection = 'asc';

      if($orderFieldID == $_SESSION[$this->module][$this->template][$tableID]['of_id']):
        $_SESSION[$this->module][$this->template][$tableID]['odirection'] = $odirection;
      else:
        $_SESSION[$this->module][$this->template][$tableID]['odirection'] = 'asc';
      endif;

      $_SESSION[$this->module][$this->template][$tableID]['of_id'] = $orderFieldID;

    }

    /**
     * @return void
     */
    public function execSwitchRelationTable() {

      $coreHttp = \BB\http\request::get();
      $type = $coreHttp->getGet('type');

      $_SESSION[$this->module][$type]['rtbl_id'] = $coreHttp->getInteger('rtbl_id');
      $_SESSION[$this->module]['RelationsAdd']['offset'] = 0;
    }

    /**
     * @param int $tableID
     * @param string $template
     * @return void
     */
    public function execSwitchTable($tableID = 0, $template = '') {
      $tableID = (int)$tableID;
      $template = (string)$template;

      if($tableID == 0):
        $coreHttp = \BB\http\request::get();
        $tableID = $coreHttp->getInteger('tbl_id');
      endif;
      if($template == ''):
        $template = $this->template;
      endif;

      $_SESSION[$this->module][$template]['tbl_id'] = $tableID;
      $_SESSION[$this->module][$template]['offset'] = 0;

      $this->execPrepareSessionFieldID();
    }

    /**
     * @param $version
     * @return array
     */
    public function mapVersion($version) {
      $modelContent = \BB\model\content::get();
      $modelVersion = \BB\model\version::get();

      $owner = $modelContent->getOwnerByUserID($version['cnv_user_id']);
      $version['username'] = $owner['firstname'].' '.$owner['lastname'];
      $version['dataset'] = $modelVersion->getVersionOfDataset(
        $version['cnv_id'],
        $version['cnv_lan_id'],
        $version['cnv_version']
      );
      return $version;
    }

    /**
     * @param $module
     */
    public function setI18n($module) {
      \BB\i18n::set(
        \BB\file::path(
          APP_ROOT.'application/modules/'.$module.'/languages/'.
          \BB\info::getLanguage().'.xml'
        ),
        $module
      );
    }

    /**
     * @return void
     */
    public function viewCollection() {

      $coreHttp = \BB\http\request::get();
      $contentID = $coreHttp->getInteger('cn_id');

      $modelContent = \BB\model\content::get();
      $modelCollection = \BB\model\collection::get();
      $modelTable = \BB\model\table::get();

      $tableID = (int)$modelContent->getTableID($contentID);
      $collectionTableIDs = (array)$modelTable->getTableIDsByType(\BB\model\table::tableTypeCollection);

      $collectionIDs = $modelContent->getCollections($tableID, $contentID, false, true);
      $collections = array();
      foreach($collectionIDs as $collectionID):
        $parentCollectionID = $modelCollection->getParentID($collectionID);
        $collections[$collectionID] = $modelContent->identify(\BB\model\table::tableTypeCollection, $collectionTableIDs[0], $parentCollectionID);
      endforeach;

      $this->view->add('dataset', $modelContent->getIdentificationValues($contentID));
      $this->view->add('collections', (array)$collections);

    }

    /**
     * @return void
     */
    public function viewCollectionAdd() {

      $this->execPrepareSession();
      $this->execAssignSearchField();
      $this->execAssignLanguageField(
        $_SESSION[$this->module][$this->template]['tbl_id'],
        $_SESSION[$this->module][$this->template]['lan_id']
      );
      $this->execAssignTables();
      $this->execAssignResults(false, false);
      $this->execAssignSession();

    }

    /**
     * @return void
     */
    public function viewEdit() {

      $coreHttp = \BB\http\request::get(
        new \BB\http\nofilter()
      );

      $modelField = \BB\model\field::get();
      $modelTable = \BB\model\table::get();
      $modelContent = \BB\model\content::get();

      $languageIDs = $this->getLanguageIDs();
      $languageIDs = $this->getAllowedLanguageIDsIfCurrentAreForbidden($languageIDs);
      $contentIDs = array();
      $collectionTableID = 0;

      if($coreHttp->issetGet('tbl_id')):
        $collectionTableID = $coreHttp->getInteger('tbl_id');
        if($collectionTableID == 0)
          $collectionTableID = $modelContent->getTableID($coreHttp->getInteger('cn_id'));
      endif;

      if(isset($_SESSION[$this->module]['Index']['resetAfterRelating'])):

        $tableID = (int)$_SESSION[$this->module]['Index']['resetAfterRelating']['rtbl_id'];
        $contentIDs = (array)$_SESSION[$this->module]['Index']['resetAfterRelating']['rcn_id'];
        unset($_SESSION[$this->module]['Index']['resetAfterRelating']);

        $coreHttp->setParam('rcn_id', '');
        $coreHttp->setParam('rtbl_id', '');

      else:

        if($this->tableID != 0):
          $tableID = $this->tableID;
        elseif($coreHttp->issetParam('tbl_id')):
          $tableID = $coreHttp->getInteger('tbl_id');
        else:
          $tableID = (int)$_SESSION[$this->module]['Index']['tbl_id'];
        endif;

        if(isset($_SESSION[$this->module]['Index']['editContentID'])):
          $contentIDs = (array)$_SESSION[$this->module]['Index']['editContentID'];
          unset($_SESSION[$this->module]['Index']['editContentID']);
        elseif($coreHttp->issetParam('cn_ids')):
          $contentIDs = $coreHttp->getString('cn_ids');
          $contentIDs = $modelContent->getArrayFromComma($contentIDs);
        else:
          $contentID = $coreHttp->getInteger('cn_id');
          if($contentID != 0):
            $contentIDs = array($contentID);
          endif;
        endif;
      endif;

      if($collectionTableID != 0):
        $tableID = $collectionTableID;
      endif;

      $fieldGroup = $coreHttp->getInteger('f_group');
      $editContentID = $coreHttp->getInteger('editContentID');

      $modelContent->execInProgressCleanUp();
      $coreSession = \BB\session\request::get();

      $userID = $coreSession->getUserID();
      $tableType = $modelTable->getType($tableID);
      $fieldsOfGroup = $modelField->getFieldsOfGroup($tableID, $fieldGroup);

      $form = $modelContent->getForm(
        array(
          'tableType'  => $tableType,
          'languageID' => $languageIDs,
          'tableID'    => $tableID,
          'contentID'  => (int)$contentIDs[0],
          'contentIDs' => $contentIDs,
          'fields'     => $fieldsOfGroup,
          'userID'     => $userID
        )
      );

      $usersWorkingWithDataset = $modelContent->getUsersWorkingWithDataset(
        $tableID,
        $contentIDs[0],
        $userID
      );

      $this->view
        ->add('contentIDs', $contentIDs)
        ->assign('rcn_id', $coreHttp->getInteger('rcn_id'))
        ->assign('rtbl_id', $coreHttp->getInteger('rtbl_id'))
        ->assign('editContentID', $editContentID)
        ->assign('cn_ids', implode(',', $contentIDs))
        ->assign('tbl_id', $tableID)
        ->assign('tbl_name', $modelTable->getName($tableID))
        ->assign('form', $form)
        ->add('users', $usersWorkingWithDataset)
      ;

      $this->execAssignLanguageList($languageIDs);
      $this->execAssignGroupField($tableID, $fieldGroup);
      $this->assignSubmenuEdit($contentIDs, $tableID, $languageIDs);
    }

    /**
     * @return void
     */
    public function viewEditCell() {

      $coreHttp = \BB\http\request::get();
      $coreSession = \BB\session\request::get();

      $languageID = $coreHttp->getInteger('lan_id');
      $contentID = $coreHttp->getInteger('cn_id');
      $fieldID = $coreHttp->getInteger('f_id');

      $modelContent = \BB\model\content::get();
      $modelField = \BB\model\field::get();
      $modelTable = \BB\model\table::get();

      $tableID = (int)$modelContent->getTableID($contentID);

      $this->view
        ->assign('tbl_id', $tableID)
        ->assign('lan_id', $languageID)
        ->assign('cn_id', $contentID)
        ->assign('f_id', $fieldID)
        ->assign(
          'field',
          $modelContent->getForm(array(
            'tableType' => $modelTable->getType($tableID),
            'languageID' => array($languageID),
            'tableID' => $tableID,
            'contentID' => $contentID,
            'fields' => array($modelField->getField($fieldID)),
            'userID' => $coreSession->getUserID()
          ))
        );

      $this->wrapFramework(false);
      $this->wrapForm(false);
      $this->execJS(false);

    }

    /**
     * @return void
     */
    public function viewFields() {

      $coreHttp = \BB\http\request::get();
      $rel = $coreHttp->getInteger('rel');
      if($rel != 0):
        $contentID = $rel;
      else:
        $contentID = $coreHttp->getInteger('cn_id');
      endif;

      $modelContent = \BB\model\content::get();
      $modelField = \BB\model\field::get();

      $this->view->assign('cn_id', $contentID);
      $this->view->add('dataset', $modelContent->getIdentificationValues($contentID));
      $this->view->add('fields', $modelField->getFieldIDsAsNames($contentID, 'content'));
    }

    /**
     *
     */
    public function viewMailing() {

      $this->template = 'Index';

      $contentIDs = $_SESSION[$this->module][$this->template]['selection'];

      $this->view
        ->assign('number', count($contentIDs))
      ;

    }

    /**
     *
     */
    public function viewStatistic() {

      $this->template = 'Index';

      $modelContent = \BB\model\content::get();
      $newsletters = $modelContent->execSearch(
        array(
          'tableID' => 'Newsletter',
          'languageID' => 1
        )
      );

      $newsletterData = array();
      foreach($newsletters->contentIDs as $newsletterID):

        $timestamps = $modelContent->getTimestamps('Newsletter', $newsletterID);

        $reads = \BB\db::query(
          ' SELECT COUNT(msr_mail_id) as amountreads FROM '.\BB\config::get('db:prefix').'web_mailspooler_reads'.
          ' WHERE msr_cn_id = '.$newsletterID.
          ' AND msr_read = 1',
          true
        );

        $newsletterData[$newsletterID] = array(
          'timestamp' => $timestamps['create'],
          'subject' => $modelContent->getValue('Newsletter', 'Newsletter-Betreff', $newsletterID, 1),
          'text' => $modelContent->getValue('Newsletter', 'Newsletter-Text', $newsletterID, 1),
          'reads' => $reads['amountreads']
        );
      endforeach;


      $this->view
        ->add('newsletters', $newsletterData)
      ;

    }

    /*
     *
     */
    public function execSwitchMode() {

      $coreHttp = \BB\http\request::get();
      $modeType = $coreHttp->getString('modeType');

      $_SESSION[$this->module][$this->template]['mode'] = $modeType;

    }

    /*
     *
     */
    public function execGenerateLabels() {

      require('custom/model/fpdf.php');
      require('custom/model/makefont/makefont.php');

      $pdf = new \FPDF();
      $modelContent = \BB\model\content::get();

      $pdf->AddFont('Arial Narrow','','ArialNarrow.php');
      $pdf->SetAutoPageBreak(false);

      $cnIDs = $_SESSION[$this->module][$this->template]['selection'];

      $contacts = $modelContent->execSearch(
        array(
          'tableID' => 'Kontakte',
          'languageID' => 1,
          'fields' => array(
            array(
              array('Straße', '!=""', \BB\model\content::searchIn),
              array('PLZ', '!=""', \BB\model\content::searchIn),
              array('Ort', '!=""', \BB\model\content::searchIn),
              'AND'
            ),
            array(
              array('Land', null, \BB\model\content::orderByAsc),
              array('PLZ', null, \BB\model\content::orderByAsc),
              'AND'
            ),
            'AND'
          ),
          'contentIDs' => $cnIDs,
          'count' => true
        )
      );

      $numberOfPages = ceil($contacts->count / 12);
      $offsetX = 4.5;
      $offsetY = 7;
      $innerOffsetY = 14;
      $lineHeight = 7;
      $height = 48;
      $width = 105;

      $contactCounter = 0;
      for($c = 1; $c <= $numberOfPages; $c++):

        $pdf->AddPage();

        for($row = 1; $row <= 6; $row++):

          for($col = 1; $col <= 2; $col++):

            $firstname = utf8_decode($modelContent->getValue('Kontakte', 'Vorname', $contacts->contentIDs[$contactCounter]));
            $lastname = utf8_decode($modelContent->getValue('Kontakte', 'Nachname', $contacts->contentIDs[$contactCounter]));
            $street = utf8_decode($modelContent->getValue('Kontakte', 'Straße', $contacts->contentIDs[$contactCounter]));
            $plz = $modelContent->getValue('Kontakte', 'PLZ', $contacts->contentIDs[$contactCounter]);
            $city = utf8_decode($modelContent->getValue('Kontakte', 'Ort', $contacts->contentIDs[$contactCounter]));
            $country = utf8_decode($modelContent->getValue('Kontakte', 'Land', $contacts->contentIDs[$contactCounter]));

            $xPosition = ($col-1)*$width + $offsetX;
            $yPosition = ($row-1)*$height + $offsetY ;

            $pdf->SetFont('Arial Narrow','',8);

            $pdf->SetXY($xPosition, $yPosition);
            $pdf->Cell($width,5,utf8_decode('Büro Ehe-Initiative e.V. - Fried & Heidi Erhardt - Im Gässle 5 - 79312 Emmendingen'));

            $pdf->SetFont('Arial','',12);

            $pdf->SetXY($xPosition, $yPosition+$innerOffsetY);
            $pdf->Cell($width,5,$firstname.' '.$lastname);

            $pdf->SetXY($xPosition, $yPosition+$innerOffsetY+$lineHeight);
            $pdf->Cell($width,5,$street);

            $pdf->SetXY($xPosition, $yPosition+$innerOffsetY+$lineHeight+$lineHeight);
            $pdf->Cell($width,5,$plz.' '.$city);

            $pdf->SetXY($xPosition, $yPosition+$innerOffsetY+$lineHeight+$lineHeight+$lineHeight);
            $pdf->Cell($width,5,$country);

            $contactCounter++;

          endfor;

        endfor;

      endfor;

      header('Content-type:application/pdf');
      $pdf->Output();
      exit;

    }

    /*
     *
     */
    public function execSetRead() {

      $coreHttp = \BB\http\request::get();
      $mailID = $coreHttp->getInteger('m');
      $newsletterID = $coreHttp->getInteger('o');

      \BB\db::query(
        ' REPLACE INTO '.\BB\config::get('db:prefix').'web_mailspooler_reads'.
        ' SET msr_mail_id = '.$mailID.','.
        ' msr_cn_id = '.$newsletterID.','.
        ' msr_read = 1'
      );

      header('Content-type:image/gif');
      readfile('skins/responsive/icons/others/spacer.gif');
      exit;

    }

    /*
     *
     */
    public function execTruncateSelection() {

      $_SESSION[$this->module][$this->template]['selection'] = array();

    }

    /*
     *
     */
    public function execAddToSelection() {

      $tableID = @(int)$_SESSION[$this->module][$this->template]['tbl_id'];

      $fieldIDsAsNames = $this->getSortedFields($tableID);

      $resultIDs = $this->getResults($fieldIDsAsNames, 'pool', true, 0, 10000000);

      foreach($resultIDs as $resultID):
        $_SESSION[$this->module][$this->template]['selection'][$resultID] = $resultID;
      endforeach;

    }

    /**
     * @return void
     */
    public function viewConfirm() {

      $this->view->assign('number', count($this->success));

    }

    /**
     * @return void
     */
    public function execSpool() {

      $coreHttp = \BB\http\request::get(new \BB\http\nofilter());

      $this->template = 'Index';
      $tableID = (int)$_SESSION[$this->module][$this->template]['tbl_id'];

      $contentIDs = $_SESSION[$this->module][$this->template]['selection'];
      $pageID = \BB\config::get('mail:newsletter:pageID');
      $sender = $coreHttp->getString('sender');
      $senderName = $coreHttp->getString('senderName');
      $mailText = $coreHttp->getString('mailText');
      $attachment = $coreHttp->getString('attachment');
      $attachment2 = $coreHttp->getString('attachment2');
      $attachment3 = $coreHttp->getString('attachment3');
      $subject = $coreHttp->getString('subject');

      $modelContent = \BB\model\content::get();
      $newsletterID = $modelContent->execCreate(
        array(
          'tableID' => 'Newsletter'
        )
      );
      $modelContent->execUpdate(array(
        'tableID' => 'Newsletter',
        'fieldID' => 'Newsletter-Betreff',
        'contentID' => $newsletterID,
        'languageID' => 1,
        'value' => $subject
      ));
      $modelContent->execUpdate(array(
        'tableID' => 'Newsletter',
        'fieldID' => 'Newsletter-Text',
        'contentID' => $newsletterID,
        'languageID' => 1,
        'value' => $mailText
      ));
      $modelContent->execUpdate(array(
        'tableID' => 'Newsletter',
        'fieldID' => 'Newsletter-Menge',
        'contentID' => $newsletterID,
        'languageID' => 1,
        'value' => count($contentIDs)
      ));

      $attachments = array();
      if(!empty($attachment) && file_exists($attachment)):
        $attachments[] = $attachment;
      endif;
      if(!empty($attachment2) && file_exists($attachment2)):
        $attachments[] = $attachment2;
      endif;
      if(!empty($attachment3) && file_exists($attachment3)):
        $attachments[] = $attachment3;
      endif;

      // mail test
      $spooler = \BB\custom\model\crmSpooler::get();
      $this->success = $spooler->execSpoolCrm(
        $pageID,
        $subject,
        $attachments,
        $sender,
        $senderName,
        $tableID,
        $contentIDs,
        $mailText,
        $newsletterID
      );

      $_SESSION[$this->module][$this->template]['selection'] = array();

    }

    /**
     * @return void
     */
    public function execSpoolTest() {

      $coreHttp = \BB\http\request::get(new \BB\http\nofilter());

      $this->template = 'Index';
      $tableID = (int)$_SESSION[$this->module][$this->template]['tbl_id'];

      $pageID = \BB\config::get('mail:newsletter:pageID');
      $sender = $coreHttp->getString('sender');
      $senderName = $coreHttp->getString('senderName');
      $mailText = $coreHttp->getString('mailText');
      $attachment = $coreHttp->getString('attachment');
      $attachment2 = $coreHttp->getString('attachment2');
      $attachment3 = $coreHttp->getString('attachment3');
      $subject = $coreHttp->getString('subject');
      $email = \BB\config::get('mail:newsletter:testAddress');

      $attachments = array();
      if(!empty($attachment) && file_exists($attachment)):
        $attachments[] = $attachment;
      endif;
      if(!empty($attachment2) && file_exists($attachment2)):
        $attachments[] = $attachment2;
      endif;
      if(!empty($attachment3) && file_exists($attachment3)):
        $attachments[] = $attachment3;
      endif;

      // mail test
      $spooler = \BB\custom\model\crmSpooler::get();
      $this->success = $spooler->execTestSpoolCrm(
        $pageID,
        $subject,
        $attachments,
        $sender,
        $senderName,
        $tableID,
        $email,
        $mailText,
        0
      );

    }

    /**
     * @return void
     */
    public function viewIndex() {

      $this->execPrepareSession();
      $this->execAssignSearchField();
      $this->execAssignLanguageField(
        $_SESSION[$this->module][$this->template]['tbl_id'],
        $_SESSION[$this->module][$this->template]['lan_id']
      );
      $this->execAssignTables();

      $_SESSION[$this->module][$this->template]['limit'] = 30;

      $this->execAssignResults();
      $this->execAssignSession();
      $this->assignContextIndex();

    }

    /**
     * @return void
     */
    public function viewMails() {

      $coreHttp = \BB\http\request::get();
      $contentID = $coreHttp->getInteger('cn_id');
      $userTableID = @(int)$_SESSION[$this->module][$this->template]['tbl_id'];

      $modelContent = \BB\model\content::get();
      $modelTable = \BB\model\table::get();
      $modelJobstatus = \BB\model\jobStatus::get();

      $tableIDsByType = $modelTable->getTableIDsByType(\BB\model\table::tableTypeUser, array(-1));
      $tables = (array)$modelTable->getTables(0, \BB\model\table::tableTypeUser, array_intersect($tableIDsByType, $this->getAllowedUserTableIDs()));

      if($userTableID == 0):
        $userTableID = $tables[0]['tbl_id'];
      endif;

      $this->execPrepareSessionMails($tables);

      $this->view
        ->assign('cn_id', $contentID)
        ->add('dataset', $modelContent->getIdentificationValues($contentID))
        ->add('tables', $tables)
        ->add('users', $modelContent->getArticleUserAndMails($contentID, null, $userTableID))
        ->add('states', $modelJobstatus->getStateKeysAsNames('', 'E-Mail an zugewiesene Personen'))
        ->add('tbl_id', $userTableID);
    }

    /**
     * @return void
     */
    public function viewMove() {

      $coreHttp = \BB\http\request::get();
      $contentID = $coreHttp->getInteger('cn_id');

      $modelContent = \BB\model\content::get();
      $modelTable = \BB\model\table::get();

      $tables = (array)$modelTable->getTables(0, '', $this->getAllowedTableIDs());

      $this->view
        ->assign('cn_id', $contentID)
        ->add('dataset', $modelContent->getIdentificationValues($contentID))
        ->add('tables', $tables);

    }

    /**
     * @return void
     */
    public function viewRelationEdit() {

      $coreHttp = \BB\http\request::get();
      $coreSession = \BB\session\request::get();

      $languageID = $coreHttp->getInteger('lan_id');
      $contentID = $coreHttp->getInteger('cn_id');
      $fieldGroup = $coreHttp->getInteger('f_group');

      $modelField = \BB\model\field::get();
      $modelTable = \BB\model\table::get();
      $modelContent = \BB\model\content::get();

      $tableIDs = (array)$modelTable->getTableIDsByType(\BB\model\table::tableTypeRelation);
      $tableID = (int)$tableIDs[0];

      $form = $modelContent->getForm(
        array(
          'tableID'    => $tableID,
          'tableType'  => $modelTable->getType($tableID),
          'languageID' => array($languageID),
          'tableIDs'   => $tableID,
          'contentID'  => $contentID,
          'fields'     => $modelField->getFieldsOfGroup($tableID, $fieldGroup),
          'userID'     => $coreSession->getUserID()
        )
      );
      $this->view
        ->assign('cn_id', $contentID)
        ->assign('lan_id', $languageID)
        ->assign('tbl_id', $tableID)
        ->assign('form', $form)
      ;

      $this->execAssignLanguageField($tableID, $languageID, '$.content.relations.switch_language(this.value);');
      $this->execAssignGroupField($tableID, $fieldGroup);
    }

    /**
     * @return void
     */
    public function viewRelations() {

      $coreHttp = \BB\http\request::get();
      $contentID = $coreHttp->getInteger('cn_id');
      $tableID = $coreHttp->getInteger('tbl_id');
      $type = $coreHttp->getString('type');
      $ids = '';
      if($coreHttp->issetGet('id_src')):
        $ids = $coreHttp->getGet('id_src');
      endif;

      if($contentID == 0 || $type == ''):
        $contentID = $_SESSION[$this->module][$this->template.'Add']['cn_id'];
        $type = $_SESSION[$this->module][$this->template.'Add']['type'];
      endif;

      $modelContent = \BB\model\content::get();
      $modelTable = \BB\model\table::get();
      $modelMedia = \BB\model\media::get();

      $relationTableID = (int)$_SESSION[$this->module][$type]['rtbl_id'];
      $includeTableIDs = $this->getAllowedTableIDs();
      $relations = array();

      if($type == 'children'):
        $tables = $modelTable->getChildTables(0, '', $tableID, $includeTableIDs);
        foreach($tables as &$table):
          $table['tbl_name'] .= ' ('.$modelContent->getChildrenCount($tableID, $table['tbl_id'], $contentID).')';
        endforeach;
      else:
        $tables = $modelTable->getParentTables(0, '', $tableID, $includeTableIDs);
      endif;

      if(!empty($tables)):

        // Prüfen ob Tabellen-ID vorkommt
        $existsRelationTableID = false;
        foreach($tables as &$table)
          if($relationTableID == $table['tbl_id'])
            $existsRelationTableID = true;

        if(!$existsRelationTableID)
          $_SESSION[$this->module][$type]['rtbl_id'] =
            $relationTableID =
              $tables[0]['tbl_id'];

        $tableType = $modelTable->getType($relationTableID);

        $this->execPrepareSessionRelations($type, $tables);

        if($type == 'children'):
          $relationContentIDs = $modelContent->getChildren(
            $tableID,
            $relationTableID,
            $contentID,
            0,
            null,
            true
          );
        else:
          $relationContentIDs = $modelContent->getParents(
            $relationTableID,
            $tableID,
            $contentID,
            0,
            null,
            true
          );
        endif;

        foreach($relationContentIDs as $contentIDOfRelation => $relationContentID):

          $isMedia = $tableType == \BB\model\table::tableTypeMedia;
          $thumb = '';

          if($isMedia):
            $filename = $modelMedia->getFilename($relationContentID);
            $thumb = \BB\image::get(
              $filename,
              $modelMedia->getSettingsThumb(180, 180)
            );
          endif;

          $identification = $modelContent->identify(
            $tableType,
            $relationTableID,
            $relationContentID
          );

          $relations[$contentIDOfRelation] = array(
            'identification' => $identification,
            'thumb' => $thumb,
            'cn_id' => $relationContentID
          );
        endforeach;
      endif;

      $this->view
        ->add('dataset', $modelContent->getIdentificationValues($contentID, $tableID))
        ->add('tables', $tables)
        ->add('relations', $relations)
        ->assign('h2', \BB\i18n::__($type == 'children' ? 53 : 52))
        ->add('rtbl_id', $relationTableID)
        ->add('tableID', $tableID)
        ->add('type', $type)
        ->add('edit', $coreHttp->issetParam('edit'))
        ->add('tbl_id', $tableID)
        ->add('cn_id', $contentID)
        ->add('activeRows', $ids)
      ;

      if($type == 'children' && $relationTableID != 0):
        $this->assignSubmenuRelations($relationTableID, $tableID, $contentID);
      endif;

      $_SESSION[$this->module][$this->template.'Add']['ptbl_id'] = $tableID;
      $_SESSION[$this->module][$this->template.'Add']['tbl_id'] = $_SESSION[$this->module][$type]['rtbl_id'];
      $_SESSION[$this->module][$this->template.'Add']['cn_id'] = $contentID;
      $_SESSION[$this->module][$this->template.'Add']['type'] = $type;

    }

    /**
     * @return void
     */
    public function viewRelationsAdd() {

      $relationTableID = $_SESSION[$this->module][$this->template]['ptbl_id'];
      $tableID = $_SESSION[$this->module][$this->template]['tbl_id'];
      $languageID = $_SESSION[$this->module][$this->template]['lan_id'];

      $this->execPrepareSession();
      $this->execAssignSearchField();
      $this->execAssignLanguageField(
        $tableID,
        $languageID
      );
      $this->execAssignResults(false, false);
      $this->execAssignSession();

      $this->view->add('relationTableID', $relationTableID);

    }

    /**
     * @return void
     */
    public function viewSearch() {

      $tableID = $_SESSION[$this->module][$this->template]['tbl_id'];

      $modelTable = \BB\model\table::get();
      $fieldIDsAsNames = $modelTable->getIdentifyFields($tableID);
      $filterFieldIDs = array_keys($fieldIDsAsNames);

      $this->execAssignResults(false, false, $filterFieldIDs);
      $this->execAssignSession();

      $this->view->add('filterFieldIDs', $filterFieldIDs);

      $this->plain($this->view->get());

    }

    /**
     * @return void
     */
    public function viewSortColumns() {
      $coreHttp = \BB\http\request::get();

      $this->view
        ->add('fieldIDsAsNames', $this->getSortedFields())
        ->add('tableID', @(int)$_SESSION[$this->module]['Index']['tbl_id']);
    }

    /**
     * @return void
     */
    public function viewVersions() {

      $coreHttp = \BB\http\request::get();
      $languageID = $coreHttp->getInteger('lan_id');
      $tableID = $coreHttp->getInteger('tbl_id');
      $contentID = $coreHttp->getInteger('cn_ids');

      $modelContent = \BB\model\content::get();
      $modelVersion = \BB\model\version::get();
      $versions = $modelVersion->getVersions($contentID, $languageID);

      $versionsAndOwner = \BB\functional\ho::map(
        $versions,
        array($this, 'mapVersion')
      );

      for($c = 1; $c < count($versionsAndOwner); $c++):
        $prev = &$versionsAndOwner[$c-1];
        $current = &$versionsAndOwner[$c];

        foreach($current['dataset'] as $fieldID => $currentField):
          $prevField = $prev['dataset'][$fieldID];
          if($currentField['value'] == $prevField['value'])
            continue;

          $prev['diff'][$fieldID] = array(
            'fieldName' => $prevField['fieldName'],
            'diff' => \BB\command::htmlDiff($currentField['value'], $prevField['value'])
          );
        endforeach;
      endfor;

      $versionsAndOwnerAndDiff = \BB\functional\ho::map(
        $versionsAndOwner,
        function($version) {
          unset($version['dataset']);
          return $version;
        }
      );

      $this->view
        ->assign('lan_id', $languageID)
        ->assign('tbl_id', $tableID)
        ->assign('cn_id', $contentID)
        ->add('dataset', $modelContent->getIdentificationValues($contentID))
        ->add('versions', $versionsAndOwnerAndDiff)
      ;
    }

    /**
     * @return void
     */
    protected function assignContextIndex() {

      $tableID = (int)$_SESSION[$this->module][$this->template]['tbl_id'];
      $mode = (string)$_SESSION[$this->module][$this->template]['mode'];

      $modelTable = \BB\model\table::get();
      $type = $modelTable->getType(@$_SESSION[$this->module][$this->template]['tbl_id']);

      if($type == \BB\model\table::tableTypeArticle):
        $this->context(
          'Index',
          array(
            'name' => '{__(28)}',
            'click' => '$.content.list.mails()',
            'icon' => 'fa-envelope-o',
            'context' => 'content'
          ),
          4
        );
      endif;

      if($mode == 'selection'):
        unset($this->context['Index'][2]);
        $this->context(
          'Index',
          array(
            'name' => 'aus Auswahl entfernen',
            'click' => '$.crmData.list.removeFromSelection()',
            'icon' => 'fa-times',
            'context' => 'content'
          ),
          4
        );
      else:
        $this->context(
          'Index',
          array(
            'name' => 'zur Auswahl hinzufügen',
            'click' => '$.crmData.list.addLinesToSelection()',
            'icon' => 'fa-plus',
            'context' => 'content'
          ),
          4
        );
      endif;

      if($tableID == 0)
        return;

      $modelTemplate = \BB\model\template::get();
      $templates = $modelTemplate->getTemplatesByTableID($tableID);

      $c = 0;
      foreach($templates as $template):
        $this->context(
          'Index',
          array(
            'name' => $template['tpl_name'],
            'click' => '$.content.list.pdf('.$template['tpl_id'].')',
            'icon' => 'fa-file-pdf-o',
            'context' => 'content'
          ),
          $c
        );
        $c += 0.01;
      endforeach;
    }

    /**
     * @param $relationTableID
     * @param $tableID
     * @param $contentID
     */
    protected function assignSubmenuRelations($relationTableID, $tableID, $contentID) {
      $modelTable = \BB\model\table::get();
      $isTableTypeMedia = $modelTable->getType($relationTableID) == \BB\model\table::tableTypeMedia;

      if($isTableTypeMedia):
        $this->submenu(
          'Relations',
          array(
            'name'   => '{__(64)}',
            'click'  => '$.brandbox.dataset.media.selectChildren()',
            'icon'   => 'fa-bookmark',
            'rights' => array('create')
          ),
          1
        );
      else:
        $this->submenu(
          'Relations',
          array(
            'name'   => '{__(64)}',
            'tpl'    => 'RelationsAdd',
            'action' => '',
            'icon'   => 'fa-bookmark',
            'rights' => array('create')
          ),
          1
        );
        $this->submenu(
          'Relations',
          array(
            'name'   => '{__(11)}',
            'tpl'    => 'Edit',
            'action' => '',
            'icon'   => 'fa-plus-square',
            'params' => '&tbl_id='.$relationTableID.'&rtbl_id='.$tableID.'&rcn_id='.$contentID,
            'rights' => array('create')
          ),
          2
        );
      endif;
    }

    /**
     * @param $contentIDs
     * @param $tableID
     * @param $languageIDs
     */
    protected function assignSubmenuEdit($contentIDs, $tableID, $languageIDs) {
      $this->submenu(
        'Edit',
        array(
          'mod'   => 'content',
          'name'   => '{__(64)}',
          'tpl'    => 'Relations',
          'action' => '',
          'params' => '&cn_id='.$contentIDs[0].'&tbl_id='.$tableID.'&lan_id='.$languageIDs[0].'&type=parents&edit',
          'icon'   => 'fa-arrow-up',
          'rights' => array('create')
        ),
        1
      );
      $this->submenu(
        'Edit',
        array(
          'mod'   => 'content',
          'name'   => '{__(64)}',
          'tpl'    => 'Relations',
          'action' => '',
          'params' => '&cn_id='.$contentIDs[0].'&tbl_id='.$tableID.'&lan_id='.$languageIDs[0].'&type=children&edit',
          'icon'   => 'fa-arrow-down',
          'rights' => array('create')
        ),
        2
      );
    }

    /**
     * @param $tableID
     * @param $fieldGroup
     * @return void
     */
    protected function execAssignGroupField($tableID, $fieldGroup) {

      $modelTable = \BB\model\table::get();
      $groupFieldsFromTable = $modelTable->getGroupFields($tableID);

      $groupFields = array(0 => \BB\i18n::__(85));
      foreach($groupFieldsFromTable as $gf_id => $gf_name):
        $groupFields[$gf_id] = $gf_name;
      endforeach;

      $this->view
        ->add('groups', $groupFields)
        ->add('f_group', $fieldGroup)
      ;
    }

    /**
     * @param $tableID
     * @param $activeLanguageIDs
     * @param string $onchange
     * @param bool $multiple
     * @return void
     */
    protected function execAssignLanguageField($tableID, $activeLanguageIDs, $onchange = '$.content.search.switch_language(this.value);', $multiple = false) {

      if($tableID != 0):

        $languageIDsAsNames = $this->getAllowedLanguageIDsAsNames();

        $searchLanguages = \BB\form::select(
          'lan_id',
          $languageIDsAsNames,
          $activeLanguageIDs,
          160,
          'onchange="'.$onchange.'"',
          $multiple == false ? null : count($languageIDsAsNames)
        );
        $pdfLanguages = \BB\form::select('pdfLanguageID', $languageIDsAsNames, 1, 160);

        $this->view
          ->assign('select:languages', $searchLanguages)
          ->assign('select:pdf:languages', $pdfLanguages)
        ;
      else:
        $this->view
          ->delete('select:languages')
          ->delete('select:pdf:languages')
        ;
      endif;

    }

    /**
     * @param $activeLanguageIDs
     * @return void
     */
    protected function execAssignLanguageList($activeLanguageIDs) {
      $this->view
        ->add('activeLanguageIDs', $activeLanguageIDs)
        ->add('languageIDsAsNames', $this->getAllowedLanguageIDsAsNames())
      ;
    }

    /**
     * @param $contentIDs
     * @return void
     */
    protected function execAssignOwner($contentIDs) {

      $modelContent = \BB\model\content::get();
      $this->view->add('owner', $modelContent->getOwner($_SESSION[$this->module][$this->template]['tbl_id'], $contentIDs));

    }

    /**
     * @param $contentIDs
     * @return void
     */
    protected function execAssignRelations($contentIDs) {

      $modelContent = \BB\model\content::get();
      $modelTable = \BB\model\table::get();

      $type = $modelTable->getType(@$_SESSION[$this->module][$this->template]['tbl_id']);

      $children = $modelContent->getMultiChildrenCount(
        $_SESSION[$this->module][$this->template]['tbl_id'],
        $contentIDs
      );
      $parents = $modelContent->getMultiParentsCount(
        $_SESSION[$this->module][$this->template]['tbl_id'],
        $contentIDs
      );

      $this->view
        ->add('children', $children)
        ->add('parents', $parents)
      ;

      if($type == \BB\model\table::tableTypeArticle):
        $this->view->add('relations', $modelContent->getMultiFieldCount($contentIDs));
      else:
        $this->view->add('relations', $modelContent->getMultiCollectionCount($_SESSION[$this->module][$this->template]['tbl_id'], $contentIDs));
      endif;

      if($type == \BB\model\table::tableTypeUser):
        $this->view->add('jobs', $modelContent->getAllJobs($contentIDs));
      else:
        $this->view->add('jobs', array());
      endif;

    }

    /**
     * @param bool $owner
     * @param bool $relations
     * @param array $filterFieldIDs
     * @return void
     */
    protected function execAssignResults($owner = true, $relations = true, $filterFieldIDs = array()) {

      $tableID = @(int)$_SESSION[$this->module][$this->template]['tbl_id'];
      $languageID = $_SESSION[$this->module][$this->template]['lan_id'];

      $coreHttp = \BB\http\request::get();
      $modelField = \BB\model\field::get();

      $fieldIDsAsNames = $this->getSortedFields($tableID);
      $resultFieldIDsAsNames = array();

      if(!empty($filterFieldIDs)):
        foreach($fieldIDsAsNames as $fieldID => $fieldName):
          if(!in_array($fieldID, $filterFieldIDs))
            continue;
          $resultFieldIDsAsNames[$fieldID] = $fieldName;
        endforeach;
      else:
        $resultFieldIDsAsNames = $fieldIDsAsNames;
      endif;

      $rows = $this->getSearchResults();
      $max = $this->getResultsMaxCount();
      $contentIDs = $this->getIDs($rows);

      if($owner == true)
        $this->execAssignOwner($contentIDs);
      if($relations == true)
        $this->execAssignRelations($contentIDs);

      if($languageID != 1):
        foreach($resultFieldIDsAsNames as $fieldID => &$fieldName):
          $fieldNameOfLanguage = $modelField->getName($fieldID, $languageID);
          if($fieldNameOfLanguage != '')
            $fieldName = $fieldNameOfLanguage;
        endforeach;
      endif;

      $this->view
        ->assign('unselect', $coreHttp->getAction() == 'Unselect' ? 1 : 0)
        ->add('fields', $resultFieldIDsAsNames)
        ->add('rows', $rows)
        ->add('max', $max)
        ->add('tableID', $tableID)
      ;
    }

    /**
     * @return array
     */
    protected function getSearchResults() {

      $tableID = @(int)$_SESSION[$this->module][$this->template]['tbl_id'];
      $mode = (string)$_SESSION[$this->module][$this->template]['mode'];

      $fieldIDsAsNames = $this->getSortedFields($tableID);
      $rows = $this->getResults($fieldIDsAsNames, $mode);

      return $rows;

    }


    /**
     * @author Philipp Frick
     * @return void
     */
    protected function execAssignSearchField() {
      $searchFields = $this->getSearchFields();
      $tableID = @(int)$_SESSION[$this->module][$this->template]['tbl_id'];
      $this->view->add('searchFields', $searchFields);
      $this->view->add('activeSearchFields', $_SESSION[$this->module][$this->template][$tableID]['q']);
      $this->view->add('activeSearchTypes', $_SESSION[$this->module][$this->template][$tableID]['searchTypes']);

    }

    /**
     * @return void
     */
    protected function execAssignSession() {

      $tableID = @(int)$_SESSION[$this->module][$this->template]['tbl_id'];

      $this->view
        ->add('offset', @$_SESSION[$this->module][$this->template]['offset'])
        ->add('mode', @$_SESSION[$this->module][$this->template]['mode'])
        ->add('limit', @$_SESSION[$this->module][$this->template]['limit'])
        ->add('tbl_id', $tableID)
        ->add('sf_id', @(int)$_SESSION[$this->module][$this->template][$tableID]['sf_id'])
        ->add('of_id', @(int)$_SESSION[$this->module][$this->template][$tableID]['of_id'])
        ->add('odirection', $_SESSION[$this->module][$this->template][$tableID]['odirection'])
        ->add('lan_id', @(int)$_SESSION[$this->module][$this->template]['lan_id']);

    }

    /**
     * @return void
     */
    protected function execAssignTables() {

      $modelTable = \BB\model\table::get();
      $tableIDsWhiteList = $this->getAllowedTableIDs(false);

      $tableGroupIDs = @(array)$_SESSION[$this->module][$this->template]['tblgr_ids'];
      $tableGroups = $modelTable->getTableGroups();

      foreach($tableGroups as &$tableGroup):
        $tables = $modelTable->getTables($tableGroup['tblgr_id'], '', $tableIDsWhiteList);
        $tableGroup['hasTables'] = count($tables) > 0;
        $tableGroup['tables'] = array();
        if(!in_array($tableGroup['tblgr_id'], $tableGroupIDs))
          continue;
        $tableGroup['active'] = true;
        $tableGroup['tables'] = $tables;
      endforeach;

      foreach($tableGroups as $tableGroupKey => &$tableGroup):
        if(!$tableGroup['hasTables'])
          unset($tableGroups[$tableGroupKey]);
      endforeach;

      $tableType = $modelTable->getType(@(int)$_SESSION[$this->module][$this->template]['tbl_id']);

      $this->view
        ->add('table_groups', $tableGroups)
        ->add('table_type', $tableType)
      ;
    }

    /**
     * @return void
     */
    protected function execPrepareSession() {           
      
      $this->execPrepareSessionCleanUp($this->template);

      $modelTable = \BB\model\table::get();
      $tableID = $modelTable->getIDByIdentifier('Kontakte');
      $tableType = $modelTable->getType($tableID);

      if(empty($_SESSION[$this->module][$this->template]['mode']))
        $_SESSION[$this->module][$this->template]['mode'] = 'pool';
      if(empty($_SESSION[$this->module][$this->template]['offset']))
        $_SESSION[$this->module][$this->template]['offset'] = 0;
      if(empty($_SESSION[$this->module][$this->template]['limit']))
        $_SESSION[$this->module][$this->template]['limit'] = $this->getDefaultLimit();
      if(empty($_SESSION[$this->module][$this->template]['lan_id'])):
        $languageIDsAsNames = $this->getAllowedLanguageIDsAsNames();
        $languageIDs = array_keys($languageIDsAsNames);
        $_SESSION[$this->module][$this->template]['lan_id'] = current($languageIDs);
      endif;
      if(empty($_SESSION[$this->module][$this->template]['tbl_id']))
        $_SESSION[$this->module][$this->template]['tbl_id'] = $tableID;

      $tableID = @(int)$_SESSION[$this->module][$this->template]['tbl_id'];
      if(empty($_SESSION[$this->module][$this->template][$tableID]['of_id']))
        $_SESSION[$this->module][$this->template][$tableID]['of_id'] = 0;
      if(empty($_SESSION[$this->module][$this->template][$tableID]['odirection']))
        $_SESSION[$this->module][$this->template][$tableID]['odirection'] = 'desc';
      if(empty($_SESSION[$this->module][$this->template][$tableID]['q']))
        $_SESSION[$this->module][$this->template][$tableID]['q'] = '';
      if(empty($_SESSION[$this->module][$this->template][$tableID]['searchTypes']))
        $_SESSION[$this->module][$this->template][$tableID]['searchTypes'] = '';

      if($tableType == '')
        $_SESSION[$this->module][$this->template]['tbl_id'] = 0;

      $this->execPrepareSessionFieldID();

    }

    /**
     * @param string $template
     * @return void
     */
    protected function execPrepareSessionCleanUp($template = 'Index') {

      if(
        isset($_SESSION[$this->module][$template]['module']) &&
        isset($_SESSION[$this->module][$template]['remember'])
      ):

        $_SESSION[$this->module][$template] =
          $_SESSION[$this->module][$template]['remember'];

        $this->execPrepareSessionCleanUp($template);
        return;

      endif;

      $_SESSION[$this->module]['Index']['module'] = null;

      return;
    }

    /**
     * @return void
     */
    protected function execPrepareSessionFieldID() {

      $fields = $this->getSearchFields();
      $fieldIDs = array_keys($fields);
      $tableID = @(int)$_SESSION[$this->module][$this->template]['tbl_id'];

      $fieldIDOkay = false;
      foreach($fieldIDs as $fieldID):
        if($fieldID == @$_SESSION[$this->module][$this->template][$tableID]['sf_id'])
          $fieldIDOkay = true;
      endforeach;

      if($fieldIDOkay == false && @$_SESSION[$this->module][$this->template][$tableID]['sf_id'] != -1):
        $_SESSION[$this->module][$this->template][$tableID]['sf_id'] = -1;
      endif;

      $fieldIDOkay = false;
      foreach($fieldIDs as $fieldID):
        if($fieldID == @$_SESSION[$this->module][$this->template][$tableID]['of_id'])
          $fieldIDOkay = true;
      endforeach;

      if($fieldIDOkay == false && @$_SESSION[$this->module][$this->template][$tableID]['of_id'] != 0):
        $_SESSION[$this->module][$this->template][$tableID]['of_id'] = 0;
      endif;
    }

    /**
     * @param $tables
     * @return void
     */
    protected function execPrepareSessionMails($tables) {

      if(empty($_SESSION[$this->module][$this->template]['tbl_id']))
        $_SESSION[$this->module][$this->template]['tbl_id'] = 0;

      $tableIDOkay = false;
      foreach($tables as $table):
        if($table['tbl_id'] == @$_SESSION[$this->module][$this->template]['tbl_id'])
          $tableIDOkay = true;
      endforeach;

      if($tableIDOkay == false)
        $_SESSION[$this->module][$this->template]['tbl_id'] = @(int)$tables[0]['tbl_id'];

    }

    /**
     * @param $type
     * @param $tables
     * @return void
     */
    protected function execPrepareSessionRelations($type, $tables) {

      if(empty($_SESSION[$this->module][$type]['rtbl_id']))
        $_SESSION[$this->module][$type]['rtbl_id'] = 0;

      $tableIDOkay = false;
      foreach($tables as $table):
        if($table['tbl_id'] == @$_SESSION[$this->module][$type]['rtbl_id'])
          $tableIDOkay = true;
      endforeach;

      if($tableIDOkay == false)
        $_SESSION[$this->module][$type]['rtbl_id'] = @(int)$tables[0]['tbl_id'];

    }

    /**
     * @return array
     */
    protected function getAllowedEditLanguageIDs() {
      $modelRight = \BB\model\right::get();
      $coreSession = \BB\session\request::get();

      return $modelRight->getAllowedEditLanguageIDs($coreSession->getUserID());
    }

    /**
     * @return array
     */
    protected function getAllowedLanguageIDs() {

      $modelRight = \BB\model\right::get();
      $coreSession = \BB\session\request::get();

      $userTableID = $coreSession->getUserTableID();

      return array_unique(
        array_merge(
          $modelRight->getAllowedDatasets('languages', $userTableID),
          $modelRight->getAllowedDatasets('languages:read', $userTableID)
        )
      );
    }

    /**
     * @return array
     */
    protected function getAllowedLanguageIDsAsNames() {

      $modelLanguage = \BB\model\language::get();
      $languageIDsAsNames = $modelLanguage->getLanguageIDsAsNames();
      $allowedLanguageIDs = $this->getAllowedLanguageIDs();

      if($allowedLanguageIDs[0] != \BB\model\right::wildcard):
        foreach($languageIDsAsNames as $languageID => $languageName):
          if(!in_array($languageID, $allowedLanguageIDs))
            unset($languageIDsAsNames[$languageID]);
        endforeach;
      endif;

      return $languageIDsAsNames;
    }

    /**
     * @param $languageIDs
     * @return array
     */
    protected function getAllowedLanguageIDsIfCurrentAreForbidden($languageIDs) {

      $allowedLanguageIDs = $this->getAllowedLanguageIDs();

      if($allowedLanguageIDs[0] != \BB\model\right::wildcard):
        $languageKeysAsIDs = array_intersect($allowedLanguageIDs, $languageIDs);
        $languageIDsWithoutKeys = array();
        foreach($languageKeysAsIDs as $languageID):
          $languageIDsWithoutKeys[] = $languageID;
        endforeach;
        return $languageIDsWithoutKeys;
      endif;

      return $languageIDs;
    }

    /**
     * @param bool $includeMediaTable
     * @return array
     */
    protected function getAllowedTableIDs($includeMediaTable = true) {
      $modelTable = \BB\model\table::get();
      $modelMedia = \BB\model\media::get();
      $coreSession = \BB\session\request::get();

      $tableIDs = $modelTable->getTableIDsOfUser($coreSession->getUserTableID());

      if($includeMediaTable == true)
        return $tableIDs;

      $tableIDOfMedia = $modelMedia->getTableID();
      $return = array();

      foreach($tableIDs as $tableID):
        if($tableID != $tableIDOfMedia)
          $return[] = $tableID;
      endforeach;

      return $return;
    }

    /**
     * @return array
     */
    protected function getAllowedUserTableIDs() {

      $modelTable = \BB\model\table::get();
      $modelRight = \BB\model\right::get();
      $coreSession = \BB\session\request::get();

      $tableIDsByType = $modelTable->getTableIDsByTypes(array(
        \BB\model\table::tableTypeUser
      ));
      $tableIDsByRight = $modelRight->getAllowedDatasets('base_table', $coreSession->getUserTableID());

      if($tableIDsByRight[0] == \BB\model\right::wildcard):
        return $tableIDsByType;
      else:
        return array_intersect($tableIDsByType, $tableIDsByRight);
      endif;

    }

    /**
     * @return mixed
     */
    protected function getConfigCountOfCategories() {
      $result = (int)\BB\config::get('settings:media:categoryCount');
      if($result == 0)
        $result = 100;

      return $result;
    }

    /**
     * @return int
     */
    protected function getDefaultLimit() {

      $coreDetect = new \BB\mobileDetect();
      $isMobile = $coreDetect->isMobile() || $coreDetect->isTablet();

      return $isMobile ? 10 : 28;
    }

    /**
     * @param $rows
     * @return array
     */
    protected function getIDs($rows) {

      $contentIDs = array();
      foreach($rows as $row):
        $contentIDs[] = $row['cnv_id'];
      endforeach;

      return $contentIDs;

    }

    /**
     * @return array
     */
    protected function getLanguageIDs() {

      $coreHttp = \BB\http\request::get();

      $languageID = max(1, $coreHttp->getInteger('lan_id'));
      if(!empty($_SESSION[$this->module][$this->template]['lan_ids'])):
        $languageIDs = $_SESSION[$this->module][$this->template]['lan_ids'];
      elseif($coreHttp->issetPost('lan_id')):
        $languageIDs = (array)explode(',', $coreHttp->getPost('lan_id'));
      else:
        $languageIDs = array($languageID);
      endif;

      return $languageIDs;
    }

    /**
     * @param $fields
     * @return array
     */
    protected function getResults($fields, $mode = 'pool', $returnIDs = false, $offset = null, $limit = null) {

      $tableID = (int)$_SESSION[$this->module][$this->template]['tbl_id'];
      $languageID = (int)$_SESSION[$this->module][$this->template]['lan_id'];
      if($limit === null):
        $limit = (int)$_SESSION[$this->module][$this->template]['limit'];
      endif;
      if($offset === null):
        $offset = (int)$_SESSION[$this->module][$this->template]['offset'];
      endif;
      $selection = (array)$_SESSION[$this->module][$this->template]['selection'];

      $languageIDs = $this->getAllowedLanguageIDsIfCurrentAreForbidden(array($languageID));
      if($languageIDs[0] != $languageID)
        $languageID = $languageIDs[0];

      if(empty($languageIDs))
        return array();

      if($tableID == 0)
        return array();


      $modelTable = \BB\model\table::get();
      $modelContent = \BB\model\content::get();
      $modelField = \BB\model\field::get();

      $querySelect = array();
      $fieldIDsMonolingual = array();
      foreach($fields as $fieldID => $fieldName):
        $querySelect[] = 'lang.cnv_'.$fieldID;
        $querySelect[] = 'german.cnv_'.$fieldID.' as german'.$fieldID;
        if($languageID != 1):
          $fieldIDsMonolingual[$fieldID] = $modelField->isMonolingual($fieldID);
        endif;
      endforeach;

      $tableNameValues = $modelTable->getRealname($tableID, 'values');
      $tableNameContent = $modelTable->getRealname($tableID, 'content');

      $rowsOfLanguages = array();
      try {

        if($mode == 'selection'):

          if(count($selection) > 0):

            $rowsOfLanguages = (array)\BB\db::rows(
              ' SELECT german.cnv_id'.
              (count($querySelect) > 0 ? ', '.implode(',', $querySelect) : '').
              ' FROM '.\BB\config::get('db:prefix').$tableNameValues.' as german'.
              ' INNER JOIN '.\BB\config::get('db:prefix').$tableNameValues.' as lang'.
              ' ON lang.cnv_id = german.cnv_id'.
              ' WHERE german.cnv_lan_id = 1'.
              ' AND german.cnv_version = 0'.
              ' AND german.cnv_id IN('.
              ' SELECT cn_id'.
              ' FROM '.\BB\config::get('db:prefix').$tableNameContent.
              ' WHERE cn_tbl_id = '.$tableID.
              ' AND cn_delete_time = 0'.
              ')'.
              ' AND lang.cnv_lan_id = '.$languageID.
              ' AND lang.cnv_version = 0'.
              ' AND german.cnv_id IN ('.implode(',', $selection).')'.
              $this->getResultsWhere().
              ' ORDER BY '.
              $this->getResultsOrderBy($fieldIDsMonolingual).
              ' LIMIT '.$offset.', '.$limit,
              'cnv_id'
            );

          else:

            $rowsOfLanguages = array();

          endif;

        else:

          $rowsOfLanguages = (array)\BB\db::rows(
            ' SELECT german.cnv_id'.
            (count($querySelect) > 0 ? ', '.implode(',', $querySelect) : '').
            ' FROM '.\BB\config::get('db:prefix').$tableNameValues.' as german'.
            ' INNER JOIN '.\BB\config::get('db:prefix').$tableNameValues.' as lang'.
            ' ON lang.cnv_id = german.cnv_id'.
            ' WHERE german.cnv_lan_id = 1'.
            ' AND german.cnv_version = 0'.
            ' AND german.cnv_id IN('.
            ' SELECT cn_id'.
            ' FROM '.\BB\config::get('db:prefix').$tableNameContent.
            ' WHERE cn_tbl_id = '.$tableID.
            ' AND cn_delete_time = 0'.
            ')'.
            ' AND lang.cnv_lan_id = '.$languageID.
            ' AND lang.cnv_version = 0'.
            $this->getResultsWhere().
            ' ORDER BY '.
            $this->getResultsOrderBy($fieldIDsMonolingual).
            ' LIMIT '.$offset.', '.$limit,
            'cnv_id'
          );

        endif;



      } catch (\BB\exception\mysql $e) {

        \BB\error::$E_NO = 'An error occured. The table has a defect. Please check the field configuration.';

      }

      if($returnIDs === true):
        return array_keys($rowsOfLanguages);
      endif;

      $rows = array();
      $c = 0;
      foreach($rowsOfLanguages as $row):
        $rows[] = $row;
        foreach($row as $key => $value):
          if($key == 'cnv_id')
            continue;
          if(substr($key, 0, 4) != 'cnv_')
            continue;
          $fieldID = (int)substr($key, 4);
          if($fieldIDsMonolingual[$fieldID] == 1):
            $value = $row['german'.$fieldID];
          endif;
          $rows[$c][$key] = $modelContent->getClearValue($fieldID, $value, $languageID);
        endforeach;
        $c++;
      endforeach;

      return $rows;
    }

    /**
     * @return integer
     */
    protected function getResultsMaxCount() {

      $tableID = (int)$_SESSION[$this->module][$this->template]['tbl_id'];
      $languageID = (int)$_SESSION[$this->module][$this->template]['lan_id'];
      $mode = (string)$_SESSION[$this->module][$this->template]['mode'];
      $selection = (array)$_SESSION[$this->module][$this->template]['selection'];

      $languageIDs = $this->getAllowedLanguageIDsIfCurrentAreForbidden(array($languageID));
      if($languageIDs[0] != $languageID)
        $languageID = $languageIDs[0];

      if(empty($languageIDs))
        return 0;

      if($tableID == 0)
        return 0;

      $modelTable = \BB\model\table::get();

      $tableNameValues = $modelTable->getRealname($tableID, 'values');
      $tableNameContent = $modelTable->getRealname($tableID, 'content');

      if($mode == 'selection' && count($selection) == 0):

        $max = 0;

      else:

        $max = (int)\BB\db::query(
          ' SELECT count(distinct german.cnv_id) as count'.
          ' FROM '.\BB\config::get('db:prefix').$tableNameValues.' as german'.
          ' INNER JOIN '.\BB\config::get('db:prefix').$tableNameValues.' as lang'.
          ' ON lang.cnv_id = german.cnv_id'.
          ' WHERE german.cnv_lan_id = 1'.
          ' AND german.cnv_version = 0'.
          ' AND german.cnv_id IN('.
          ' SELECT cn_id'.
          ' FROM '.\BB\config::get('db:prefix').$tableNameContent.
          ' WHERE cn_tbl_id = '.$tableID.
          ' AND cn_delete_time = 0'.
          ')'.
          ' AND lang.cnv_lan_id = '.$languageID.
          ' AND lang.cnv_version = 0'.
          ($mode == 'selection' ? ' AND german.cnv_id IN ('.implode(',', $selection).')' : '').
          $this->getResultsWhere(),
          'count'
        );

      endif;




      return $max;

    }

    /**
     * @param $fieldIDsMonolingual
     * @return string
     */
    protected function getResultsOrderBy($fieldIDsMonolingual) {

      $tableID = @(int)$_SESSION[$this->module][$this->template]['tbl_id'];
      $orderFieldID = (int)$_SESSION[$this->module][$this->template][$tableID]['of_id'];
      $orderBy = (string)$_SESSION[$this->module][$this->template][$tableID]['odirection'];

      $sortInLanguage = 'lang';
      if($fieldIDsMonolingual[$orderFieldID] == 1):
        $sortInLanguage = 'german';
      endif;

      return
        ($orderFieldID != 0 ? $sortInLanguage.'.cnv_'.$orderFieldID.' '.$orderBy.', ' : '').
          ' cnv_id '.$orderBy;
    }

    /**
     * @author Philipp Frick
     * @return string
     */
    protected function getResultsWhere() {

      $modelField = \BB\model\field::get();

      $tableID = (int)$_SESSION[$this->module][$this->template]['tbl_id'];
      $searchFields = (array)$_SESSION[$this->module][$this->template][$tableID]['q'];
      $where = array();

      foreach($searchFields as $searchFieldID => $query):

        if($searchFieldID != 0 && $query != ''):
          $phrase = $query;
          $phrase = str_replace('_', '\_', $phrase);
          $phrase = str_replace('%', '\%', $phrase);
          $phrase = str_replace('*', '%', $phrase);
          $phrase = mysql_real_escape_string($phrase);

          if($searchFieldID == -1):

            if(\BB\info::isNumber($phrase)):
              return ' AND german.cnv_id = '.(int)$phrase;
            else:
              $fields = $this->getSearchFields();

              $whereQuery = array();
              foreach($fields as $fieldID => $f_value):
                $isMonolingual = $modelField->isMonolingual($fieldID);
                $tableName = $isMonolingual ? 'german' : 'lang';
                  $whereQuery[] = $tableName.'.cnv_'.$fieldID.' LIKE "%'.$phrase.'%"';
              endforeach;

              $where[] = ' AND ('.implode(' OR ', $whereQuery).')';
            endif;

          else:

            $isMonolingual = $modelField->isMonolingual($searchFieldID);
            $tableName = $isMonolingual ? 'german' : 'lang';
            $searchType = $_SESSION[$this->module][$this->template][$tableID]['searchTypes'][$searchFieldID];

            if($modelField->isPipeArray($searchFieldID)):
              $cond = ' LIKE "%|'.$phrase.'|%"';
            elseif($modelField->isInteger($searchFieldID)):
              $cond = ' = '.(int)$phrase;
            else:

              switch($searchType):

                case 'is'; $cond = ' LIKE "'.$phrase.'"'; break;
                case 'isnot': $cond = ' NOT LIKE "'.$phrase.'"'; break;
                default:
                case 'contains': $cond = ' LIKE "%'.$phrase.'%"'; break;
                case 'containsnot': $cond = ' NOT LIKE "%'.$phrase.'%"'; break;
                case 'startswith': $cond = ' LIKE "'.$phrase.'%"'; break;
                case 'startsnotwith': $cond = ' NOT LIKE "'.$phrase.'%"'; break;
                case 'endswith': $cond = ' LIKE "%'.$phrase.'"'; break;
                case 'endsnotwith': $cond = ' NOT LIKE "%'.$phrase.'"'; break;

              endswitch;

            endif;

            $where[] =  ' AND '.$tableName.'.cnv_'.$searchFieldID.' '.$cond;
          endif;
        endif;

      endforeach;

      return implode(' ', $where);
    }

    /**
     * @return array
     */
    protected function getSearchFields() {

      if(is_array($this->searchFields))
        return $this->searchFields;

      $modelTable = \BB\model\table::get();
      return
        $this->searchFields =
          (array)$modelTable->getSearchFields(@$_SESSION[$this->module][$this->template]['tbl_id']);

    }

    /**
     * @param $tableID
     * @return array
     */
    protected function getSortedFields($tableID = -1) {

      $modelTable = \BB\model\table::get();

      if($tableID == -1)
        $tableID = @(int)$_SESSION[$this->module]['Index']['tbl_id'];

      $fieldIDs = @(array)json_decode($_COOKIE['content']['sortedFieldIDs'][$tableID]);
      $fieldIDsAsNames = $modelTable->getResultFields($tableID);
      $sortedFields = array();

      foreach($fieldIDs as $fieldID):
        if(isset($fieldIDsAsNames[$fieldID]))
          $sortedFields[$fieldID] = $fieldIDsAsNames[$fieldID];
      endforeach;

      foreach($fieldIDsAsNames as $fieldID => $fieldName):
        if(!isset($sortedFields[$fieldID]))
          $sortedFields[$fieldID] = $fieldName;
      endforeach;

      return $sortedFields;
    }
  }

?>