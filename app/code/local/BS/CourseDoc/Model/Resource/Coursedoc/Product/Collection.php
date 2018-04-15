<?php
/**
 * BS_CourseDoc extension
 * 
 * @category       BS
 * @package        BS_CourseDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Course Document - product relation resource model collection
 *
 * @category    BS
 * @package     BS_CourseDoc
 * @author      Bui Phong
 */
class BS_CourseDoc_Model_Resource_Coursedoc_Product_Collection extends Mage_Catalog_Model_Resource_Product_Collection
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
     * @return BS_CourseDoc_Model_Resource_Coursedoc_Product_Collection
     * @author Bui Phong
     */
    public function joinFields()
    {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                array('related' => $this->getTable('bs_coursedoc/coursedoc_product')),
                'related.product_id = e.entity_id',
                array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    /**
     * add course doc filter
     *
     * @access public
     * @param BS_CourseDoc_Model_Coursedoc | int $coursedoc
     * @return BS_CourseDoc_Model_Resource_Coursedoc_Product_Collection
     * @author Bui Phong
     */
    public function addCoursedocFilter($coursedoc)
    {
        if ($coursedoc instanceof BS_CourseDoc_Model_Coursedoc) {
            $coursedoc = $coursedoc->getId();
        }
        if (!$this->_joinedFields ) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.coursedoc_id = ?', $coursedoc);
        return $this;
    }
}
