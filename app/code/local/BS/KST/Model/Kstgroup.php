<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2016
 */
/**
 * Group model
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Model_Kstgroup extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_kst_kstgroup';
    const CACHE_TAG = 'bs_kst_kstgroup';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_kst_kstgroup';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'kstgroup';

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
        $this->_init('bs_kst/kstgroup');
    }

    /**
     * before save group
     *
     * @access protected
     * @return BS_KST_Model_Kstgroup
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
     * save group relation
     *
     * @access public
     * @return BS_KST_Model_Kstgroup
     * @author Bui Phong
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
    }

    /**
     * Retrieve  collection
     *
     * @access public
     * @return BS_KST_Model_Kstmember_Collection
     * @author Bui Phong
     */
    public function getSelectedKstmembersCollection()
    {
        if (!$this->hasData('_kstmember_collection')) {
            if (!$this->getId()) {
                return new Varien_Data_Collection();
            } else {
                $collection = Mage::getResourceModel('bs_kst/kstmember_collection')
                        ->addFieldToFilter('kstgroup_id', $this->getId());
                $this->setData('_kstmember_collection', $collection);
            }
        }
        return $this->getData('_kstmember_collection');
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
