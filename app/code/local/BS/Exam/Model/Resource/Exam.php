<?php
/**
 * BS_Exam extension
 * 
 * @category       BS
 * @package        BS_Exam
 * @copyright      Copyright (c) 2015
 */
/**
 * Exam resource model
 *
 * @category    BS
 * @package     BS_Exam
 * @author Bui Phong
 */
class BS_Exam_Model_Resource_Exam extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function _construct()
    {
        $this->_init('bs_exam/exam', 'entity_id');
    }

    /**
     * process multiple select fields
     *
     * @access protected
     * @param Mage_Core_Model_Abstract $object
     * @return BS_Exam_Model_Resource_Exam
     * @author Bui Phong
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        $subjectids = $object->getSubjectIds();
        if (is_array($subjectids)) {
            $object->setSubjectIds(implode(',', $subjectids));
        }
        $examiners = $object->getExaminers();
        if (is_array($examiners)) {
            $object->setExaminers(implode(',', $examiners));
        }
        return parent::_beforeSave($object);
    }
}
