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
class BS_Logistics_Model_Adminhtml_Search_Otherroom extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Logistics_Model_Adminhtml_Search_Otherroom
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_logistics/otherroom_collection')
            ->addFieldToFilter('otherroom_name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $otherroom) {
            $arr[] = array(
                'id'          => 'otherroom/1/'.$otherroom->getId(),
                'type'        => Mage::helper('bs_logistics')->__('Other room'),
                'name'        => $otherroom->getOtherroomName(),
                'description' => $otherroom->getOtherroomName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/logistics_otherroom/edit',
                    array('id'=>$otherroom->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
