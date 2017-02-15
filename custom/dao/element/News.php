<?php

  namespace BB\model\element\dao;

  /**
   * @author Brandbox
   *
   * @method \BB\model\element\dao\News[] getChildren(\string $tableID)
   * @method \BB\model\element\dao\News[] getParents(\string $tableID)
   * @method \int[] getChildContentIDs(\string $tableID)
   * @method \int[] getParentContentIDs(\string $tableID)
   * @method \BB\custom\model\element\dao\stdNews getData(\int $languageID = 1)
   * @method \BB\custom\model\element\dao\stdNews setData(\object $row, \int $languageID = 1, \int $userID = 1)
   *
   * @method integer getNewsdatum(\int $languageID)
   * @method string getNewsbild(\int $languageID)
   * @method string getNewsheadline(\int $languageID)
   * @method string getNewsteaser(\int $languageID)
   * @method string getNewstext(\int $languageID)
   * @method void setNewsdatum(\integer $value, \int $languageID)
   * @method void setNewsbild(\string $value, \int $languageID)
   * @method void setNewsheadline(\string $value, \int $languageID)
   * @method void setNewsteaser(\string $value, \int $languageID)
   * @method void setNewstext(\string $value, \int $languageID)
   */
  class News extends \BB\model\element\dao {}

?>