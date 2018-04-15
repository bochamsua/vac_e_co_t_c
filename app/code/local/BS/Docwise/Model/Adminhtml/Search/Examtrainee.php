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
class BS_Docwise_Model_Adminhtml_Search_Examtrainee extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Docwise_Model_Adminhtml_Search_Examtrainee
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_docwise/examtrainee_collection')
            ->addFieldToFilter('exam_id', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $examtrainee) {
            $arr[] = array(
                'id'          => 'examtrainee/1/'.$examtrainee->getId(),
                'type'        => Mage::helper('bs_docwise')->__('Exam Trainee'),
                'name'        => $examtrainee->getExamId(),
                'description' => $examtrainee->getExamId(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/docwise_examtrainee/edit',
                    array('id'=>$examtrainee->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
