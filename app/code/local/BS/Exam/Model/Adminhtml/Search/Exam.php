<?php
/**
 * BS_Exam extension
 * 
 * @category       BS
 * @package        BS_Exam
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_Exam
 * @author Bui Phong
 */
class BS_Exam_Model_Adminhtml_Search_Exam extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Exam_Model_Adminhtml_Search_Exam
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_exam/exam_collection')
            ->addFieldToFilter('course_id', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $exam) {
            $arr[] = array(
                'id'          => 'exam/1/'.$exam->getId(),
                'type'        => Mage::helper('bs_exam')->__('Exam'),
                'name'        => $exam->getExamContent(),
                //'description' => $exam->getCourseId(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/exam_exam/edit',
                    array('id'=>$exam->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
