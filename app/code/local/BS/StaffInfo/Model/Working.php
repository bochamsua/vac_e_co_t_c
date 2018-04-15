<?php
/**
 * BS_StaffInfo extension
 * 
 * @category       BS
 * @package        BS_StaffInfo
 * @copyright      Copyright (c) 2015
 */
/**
 * Related Working model
 *
 * @category    BS
 * @package     BS_StaffInfo
 * @author Bui Phong
 */
class BS_StaffInfo_Model_Working extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_staffinfo_working';
    const CACHE_TAG = 'bs_staffinfo_working';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_staffinfo_working';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'working';

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
        $this->_init('bs_staffinfo/working');
    }

    /**
     * before save related working
     *
     * @access protected
     * @return BS_StaffInfo_Model_Working
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
     * save related working relation
     *
     * @access public
     * @return BS_StaffInfo_Model_Working
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
