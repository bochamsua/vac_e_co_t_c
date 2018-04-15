<?php
/**
 * BS_InstructorApproval extension
 * 
 * @category       BS
 * @package        BS_InstructorApproval
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Approval model
 *
 * @category    BS
 * @package     BS_InstructorApproval
 * @author Bui Phong
 */
class BS_InstructorApproval_Model_Iapproval extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_instructorapproval_iapproval';
    const CACHE_TAG = 'bs_instructorapproval_iapproval';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_instructorapproval_iapproval';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'iapproval';

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('bs_instructorapproval/iapproval');
    }

    /**
     * before save instructor approval
     *
     * @access protected
     * @return BS_InstructorApproval_Model_Iapproval
     * @author Bui Phong
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * save instructor approval relation
     *
     * @access public
     * @return BS_InstructorApproval_Model_Iapproval
     * @author Bui Phong
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
    }

    /**
     * get default values
     *
     * @access public
     * @return array
     * @author Bui Phong
     */
    public function getDefaultValues()
    {
        $values = array();
        $values['status'] = 1;
        return $values;
    }
    
    /**
      * get Compliance With
      *
      * @access public
      * @return array
      * @author Bui Phong
      */
    public function getIapprovalCompliance()
    {
        if (!$this->getData('iapproval_compliance')) {
            return explode(',', $this->getData('iapproval_compliance'));
        }
        return $this->getData('iapproval_compliance');
    }
}
