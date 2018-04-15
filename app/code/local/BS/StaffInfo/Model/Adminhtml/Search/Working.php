<?php
/**
 * BS_StaffInfo extension
 * 
 * @category       BS
 * @package        BS_StaffInfo
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_StaffInfo
 * @author Bui Phong
 */
class BS_StaffInfo_Model_Adminhtml_Search_Working extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_StaffInfo_Model_Adminhtml_Search_Working
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_staffinfo/working_collection')
            ->addFieldToFilter('division_dept', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $working) {
            $arr[] = array(
                'id'          => 'working/1/'.$working->getId(),
                'type'        => Mage::helper('bs_staffinfo')->__('Related Working'),
                'name'        => $working->getDivisionDept(),
                'description' => $working->getDivisionDept(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/staffinfo_working/edit',
                    array('id'=>$working->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
