<?php
/**
 * BS_Traininglist extension
 * 
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * Training Curriculum - product relation resource model collection
 *
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */
class BS_Traininglist_Model_Resource_Curriculum_Product_Collection extends Mage_Catalog_Model_Resource_Product_Collection
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
     * @return BS_Traininglist_Model_Resource_Curriculum_Product_Collection
     * @author Bui Phong
     */
    public function joinFields()
    {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                array('related' => $this->getTable('bs_traininglist/curriculum_product')),
                'related.product_id = e.entity_id',
                array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    /**
     * add training curriculum filter
     *
     * @access public
     * @param BS_Traininglist_Model_Curriculum | int $curriculum
     * @return BS_Traininglist_Model_Resource_Curriculum_Product_Collection
     * @author Bui Phong
     */
    public function addCurriculumFilter($curriculum)
    {
        if ($curriculum instanceof BS_Traininglist_Model_Curriculum) {
            $curriculum = $curriculum->getId();
        }
        if (!$this->_joinedFields ) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.curriculum_id = ?', $curriculum);
        return $this;
    }
}
