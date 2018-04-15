<?php
/**
 * BS_CourseCost extension
 * 
 * @category       BS
 * @package        BS_CourseCost
 * @copyright      Copyright (c) 2016
 */
/**
 * Manage Cost Group model
 *
 * @category    BS
 * @package     BS_CourseCost
 * @author Bui Phong
 */
class BS_CourseCost_Model_Costgroup extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_coursecost_costgroup';
    const CACHE_TAG = 'bs_coursecost_costgroup';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_coursecost_costgroup';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'costgroup';

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
        $this->_init('bs_coursecost/costgroup');
    }

    /**
     * before save manage cost group
     *
     * @access protected
     * @return BS_CourseCost_Model_Costgroup
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
     * save manage cost group relation
     *
     * @access public
     * @return BS_CourseCost_Model_Costgroup
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
     * @return BS_CourseCost_Model_Costitem_Collection
     * @author Bui Phong
     */
    public function getSelectedCostitemsCollection()
    {
        if (!$this->hasData('_costitem_collection')) {
            if (!$this->getId()) {
                return new Varien_Data_Collection();
            } else {
                $collection = Mage::getResourceModel('bs_coursecost/costitem_collection')
                        ->addFieldToFilter('costgroup_id', $this->getId());
                $this->setData('_costitem_collection', $collection);
            }
        }
        return $this->getData('_costitem_collection');
    }

    /**
     * Retrieve  collection
     *
     * @access public
     * @return BS_CourseCost_Model_Coursecost_Collection
     * @author Bui Phong
     */
    public function getSelectedCoursecostsCollection()
    {
        if (!$this->hasData('_coursecost_collection')) {
            if (!$this->getId()) {
                return new Varien_Data_Collection();
            } else {
                $collection = Mage::getResourceModel('bs_coursecost/coursecost_collection')
                        ->addFieldToFilter('costgroup_id', $this->getId());
                $this->setData('_coursecost_collection', $collection);
            }
        }
        return $this->getData('_coursecost_collection');
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
