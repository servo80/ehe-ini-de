<?php

  namespace BB\custom\model\element\dao;

  if(@secure !== true)
    die('forbidden');

  /**
   * @author Brandbox
   *
   * @method \BB\model\element\dao\Testimonials getRow(\int $contentID)
   * @method \BB\model\element\dao\Testimonials[] getRows(\int $languageID, array $fields = array(), \int $offset = 0, \int $limit = 0)
   * @method \BB\model\element\dao\Testimonials[] getDaoElements(array $contentIDs)
   * @method \BB\model\element\dao\Testimonials[] getChildren(\string $tableID, \int $contentID)
   * @method \BB\model\element\dao\Testimonials[] getParents(\string $tableID, \int $contentID)
   * @method \int[] getChildContentIDs(\string $tableID, \int $contentID)
   * @method \int[] getParentContentIDs(\string $tableID, \int $contentID)
   * @method \BB\model\element\dao\Testimonials saveForm(\int $contentID, array $data = array(), \int $languageID = 1, \int $userID = 0)
   * @method \BB\model\element\dao\Testimonials setData(\int $contentID,\object $row, \int $languageID = 1, \int $userID = 1)
   * @method \BB\custom\model\element\dao\stdTestimonials getData(\int $contentID, \int $languageID = 1)
   *
   * @method string getTestimonial(\int $contentID, \int $languageID)
   * @method void setTestimonial(\int $contentID, \string $value, \int $languageID)
   */
  class Testimonials extends \BB\model\dao {

    /**
     * @return self
     */
    public static function instance() {
      return self::get('custom/dao/Testimonials.xml');
    }
  }

  /**
   * @property string Testimonial
   */
  class stdTestimonials {}

?>