<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Exam - product relation model
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Model_Resource_Exam_Docwisement extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * initialize resource model
     *
     * @access protected
     * @see Mage_Core_Model_Resource_Abstract::_construct()
     * @author Bui Phong
     */
    protected function  _construct()
    {
        $this->_init('bs_docwise/exam_docwisement', 'rel_id');
    }
    /**
     * Save exam - product relations
     *
     * @access public
     * @param BS_Docwise_Model_Exam $exam
     * @param array $data
     * @return BS_Docwise_Model_Resource_Exam_Product
     * @author Bui Phong
     */
    public function saveExamRelation($exam, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('exam_id=?', $exam->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $productId => $info) {
            $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                    'exam_id' => $exam->getId(),
                    'docwisement_id'    => $productId,
                    'position'      => @$info['position']
                )
            );
        }
        return $this;
    }

    /**
     * Save  product - exam relations
     *
     * @access public
     * @param Mage_Catalog_Model_Product $prooduct
     * @param array $data
     * @return BS_Docwise_Model_Resource_Exam_Product
     * @@author Bui Phong
     */
    public function saveDocwisementRelation($docwisement, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('docwisement_id=?', $docwisement->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $examId => $info) {
            $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                    'exam_id' => $examId,
                    'docwisement_id'    => $docwisement->getId(),
                    'position'      => @$info['position']
                )
            );
        }
        return $this;
    }
}
