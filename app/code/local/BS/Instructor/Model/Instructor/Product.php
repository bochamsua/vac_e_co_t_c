<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor product model
 *
 * @category    BS
 * @package     BS_Instructor
 * @author      Bui Phong
 */
class BS_Instructor_Model_Instructor_Product extends Mage_Core_Model_Abstract
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
        $this->_init('bs_instructor/instructor_product');
    }

    /**
     * Save data for instructor-product relation
     * @access public
     * @param  BS_Instructor_Model_Instructor $instructor
     * @return BS_Instructor_Model_Instructor_Product
     * @author Bui Phong
     */
    public function saveInstructorRelation($instructor)
    {
        $data = $instructor->getProductsData();
        if (!is_null($data)) {
            $this->_getResource()->saveInstructorRelation($instructor, $data);
        }
        return $this;
    }

    /**
     * get products for instructor
     *
     * @access public
     * @param BS_Instructor_Model_Instructor $instructor
     * @return BS_Instructor_Model_Resource_Instructor_Product_Collection
     * @author Bui Phong
     */
    public function getProductCollection($instructor)
    {
        $collection = Mage::getResourceModel('bs_instructor/instructor_product_collection')
            ->addInstructorFilter($instructor);
        return $collection;
    }
}
