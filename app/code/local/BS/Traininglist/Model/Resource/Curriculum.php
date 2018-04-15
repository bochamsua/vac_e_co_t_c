<?php
/**
 * BS_Traininglist extension
 * 
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * Training Curriculum resource model
 *
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */
class BS_Traininglist_Model_Resource_Curriculum extends Mage_Catalog_Model_Resource_Abstract
{
    protected $_curriculumProductTable = null;
    protected $_curriculumCategoryTable = null;


    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        $resource = Mage::getSingleton('core/resource');
        $this->setType('bs_traininglist_curriculum')
            ->setConnection(
                $resource->getConnection('curriculum_read'),
                $resource->getConnection('curriculum_write')
            );
        $this->_curriculumProductTable = $this->getTable('bs_traininglist/curriculum_product');
        $this->_curriculumCategoryTable = $this->getTable('bs_traininglist/curriculum_category');

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
