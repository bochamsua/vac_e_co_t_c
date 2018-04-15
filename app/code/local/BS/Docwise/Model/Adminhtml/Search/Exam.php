<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Model_Adminhtml_Search_Exam extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Docwise_Model_Adminhtml_Search_Exam
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_docwise/exam_collection')
            ->addFieldToFilter('exam_code', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $exam) {
            $arr[] = array(
                'id'          => 'exam/1/'.$exam->getId(),
                'type'        => Mage::helper('bs_docwise')->__('Exam'),
                'name'        => $exam->getExamCode(),
                'description' => $exam->getExamCode(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/docwise_exam/edit',
                    array('id'=>$exam->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
