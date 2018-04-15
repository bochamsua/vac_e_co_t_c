<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Exam - product relation resource model collection
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Model_Resource_Exam_Docwisement_Collection extends BS_Docwise_Model_Resource_Docwisement_Collection
{
    /**
     * remember if fields have been joined
     *
     * @var bool
     */
    protected $_joinedFields = false;

    /**
     * join the link table
     *
     * @access public
     * @return BS_Docwise_Model_Resource_Exam_Product_Collection
     * @author Bui Phong
     */
    public function joinFields()
    {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                array('related' => $this->getTable('bs_docwise/exam_docwisement')),
                'related.docwisement_id = main_table.entity_id',
                array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    /**
     * add exam filter
     *
     * @access public
     * @param BS_Docwise_Model_Exam | int $exam
     * @return BS_Docwise_Model_Resource_Exam_Product_Collection
     * @author Bui Phong
     */
    public function addExamFilter($exam)
    {
        if ($exam instanceof BS_Docwise_Model_Exam) {
            $exam = $exam->getId();
        }
        if (!$this->_joinedFields ) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.exam_id = ?', $exam);
        return $this;
    }
}
