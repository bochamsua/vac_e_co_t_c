<?php
/**
 * BS_Handover extension
 * 
 * @category       BS
 * @package        BS_Handover
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_Handover
 * @author Bui Phong
 */
class BS_Handover_Model_Adminhtml_Search_Handoverone extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Handover_Model_Adminhtml_Search_Handoverone
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_handover/handoverone_collection')
            ->addFieldToFilter('title', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $handoverone) {
            $arr[] = array(
                'id'          => 'handoverone/1/'.$handoverone->getId(),
                'type'        => Mage::helper('bs_handover')->__('Minutes of Handover V1'),
                'name'        => $handoverone->getTitle(),
                'description' => $handoverone->getTitle(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/handover_handoverone/edit',
                    array('id'=>$handoverone->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
