<?php
/**
 * BS_Handover extension
 * 
 * @category       BS
 * @package        BS_Handover
 * @copyright      Copyright (c) 2015
 */
/**
 * Minutes of Handover V1 model
 *
 * @category    BS
 * @package     BS_Handover
 * @author Bui Phong
 */
class BS_Handover_Model_Handoverone extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_handover_handoverone';
    const CACHE_TAG = 'bs_handover_handoverone';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_handover_handoverone';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'handoverone';

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
        $this->_init('bs_handover/handoverone');
    }

    /**
     * before save minutes of handover v1
     *
     * @access protected
     * @return BS_Handover_Model_Handoverone
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
     * save minutes of handover v1 relation
     *
     * @access public
     * @return BS_Handover_Model_Handoverone
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
