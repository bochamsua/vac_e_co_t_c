<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Classroom/Examroom product model
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Model_Classroom_Product extends Mage_Core_Model_Abstract
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
        $this->_init('bs_logistics/classroom_product');
    }

    /**
     * Save data for classroom/examroom-product relation
     * @access public
     * @param  BS_Logistics_Model_Classroom $classroom
     * @return BS_Logistics_Model_Classroom_Product
     * @author Bui Phong
     */
    public function saveClassroomRelation($classroom)
    {
        $data = $classroom->getProductsData();
        if (!is_null($data)) {
            $this->_getResource()->saveClassroomRelation($classroom, $data);
        }
        return $this;
    }

    /**
     * get products for classroom/examroom
     *
     * @access public
     * @param BS_Logistics_Model_Classroom $classroom
     * @return BS_Logistics_Model_Resource_Classroom_Product_Collection
     * @author Bui Phong
     */
    public function getProductCollection($classroom)
    {
        $collection = Mage::getResourceModel('bs_logistics/classroom_product_collection')
            ->addClassroomFilter($classroom);
        return $collection;
    }
}
