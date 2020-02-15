<?php

  namespace BB\custom\model;

  if(@secure !== true)
    die('unerlaubter Zugriff');

  /**
   * Class productionSeriesSearch
   * @author Philipp Frick
   * @date 28.01.2015
   * @since: BB 3.1.1
   */
  class eventsSearch {

    /**
     * @var array $countries
     */
    protected $fromTimestamp = 0;
    protected $toTimestamp = 0;
    protected $type = '';
    protected $seoTitle = '';

    /**
     * @var \BB\model\dao $dao
     */
    protected $dao = null;

    /**
     * @param \BB\model\dao $dao
     * @return self
     */
    public function __construct(\BB\model\dao $dao) {
      $this->dao = $dao;
    }

    /**
     * @param int $fromTimestamp
     */
    public function setFromTimestamp($fromTimestamp) {
      $this->fromTimestamp = $fromTimestamp;
    }

    /**
     * @param int $toTimestamp
     */
    public function setToTimestamp($toTimestamp) {
      $this->toTimestamp = $toTimestamp;
    }

    /**
     * @param string $type
     */
    public function setType($type) {
      $this->type = $type;
    }

    /**
     * @param string $seoTitle
     */
    public function setSeoTitle($seoTitle) {
      $this->seoTitle = $seoTitle;
    }

    /**
     * @param int $languageID
     * @param bool $type
     * @param bool $seoTitle
     * @return \BB\model\element\dao\caravan
     */
    public function getResults($languageID, $type = false, $seoTitle = false) {
      $fields = $this->getSearchFields($type, $seoTitle);
      $results = $this->dao->getRows((int)$languageID, $fields);

      return $results;
    }

    /**
     * @param bool $type
     * @return array
     */
    private function getSearchFields($type, $seoTitle) {

      $fields = array();
      if($this->fromTimestamp > 0):
      $fields[] = $this->getGreaterThanField('Veranstaltungsstartdatum', $this->fromTimestamp);
      endif;
      if($this->toTimestamp > 0):
        $fields[] = $this->getLessThanField('Veranstaltungsstartdatum', $this->toTimestamp);
      endif;
      if($type):
        $fields[] = $this->getLikeField('Veranstaltungsart', $this->type);
      endif;
      if($seoTitle):
        $fields[] = $this->getLikeField('VeranstaltungsSeoTitel', $this->seoTitle);
      endif;
      $fields[] = $this->getOrderByAscField('Veranstaltungsstartdatum');
      $fields[] = 'AND';

      return $fields;
    }

    /**
     * @param string $fieldIdentifier
     * @param int $expected
     * @return \BB\model\element\optSearchField
     */
    private function getGreaterThanField($fieldIdentifier, $expected) {
      $searchCategory = new \BB\model\element\optSearchField();
      $field = $searchCategory->id($fieldIdentifier)->isGreaterThan($expected);

      return $field;
    }

    /**
     * @param string $fieldIdentifier
     * @param int $expected
     * @return \BB\model\element\optSearchField
     */
    private function getLessThanField($fieldIdentifier, $expected) {
      $searchCategory = new \BB\model\element\optSearchField();
      $field = $searchCategory->id($fieldIdentifier)->isLessThan($expected);

      return $field;
    }

    /**
     * @param string $fieldIdentifier
     * @param int $expected
     * @return \BB\model\element\optSearchField
     */
    private function getLikeField($fieldIdentifier, $expected) {
      $searchCategory = new \BB\model\element\optSearchField();
      $field = $searchCategory->id($fieldIdentifier)->like($expected);

      return $field;
    }

    /**
     * @param string $fieldIdentifier
     * @return \BB\model\element\optSearchField
     */
    private function getOrderByAscField($fieldIdentifier) {

      $searchCategory = new \BB\model\element\optSearchField();
      $field = $searchCategory->id($fieldIdentifier)->sortAsc();

      return $field;
    }

  }

?>
