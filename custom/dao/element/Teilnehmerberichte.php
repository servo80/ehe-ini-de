<?php

  namespace BB\model\element\dao;

  /**
   * @author Brandbox
   *
   * @method \BB\model\element\dao\Teilnehmerberichte[] getChildren(\string $tableID)
   * @method \BB\model\element\dao\Teilnehmerberichte[] getParents(\string $tableID)
   * @method \int[] getChildContentIDs(\string $tableID)
   * @method \int[] getParentContentIDs(\string $tableID)
   * @method \BB\custom\model\element\dao\stdTeilnehmerberichte getData(\int $languageID = 1)
   * @method \BB\custom\model\element\dao\stdTeilnehmerberichte setData(\object $row, \int $languageID = 1, \int $userID = 1)
   *
   * @method string getBerichtautor(\int $languageID)
   * @method string getBerichtueberschrift(\int $languageID)
   * @method string getBericht(\int $languageID)
   * @method void setBerichtautor(\string $value, \int $languageID)
   * @method void setBerichtueberschrift(\string $value, \int $languageID)
   * @method void setBericht(\string $value, \int $languageID)
   */
  class Teilnehmerberichte extends \BB\model\element\dao {}

?>