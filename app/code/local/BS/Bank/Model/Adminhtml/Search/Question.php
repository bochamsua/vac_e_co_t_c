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
class BS_Bank_Model_Adminhtml_Search_Question extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Bank_Model_Adminhtml_Search_Question
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_bank/question_collection')
            ->addFieldToFilter('curriculum_id', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $question) {
            $arr[] = array(
                'id'          => 'question/1/'.$question->getId(),
                'type'        => Mage::helper('bs_bank')->__('Question'),
                'name'        => $question->getCurriculumId(),
                'description' => $question->getCurriculumId(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/bank_question/edit',
                    array('id'=>$question->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
