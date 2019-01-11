<?php

  namespace BB\custom\model\element\dao;

  if(@secure !== true)
    die('forbidden');

  /**
   * @author Brandbox
   *
   * @method \BB\model\element\dao\Veranstaltungen getRow(\int $contentID)
   * @method \BB\model\element\dao\Veranstaltungen[] getRows(\int $languageID, array $fields = array(), \int $offset = 0, \int $limit = 0)
   * @method \BB\model\element\dao\Veranstaltungen[] getDaoElements(array $contentIDs)
   * @method \BB\model\element\dao\Veranstaltungen[] getChildren(\string $tableID, \int $contentID)
   * @method \BB\model\element\dao\Veranstaltungen[] getParents(\string $tableID, \int $contentID)
   * @method \int[] getChildContentIDs(\string $tableID, \int $contentID)
   * @method \int[] getParentContentIDs(\string $tableID, \int $contentID)
   * @method \BB\model\element\dao\Veranstaltungen saveForm(\int $contentID, array $data = array(), \int $languageID = 1, \int $userID = 0)
   * @method \BB\model\element\dao\Veranstaltungen setData(\int $contentID,\object $row, \int $languageID = 1, \int $userID = 1)
   * @method \BB\custom\model\element\dao\stdVeranstaltungen getData(\int $contentID, \int $languageID = 1)
   *
   * @method string getVeranstaltungsart(\int $contentID, \int $languageID)
   * @method string getVeranstaltungstitel(\int $contentID, \int $languageID)
   * @method string getVeranstaltungsreferenten(\int $contentID, \int $languageID)
   * @method integer getVeranstaltungsstartdatum(\int $contentID, \int $languageID)
   * @method integer getVeranstaltungsenddatum(\int $contentID, \int $languageID)
   * @method integer getVeranstaltungsbeginn(\int $contentID, \int $languageID)
   * @method integer getVeranstaltungsstartdatum2(\int $contentID, \int $languageID)
   * @method integer getVeranstaltungsenddatum2(\int $contentID, \int $languageID)
   * @method integer getVeranstaltungsbeginn2(\int $contentID, \int $languageID)
   * @method integer getVeranstaltungsanmeldefrist(\int $contentID, \int $languageID)
   * @method string getVeranstaltungsbeschreibung(\int $contentID, \int $languageID)
   * @method string getVeranstaltungshinweise(\int $contentID, \int $languageID)
   * @method string getVeranstaltungsort(\int $contentID, \int $languageID)
   * @method string getVeranstaltungspreis(\int $contentID, \int $languageID)
   * @method string getNewsbild(\int $contentID, \int $languageID)
   * @method void setVeranstaltungsart(\int $contentID, \string $value, \int $languageID)
   * @method void setVeranstaltungstitel(\int $contentID, \string $value, \int $languageID)
   * @method void setVeranstaltungsreferenten(\int $contentID, \string $value, \int $languageID)
   * @method void setVeranstaltungsstartdatum(\int $contentID, \integer $value, \int $languageID)
   * @method void setVeranstaltungsenddatum(\int $contentID, \integer $value, \int $languageID)
   * @method void setVeranstaltungsbeginn(\int $contentID, \integer $value, \int $languageID)
   * @method void setVeranstaltungsstartdatum2(\int $contentID, \integer $value, \int $languageID)
   * @method void setVeranstaltungsenddatum2(\int $contentID, \integer $value, \int $languageID)
   * @method void setVeranstaltungsbeginn2(\int $contentID, \integer $value, \int $languageID)
   * @method void setVeranstaltungsanmeldefrist(\int $contentID, \integer $value, \int $languageID)
   * @method void setVeranstaltungsbeschreibung(\int $contentID, \string $value, \int $languageID)
   * @method void setVeranstaltungshinweise(\int $contentID, \string $value, \int $languageID)
   * @method void setVeranstaltungsort(\int $contentID, \string $value, \int $languageID)
   * @method void setVeranstaltungspreis(\int $contentID, \string $value, \int $languageID)
   * @method void setNewsbild(\int $contentID, \string $value, \int $languageID)
   */
  class Veranstaltungen extends \BB\model\dao {

    /**
     * @return self
     */
    public static function instance() {
      return self::get('custom/dao/Veranstaltungen.xml');
    }
  }

  /**
   * @property string Veranstaltungsart
   * @property string Veranstaltungstitel
   * @property string Veranstaltungsreferenten
   * @property integer Veranstaltungsstartdatum
   * @property integer Veranstaltungsenddatum
   * @property integer Veranstaltungsanmeldefrist
   * @property string Veranstaltungsbeschreibung
   * @property string Veranstaltungshinweise
   * @property string Veranstaltungsort
   * @property string Veranstaltungspreis
   * @property string Newsbild
   */
  class stdVeranstaltungen {}

?>