<?php
/**
 * BS_Traininglist extension
 * 
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * Training Curriculum attribute collection model
 *
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */
class BS_Traininglist_Model_Resource_Curriculum_Attribute_Collection extends Mage_Eav_Model_Resource_Entity_Attribute_Collection
{
    /**
     * init attribute select
     *
     * @access protected
     * @return BS_Traininglist_Model_Resource_Curriculum_Attribute_Collection
     * @author Bui Phong
     */
    protected function _initSelect()
    {
        $this->getSelect()->from(array('main_table' => $this->getResource()->getMainTable()))
            ->where(
                'main_table.entity_type_id=?',
                Mage::getModel('eav/entity')->setType('bs_traininglist_curriculum')->getTypeId()
            )
            ->join(
                array('additional_table' => $this->getTable('bs_traininglist/eav_attribute')),
                'additional_table.attribute_id=main_table.attribute_id'
            );
        return $this;
    }

    /**
     * set entity type filter
     *
     * @access public
     * @param string $typeId
     * @return BS_Traininglist_Model_Resource_Curriculum_Attribute_Collection
     * @author Bui Phong
     */
    public function setEntityTypeFilter($typeId)
    {
        return $this;
    }

    /**
     * Specify filter by "is_visible" field
     *
     * @access public
     * @return BS_Traininglist_Model_Resource_Curriculum_Attribute_Collection
     * @author Bui Phong
     */
    public function addVisibleFilter()
    {
        return $this->addFieldToFilter('additional_table.is_visible', 1);
    }

    /**
     * Specify filter by "is_editable" field
     *
     * @access public
     * @return BS_Traininglist_Model_Resource_Curriculum_Attribute_Collection
     * @author Bui Phong
     */
    public function addEditableFilter()
    {
        return $this->addFieldToFilter('additional_table.is_editable', 1);
    }
}
