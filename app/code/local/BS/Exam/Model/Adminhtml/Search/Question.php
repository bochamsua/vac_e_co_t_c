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
class BS_Exam_Model_Adminhtml_Search_Question extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Exam_Model_Adminhtml_Search_Question
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_exam/question_collection')
            ->addFieldToFilter('question_question', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $question) {
            $arr[] = array(
                'id'          => 'question/1/'.$question->getId(),
                'type'        => Mage::helper('bs_exam')->__('Question'),
                'name'        => $question->getQuestionQuestion(),
                'description' => $question->getQuestionQuestion(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/exam_question/edit',
                    array('id'=>$question->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
