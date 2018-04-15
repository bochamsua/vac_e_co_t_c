<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Exam product model
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Model_Exam_Docwisement extends Mage_Core_Model_Abstract
{
    /**
     * Initialize resource
     *
     * @access protected
     * @return void
     * @author Bui Phong
     */
    protected function _construct()
    {
        $this->_init('bs_docwise/exam_docwisement');
    }

    /**
     * Save data for exam-product relation
     * @access public
     * @param  BS_Docwise_Model_Exam $exam
     * @return BS_Docwise_Model_Exam_Product
     * @author Bui Phong
     */
    public function saveExamRelation($exam)
    {
        $data = $exam->getDocwisementsData();
        if (!is_null($data)) {
            $this->_getResource()->saveExamRelation($exam, $data);
        }
        return $this;
    }

    /**
     * get products for exam
     *
     * @access public
     * @param BS_Docwise_Model_Exam $exam
     * @return BS_Docwise_Model_Resource_Exam_Product_Collection
     * @author Bui Phong
     */
    public function getDocwisementCollection($exam)
    {
        $collection = Mage::getResourceModel('bs_docwise/exam_docwisement_collection')
            ->addExamFilter($exam);
        return $collection;
    }
}
