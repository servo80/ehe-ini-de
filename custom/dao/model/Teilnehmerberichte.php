<?php

  namespace BB\custom\model\element\dao;

  if(@secure !== true)
    die('forbidden');

  /**
   * @author Brandbox
   *
   * @method \BB\model\element\dao\Teilnehmerberichte getRow(\int $contentID)
   * @method \BB\model\element\dao\Teilnehmerberichte[] getRows(\int $languageID, array $fields = array(), \int $offset = 0, \int $limit = 0)
   * @method \BB\model\element\dao\Teilnehmerberichte[] getDaoElements(array $contentIDs)
   * @method \BB\model\element\dao\Teilnehmerberichte[] getChildren(\string $tableID, \int $contentID)
   * @method \BB\model\element\dao\Teilnehmerberichte[] getParents(\string $tableID, \int $contentID)
   * @method \int[] getChildContentIDs(\string $tableID, \int $contentID)
   * @method \int[] getParentContentIDs(\string $tableID, \int $contentID)
   * @method \BB\model\element\dao\Teilnehmerberichte saveForm(\int $contentID, array $data = array(), \int $languageID = 1, \int $userID = 0)
   * @method \BB\model\element\dao\Teilnehmerberichte setData(\int $contentID,\object $row, \int $languageID = 1, \int $userID = 1)
   * @method \BB\custom\model\element\dao\stdTeilnehmerberichte getData(\int $contentID, \int $languageID = 1)
   *
   * @method string getBerichtautor(\int $contentID, \int $languageID)
   * @method string getBerichtueberschrift(\int $contentID, \int $languageID)
   * @method string getBericht(\int $contentID, \int $languageID)
   * @method void setBerichtautor(\int $contentID, \string $value, \int $languageID)
   * @method void setBerichtueberschrift(\int $contentID, \string $value, \int $languageID)
   * @method void setBericht(\int $contentID, \string $value, \int $languageID)
   */
  class Teilnehmerberichte extends \BB\model\dao {

    /**
     * @return self
     */
    public static function instance() {
      return self::get('custom/dao/Teilnehmerberichte.xml');
    }
  }

  /**
   * @property string Berichtautor
   * @property string Berichtueberschrift
   * @property string Bericht
   */
  class stdTeilnehmerberichte {}

?>