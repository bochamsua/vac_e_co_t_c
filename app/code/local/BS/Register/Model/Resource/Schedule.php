<?php
/**
 * BS_Register extension
 * 
 * @category       BS
 * @package        BS_Register
 * @copyright      Copyright (c) 2015
 */
/**
 * Course Schedule resource model
 *
 * @category    BS
 * @package     BS_Register
 * @author Bui Phong
 */
class BS_Register_Model_Resource_Schedule extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function _construct()
    {
        $this->_init('bs_register/schedule', 'entity_id');
    }

    /**
     * process multiple select fields
     *
     * @access protected
     * @param Mage_Core_Model_Abstract $object
     * @return BS_Register_Model_Resource_Schedule
     * @author Bui Phong
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        $schedulesubjects = $object->getScheduleSubjects();
        if (is_array($schedulesubjects)) {
            $object->setScheduleSubjects(implode(',', $schedulesubjects));
        }

        $schedulestarttime = $object->getScheduleStartTime();
        if (is_array($schedulestarttime)) {
            $object->setScheduleStartTime(implode(',', $schedulestarttime));
        }
        $schedulefinishtime = $object->getScheduleFinishTime();
        if (is_array($schedulefinishtime)) {
            $object->setScheduleFinishTime(implode(',', $schedulefinishtime));
        }
        return parent::_beforeSave($object);
    }
}
