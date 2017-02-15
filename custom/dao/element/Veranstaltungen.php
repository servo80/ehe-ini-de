<?php

  namespace BB\model\element\dao;

  /**
   * @author Brandbox
   *
   * @method \BB\model\element\dao\Veranstaltungen[] getChildren(\string $tableID)
   * @method \BB\model\element\dao\Veranstaltungen[] getParents(\string $tableID)
   * @method \int[] getChildContentIDs(\string $tableID)
   * @method \int[] getParentContentIDs(\string $tableID)
   * @method \BB\custom\model\element\dao\stdVeranstaltungen getData(\int $languageID = 1)
   * @method \BB\custom\model\element\dao\stdVeranstaltungen setData(\object $row, \int $languageID = 1, \int $userID = 1)
   *
   * @method string getVeranstaltungsart(\int $languageID)
   * @method string getVeranstaltungstitel(\int $languageID)
   * @method string getVeranstaltungsreferenten(\int $languageID)
   * @method integer getVeranstaltungsstartdatum(\int $languageID)
   * @method integer getVeranstaltungsenddatum(\int $languageID)
   * @method integer getVeranstaltungsanmeldefrist(\int $languageID)
   * @method string getVeranstaltungsbeschreibung(\int $languageID)
   * @method string getVeranstaltungshinweise(\int $languageID)
   * @method string getVeranstaltungsort(\int $languageID)
   * @method string getVeranstaltungspreis(\int $languageID)
   * @method string getNewsbild(\int $languageID)
   * @method void setVeranstaltungsart(\string $value, \int $languageID)
   * @method void setVeranstaltungstitel(\string $value, \int $languageID)
   * @method void setVeranstaltungsreferenten(\string $value, \int $languageID)
   * @method void setVeranstaltungsstartdatum(\integer $value, \int $languageID)
   * @method void setVeranstaltungsenddatum(\integer $value, \int $languageID)
   * @method void setVeranstaltungsanmeldefrist(\integer $value, \int $languageID)
   * @method void setVeranstaltungsbeschreibung(\string $value, \int $languageID)
   * @method void setVeranstaltungshinweise(\string $value, \int $languageID)
   * @method void setVeranstaltungsort(\string $value, \int $languageID)
   * @method void setVeranstaltungspreis(\string $value, \int $languageID)
   * @method void setNewsbild(\string $value, \int $languageID)
   */
  class Veranstaltungen extends \BB\model\element\dao {}

?>