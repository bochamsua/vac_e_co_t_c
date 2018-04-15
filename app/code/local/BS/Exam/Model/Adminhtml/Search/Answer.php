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
class BS_Exam_Model_Adminhtml_Search_Answer extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Exam_Model_Adminhtml_Search_Answer
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_exam/answer_collection')
            ->addFieldToFilter('answer_answer', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $answer) {
            $arr[] = array(
                'id'          => 'answer/1/'.$answer->getId(),
                'type'        => Mage::helper('bs_exam')->__('Answer'),
                'name'        => $answer->getAnswerAnswer(),
                'description' => $answer->getAnswerAnswer(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/exam_answer/edit',
                    array('id'=>$answer->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
