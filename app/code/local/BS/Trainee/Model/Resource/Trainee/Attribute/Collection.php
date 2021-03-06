<?php
/**
 * BS_Trainee extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       BS
 * @package        BS_Trainee
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Trainee attribute collection model
 *
 * @category    BS
 * @package     BS_Trainee
 * @author      Bui Phong
 */
class BS_Trainee_Model_Resource_Trainee_Attribute_Collection extends Mage_Eav_Model_Resource_Entity_Attribute_Collection
{
    /**
     * init attribute select
     *
     * @access protected
     * @return BS_Trainee_Model_Resource_Trainee_Attribute_Collection
     * @author Bui Phong
     */
    protected function _initSelect()
    {
        $this->getSelect()->from(array('main_table' => $this->getResource()->getMainTable()))
            ->where(
                'main_table.entity_type_id=?',
                Mage::getModel('eav/entity')->setType('bs_trainee_trainee')->getTypeId()
            )
            ->join(
                array('additional_table' => $this->getTable('bs_trainee/eav_attribute')),
                'additional_table.attribute_id=main_table.attribute_id'
            );
        return $this;
    }

    /**
     * set entity type filter
     *
     * @access public
     * @param string $typeId
     * @return BS_Trainee_Model_Resource_Trainee_Attribute_Collection
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
     * @return BS_Trainee_Model_Resource_Trainee_Attribute_Collection
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
     * @return BS_Trainee_Model_Resource_Trainee_Attribute_Collection
     * @author Bui Phong
     */
    public function addEditableFilter()
    {
        return $this->addFieldToFilter('additional_table.is_editable', 1);
    }
}
