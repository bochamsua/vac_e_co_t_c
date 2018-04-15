<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Model_Adminhtml_Search_Equipment extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Logistics_Model_Adminhtml_Search_Equipment
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_logistics/equipment_collection')
            ->addFieldToFilter('equipment_name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $equipment) {
            $arr[] = array(
                'id'          => 'equipment/1/'.$equipment->getId(),
                'type'        => Mage::helper('bs_logistics')->__('Equipment'),
                'name'        => $equipment->getEquipmentName(),
                'description' => $equipment->getEquipmentName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/logistics_equipment/edit',
                    array('id'=>$equipment->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
