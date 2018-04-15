<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2016
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Model_Adminhtml_Search_Wtool extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Logistics_Model_Adminhtml_Search_Wtool
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_logistics/wtool_collection')
            ->addFieldToFilter('name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $wtool) {
            $arr[] = array(
                'id'          => 'wtool/1/'.$wtool->getId(),
                'type'        => Mage::helper('bs_logistics')->__('Tool'),
                'name'        => $wtool->getName(),
                'description' => $wtool->getName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/logistics_wtool/edit',
                    array('id'=>$wtool->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
