<?php
/**
 * BS_Subject extension
 * 
 * @category       BS
 * @package        BS_Subject
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_Subject
 * @author Bui Phong
 */
class BS_Subject_Model_Adminhtml_Search_Subject extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Subject_Model_Adminhtml_Search_Subject
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_subject/subject_collection')
            ->addFieldToFilter('subject_name', array('like' => '%'.$this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $subject) {
            $arr[] = array(
                'id'          => 'subject/1/'.$subject->getId(),
                'type'        => Mage::helper('bs_subject')->__('Subject'),
                'name'        => $subject->getSubjectName(),
                //'description' => $subject->getSubjectName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/subject_subject/edit',
                    array('id'=>$subject->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
