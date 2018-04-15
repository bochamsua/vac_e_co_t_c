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
class BS_Exam_Model_Adminhtml_Search_Examresult extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Exam_Model_Adminhtml_Search_Examresult
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_exam/examresult_collection')
            ->addFieldToFilter('first_mark', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $examresult) {
            $arr[] = array(
                'id'          => 'examresult/1/'.$examresult->getId(),
                'type'        => Mage::helper('bs_exam')->__('Exam Result'),
                'name'        => $examresult->getFirstMark(),
                'description' => $examresult->getFirstMark(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/exam_examresult/edit',
                    array('id'=>$examresult->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
