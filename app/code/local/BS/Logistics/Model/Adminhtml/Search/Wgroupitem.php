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
class BS_Logistics_Model_Adminhtml_Search_Wgroupitem extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Logistics_Model_Adminhtml_Search_Wgroupitem
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_logistics/wgroupitem_collection')
            ->addFieldToFilter('name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $wgroupitem) {
            $arr[] = array(
                'id'          => 'wgroupitem/1/'.$wgroupitem->getId(),
                'type'        => Mage::helper('bs_logistics')->__('Group Item'),
                'name'        => $wgroupitem->getName(),
                'description' => $wgroupitem->getName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/logistics_wgroupitem/edit',
                    array('id'=>$wgroupitem->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
