<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Exam resource model
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Model_Resource_Exam extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function _construct()
    {
        $this->_init('bs_docwise/exam', 'entity_id');
    }

    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        $applyfor = $object->getExamRequestDept();
        if (is_array($applyfor)) {
            $object->setExamRequestDept(implode(',', $applyfor));
        }

        $applyfor1 = $object->getExaminer();
        if (is_array($applyfor1)) {
            $object->setExaminer(implode(',', $applyfor1));
        }

        return parent::_beforeSave($object);
    }
}
