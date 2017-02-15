<?php

  namespace BB\custom\model\element\dao;

  if(@secure !== true)
    die('forbidden');

  /**
   * @author Brandbox
   *
   * @method \BB\model\element\dao\News getRow(\int $contentID)
   * @method \BB\model\element\dao\News[] getRows(\int $languageID, array $fields = array(), \int $offset = 0, \int $limit = 0)
   * @method \BB\model\element\dao\News[] getDaoElements(array $contentIDs)
   * @method \BB\model\element\dao\News[] getChildren(\string $tableID, \int $contentID)
   * @method \BB\model\element\dao\News[] getParents(\string $tableID, \int $contentID)
   * @method \int[] getChildContentIDs(\string $tableID, \int $contentID)
   * @method \int[] getParentContentIDs(\string $tableID, \int $contentID)
   * @method \BB\model\element\dao\News saveForm(\int $contentID, array $data = array(), \int $languageID = 1, \int $userID = 0)
   * @method \BB\model\element\dao\News setData(\int $contentID,\object $row, \int $languageID = 1, \int $userID = 1)
   * @method \BB\custom\model\element\dao\\stdNews getData(\int $contentID, \int $languageID = 1)
   *
   * @method integer getNewsdatum(\int $contentID, \int $languageID)
   * @method string getNewsbild(\int $contentID, \int $languageID)
   * @method string getNewsheadline(\int $contentID, \int $languageID)
   * @method string getNewsteaser(\int $contentID, \int $languageID)
   * @method string getNewstext(\int $contentID, \int $languageID)
   * @method void setNewsdatum(\int $contentID, \integer $value, \int $languageID)
   * @method void setNewsbild(\int $contentID, \string $value, \int $languageID)
   * @method void setNewsheadline(\int $contentID, \string $value, \int $languageID)
   * @method void setNewsteaser(\int $contentID, \string $value, \int $languageID)
   * @method void setNewstext(\int $contentID, \string $value, \int $languageID)
   */
  class News extends \BB\model\dao {

    /**
     * @return self
     */
    public static function instance() {
      return self::get('custom/dao/News.xml');
    }
  }

  /**
   * @property integer Newsdatum
   * @property string Newsbild
   * @property string Newsheadline
   * @property string Newsteaser
   * @property string Newstext
   */
  class stdNews {}

?>