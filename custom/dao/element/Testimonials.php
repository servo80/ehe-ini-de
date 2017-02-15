<?php

  namespace BB\model\element\dao;

  /**
   * @author Brandbox
   *
   * @method \BB\model\element\dao\Testimonials[] getChildren(\string $tableID)
   * @method \BB\model\element\dao\Testimonials[] getParents(\string $tableID)
   * @method \int[] getChildContentIDs(\string $tableID)
   * @method \int[] getParentContentIDs(\string $tableID)
   * @method \BB\custom\model\element\dao\stdTestimonials getData(\int $languageID = 1)
   * @method \BB\custom\model\element\dao\stdTestimonials setData(\object $row, \int $languageID = 1, \int $userID = 1)
   *
   * @method string getTestimonial(\int $languageID)
   * @method void setTestimonial(\string $value, \int $languageID)
   */
  class Testimonials extends \BB\model\element\dao {}

?>