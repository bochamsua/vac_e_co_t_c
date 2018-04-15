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
class BS_Logistics_Model_Adminhtml_Search_Wgroup extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Logistics_Model_Adminhtml_Search_Wgroup
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_logistics/wgroup_collection')
            ->addFieldToFilter('name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $wgroup) {
            $arr[] = array(
                'id'          => 'wgroup/1/'.$wgroup->getId(),
                'type'        => Mage::helper('bs_logistics')->__('Group'),
                'name'        => $wgroup->getName(),
                'description' => $wgroup->getName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/logistics_wgroup/edit',
                    array('id'=>$wgroup->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
