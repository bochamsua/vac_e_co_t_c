<?php
/**
 * BS_InstructorCopy extension
 * 
 * @category       BS
 * @package        BS_InstructorCopy
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_InstructorCopy
 * @author Bui Phong
 */
class BS_InstructorCopy_Model_Adminhtml_Search_Instructorcopy extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_InstructorCopy_Model_Adminhtml_Search_Instructorcopy
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_instructorcopy/instructorcopy_collection')
            ->addFieldToFilter('c_from', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $instructorcopy) {
            $arr[] = array(
                'id'          => 'instructorcopy/1/'.$instructorcopy->getId(),
                'type'        => Mage::helper('bs_instructorcopy')->__('Instructor Copy'),
                'name'        => $instructorcopy->getCFrom(),
                'description' => $instructorcopy->getCFrom(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/instructorcopy_instructorcopy/edit',
                    array('id'=>$instructorcopy->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
