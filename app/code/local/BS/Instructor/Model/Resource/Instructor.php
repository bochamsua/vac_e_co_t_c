<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor resource model
 *
 * @category    BS
 * @package     BS_Instructor
 * @author      Bui Phong
 */
class BS_Instructor_Model_Resource_Instructor extends Mage_Catalog_Model_Resource_Abstract
{
    protected $_instructorProductTable = null;
    protected $_instructorCategoryTable = null;
    protected $_instructorCurriculumTable = null;


    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        $resource = Mage::getSingleton('core/resource');
        $this->setType('bs_instructor_instructor')
            ->setConnection(
                $resource->getConnection('instructor_read'),
                $resource->getConnection('instructor_write')
            );
        $this->_instructorProductTable = $this->getTable('bs_instructor/instructor_product');
        $this->_instructorCategoryTable = $this->getTable('bs_instructor/instructor_category');
        $this->_instructorCurriculumTable = $this->getTable('bs_instructor/instructor_curriculum');

    }

    /**
     * wrapper for main table getter
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getMainTable()
    {
        return $this->getEntityTable();
    }
}
