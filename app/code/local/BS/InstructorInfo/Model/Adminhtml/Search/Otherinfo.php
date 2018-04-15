<?php
/**
 * BS_InstructorInfo extension
 * 
 * @category       BS
 * @package        BS_InstructorInfo
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_InstructorInfo
 * @author Bui Phong
 */
class BS_InstructorInfo_Model_Adminhtml_Search_Otherinfo extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_InstructorInfo_Model_Adminhtml_Search_Otherinfo
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_instructorinfo/otherinfo_collection')
            ->addFieldToFilter('title', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $otherinfo) {
            $arr[] = array(
                'id'          => 'otherinfo/1/'.$otherinfo->getId(),
                'type'        => Mage::helper('bs_instructorinfo')->__('Other Info'),
                'name'        => $otherinfo->getTitle(),
                'description' => $otherinfo->getTitle(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/instructorinfo_otherinfo/edit',
                    array('id'=>$otherinfo->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
