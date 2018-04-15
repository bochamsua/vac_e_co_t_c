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
class BS_Docwise_Model_Exam_Filefolder extends Mage_Core_Model_Abstract
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
        $this->_init('bs_docwise/exam_filefolder');
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
        $data = $exam->getFilefoldersData();
        if (!is_null($data)) {
            $this->_getResource()->saveExamRelation($exam, $data);
        }
        return $this;
    }

    public function saveFilefolderRelation($filefolder)
    {
        $data = $filefolder->getExamsData();
        if (!is_null($data)) {
            $this->_getResource()->saveFilefolderRelation($filefolder, $data);
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
    public function getFilefolderCollection($exam)
    {
        $collection = Mage::getResourceModel('bs_docwise/exam_filefolder_collection')
            ->addExamFilter($exam);
        return $collection;
    }

    public function getExamCollection($filefolder)
    {
        $collection = Mage::getResourceModel('bs_docwise/exam_collection')
            ->addFilefolderFilter($filefolder);
        return $collection;
    }
}
