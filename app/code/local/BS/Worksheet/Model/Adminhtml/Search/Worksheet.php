<?php
/**
 * BS_Worksheet extension
 * 
 * 
 * @category       BS
 * @package        BS_Worksheet
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_Worksheet
 * @author      Bui Phong
 */
class BS_Worksheet_Model_Adminhtml_Search_Worksheet extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Worksheet_Model_Adminhtml_Search_Worksheet
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_worksheet/worksheet_collection')
            ->addFieldToFilter('ws_name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $worksheet) {
            $arr[] = array(
                'id'          => 'worksheet/1/'.$worksheet->getId(),
                'type'        => Mage::helper('bs_worksheet')->__('Worksheet'),
                'name'        => $worksheet->getWsName(),
                'description' => $worksheet->getWsName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/worksheet_worksheet/edit',
                    array('id'=>$worksheet->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
