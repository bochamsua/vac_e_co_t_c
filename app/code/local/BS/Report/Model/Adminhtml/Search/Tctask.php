<?php
/**
 * BS_Report extension
 * 
 * @category       BS
 * @package        BS_Report
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_Report
 * @author Bui Phong
 */
class BS_Report_Model_Adminhtml_Search_Tctask extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Report_Model_Adminhtml_Search_Tctask
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_report/tctask_collection')
            ->addFieldToFilter('tctask_name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $tctask) {
            $arr[] = array(
                'id'          => 'tctask/1/'.$tctask->getId(),
                'type'        => Mage::helper('bs_report')->__('TC Task'),
                'name'        => $tctask->getTctaskName(),
                'description' => $tctask->getTctaskName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/report_tctask/edit',
                    array('id'=>$tctask->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
