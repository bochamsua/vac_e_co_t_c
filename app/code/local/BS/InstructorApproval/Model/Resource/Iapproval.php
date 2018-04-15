<?php
/**
 * BS_InstructorApproval extension
 * 
 * @category       BS
 * @package        BS_InstructorApproval
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Approval resource model
 *
 * @category    BS
 * @package     BS_InstructorApproval
 * @author Bui Phong
 */
class BS_InstructorApproval_Model_Resource_Iapproval extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function _construct()
    {
        $this->_init('bs_instructorapproval/iapproval', 'entity_id');
    }

    /**
     * process multiple select fields
     *
     * @access protected
     * @param Mage_Core_Model_Abstract $object
     * @return BS_InstructorApproval_Model_Resource_Iapproval
     * @author Bui Phong
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        $iapprovalcompliance = $object->getIapprovalCompliance();
        if (is_array($iapprovalcompliance)) {
            $object->setIapprovalCompliance(implode(',', $iapprovalcompliance));
        }
        return parent::_beforeSave($object);
    }
}
