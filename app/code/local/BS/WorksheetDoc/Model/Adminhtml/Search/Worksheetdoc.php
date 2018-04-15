<?php
/**
 * BS_WorksheetDoc extension
 * 
 * @category       BS
 * @package        BS_WorksheetDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_WorksheetDoc
 * @author      Bui Phong
 */
class BS_WorksheetDoc_Model_Adminhtml_Search_Worksheetdoc extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_WorksheetDoc_Model_Adminhtml_Search_Worksheetdoc
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_worksheetdoc/worksheetdoc_collection')
            ->addFieldToFilter('wsdoc_name', array('like' => '%'.$this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $worksheetdoc) {
            $arr[] = array(
                'id'          => 'worksheetdoc/1/'.$worksheetdoc->getId(),
                'type'        => Mage::helper('bs_worksheetdoc')->__('Worksheet Document'),
                'name'        => $worksheetdoc->getWsdocName(),
                'description' => $worksheetdoc->getWsdocName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/worksheetdoc_worksheetdoc/edit',
                    array('id'=>$worksheetdoc->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
