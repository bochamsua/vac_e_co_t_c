<?php
/**
 * BS_Shortcut extension
 * 
 * @category       BS
 * @package        BS_Shortcut
 * @copyright      Copyright (c) 2015
 */
/**
 * Shortcut model
 *
 * @category    BS
 * @package     BS_Shortcut
 * @author Bui Phong
 */
class BS_Shortcut_Model_Shortcut extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_shortcut_shortcut';
    const CACHE_TAG = 'bs_shortcut_shortcut';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_shortcut_shortcut';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'shortcut';

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
        $this->_init('bs_shortcut/shortcut');
    }

    /**
     * before save shortcut
     *
     * @access protected
     * @return BS_Shortcut_Model_Shortcut
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
     * save shortcut relation
     *
     * @access public
     * @return BS_Shortcut_Model_Shortcut
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
