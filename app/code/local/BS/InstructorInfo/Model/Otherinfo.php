<?php
/**
 * BS_InstructorInfo extension
 * 
 * @category       BS
 * @package        BS_InstructorInfo
 * @copyright      Copyright (c) 2015
 */
/**
 * Other Info model
 *
 * @category    BS
 * @package     BS_InstructorInfo
 * @author Bui Phong
 */
class BS_InstructorInfo_Model_Otherinfo extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_instructorinfo_otherinfo';
    const CACHE_TAG = 'bs_instructorinfo_otherinfo';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_instructorinfo_otherinfo';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'otherinfo';

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
        $this->_init('bs_instructorinfo/otherinfo');
    }

    /**
     * before save other info
     *
     * @access protected
     * @return BS_InstructorInfo_Model_Otherinfo
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
     * save other info relation
     *
     * @access public
     * @return BS_InstructorInfo_Model_Otherinfo
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
    
}
