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
class BS_Logistics_Model_Adminhtml_Search_Foldercontent extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Logistics_Model_Adminhtml_Search_Foldercontent
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_logistics/foldercontent_collection')
            ->addFieldToFilter('title', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $foldercontent) {
            $arr[] = array(
                'id'          => 'foldercontent/1/'.$foldercontent->getId(),
                'type'        => Mage::helper('bs_logistics')->__('Folder Content'),
                'name'        => $foldercontent->getTitle(),
                'description' => $foldercontent->getTitle(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/logistics_foldercontent/edit',
                    array('id'=>$foldercontent->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
