<?php
/**
 * BS_Bank extension
 * 
 * @category       BS
 * @package        BS_Bank
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_Bank
 * @author      Bui Phong
 */
class BS_Bank_Model_Adminhtml_Search_Subject extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Bank_Model_Adminhtml_Search_Subject
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_bank/subject_collection')
            ->addFieldToFilter('subject_name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $subject) {
            $arr[] = array(
                'id'          => 'subject/1/'.$subject->getId(),
                'type'        => Mage::helper('bs_bank')->__('Subject'),
                'name'        => $subject->getSubjectName(),
                'description' => $subject->getSubjectName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/bank_subject/edit',
                    array('id'=>$subject->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
