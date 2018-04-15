<?php
/**
 * BS_CourseDoc extension
 * 
 * @category       BS
 * @package        BS_CourseDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Course Document product model
 *
 * @category    BS
 * @package     BS_CourseDoc
 * @author      Bui Phong
 */
class BS_CourseDoc_Model_Coursedoc_Product extends Mage_Core_Model_Abstract
{
    /**
     * Initialize resource
     *
     * @access protected
     * @return void
     * @author Bui Phong
     */
    protected function _construct()
    {
        $this->_init('bs_coursedoc/coursedoc_product');
    }

    /**
     * Save data for course doc-product relation
     * @access public
     * @param  BS_CourseDoc_Model_Coursedoc $coursedoc
     * @return BS_CourseDoc_Model_Coursedoc_Product
     * @author Bui Phong
     */
    public function saveCoursedocRelation($coursedoc)
    {
        $data = $coursedoc->getProductsData();
        if (!is_null($data)) {
            $this->_getResource()->saveCoursedocRelation($coursedoc, $data);
        }
        return $this;
    }

    /**
     * get products for course doc
     *
     * @access public
     * @param BS_CourseDoc_Model_Coursedoc $coursedoc
     * @return BS_CourseDoc_Model_Resource_Coursedoc_Product_Collection
     * @author Bui Phong
     */
    public function getProductCollection($coursedoc)
    {
        $collection = Mage::getResourceModel('bs_coursedoc/coursedoc_product_collection')
            ->addCoursedocFilter($coursedoc);
        return $collection;
    }
}
