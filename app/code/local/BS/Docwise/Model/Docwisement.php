<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Docwise Document model
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Model_Docwisement extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_docwise_docwisement';
    const CACHE_TAG = 'bs_docwise_docwisement';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_docwise_docwisement';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'docwisement';

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
        $this->_init('bs_docwise/docwisement');
    }

    /**
     * before save docwise document
     *
     * @access protected
     * @return BS_Docwise_Model_Docwisement
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
     * save docwise document relation
     *
     * @access public
     * @return BS_Docwise_Model_Docwisement
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
