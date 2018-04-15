<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Classroom/Examroom - product relation resource model collection
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Model_Resource_Classroom_Product_Collection extends Mage_Catalog_Model_Resource_Product_Collection
{
    /**
     * remember if fields have been joined
     *
     * @var bool
     */
    protected $_joinedFields = false;

    /**
     * join the link table
     *
     * @access public
     * @return BS_Logistics_Model_Resource_Classroom_Product_Collection
     * @author Bui Phong
     */
    public function joinFields()
    {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                array('related' => $this->getTable('bs_logistics/classroom_product')),
                'related.product_id = e.entity_id',
                array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    /**
     * add classroom/examroom filter
     *
     * @access public
     * @param BS_Logistics_Model_Classroom | int $classroom
     * @return BS_Logistics_Model_Resource_Classroom_Product_Collection
     * @author Bui Phong
     */
    public function addClassroomFilter($classroom)
    {
        if ($classroom instanceof BS_Logistics_Model_Classroom) {
            $classroom = $classroom->getId();
        }
        if (!$this->_joinedFields ) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.classroom_id = ?', $classroom);
        return $this;
    }
}
