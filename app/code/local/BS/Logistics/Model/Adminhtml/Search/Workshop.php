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
class BS_Logistics_Model_Adminhtml_Search_Workshop extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Logistics_Model_Adminhtml_Search_Workshop
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_logistics/workshop_collection')
            ->addFieldToFilter('workshop_name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $workshop) {
            $arr[] = array(
                'id'          => 'workshop/1/'.$workshop->getId(),
                'type'        => Mage::helper('bs_logistics')->__('Workshop'),
                'name'        => $workshop->getWorkshopName(),
                'description' => $workshop->getWorkshopName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/logistics_workshop/edit',
                    array('id'=>$workshop->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
