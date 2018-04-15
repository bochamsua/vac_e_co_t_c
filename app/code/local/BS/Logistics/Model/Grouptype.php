<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2016
 */
/**
 * Type model
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Model_Grouptype extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_logistics_grouptype';
    const CACHE_TAG = 'bs_logistics_grouptype';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_logistics_grouptype';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'grouptype';

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
        $this->_init('bs_logistics/grouptype');
    }

    /**
     * before save type
     *
     * @access protected
     * @return BS_Logistics_Model_Grouptype
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
     * save type relation
     *
     * @access public
     * @return BS_Logistics_Model_Grouptype
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
     * @return BS_Logistics_Model_Wgroup_Collection
     * @author Bui Phong
     */
    public function getSelectedWgroupsCollection()
    {
        if (!$this->hasData('_wgroup_collection')) {
            if (!$this->getId()) {
                return new Varien_Data_Collection();
            } else {
                $collection = Mage::getResourceModel('bs_logistics/wgroup_collection')
                        ->addFieldToFilter('grouptype_id', $this->getId());
                $this->setData('_wgroup_collection', $collection);
            }
        }
        return $this->getData('_wgroup_collection');
    }

    /**
     * Retrieve  collection
     *
     * @access public
     * @return BS_Logistics_Model_Wgroupitem_Collection
     * @author Bui Phong
     */
    public function getSelectedWgroupitemsCollection()
    {
        if (!$this->hasData('_wgroupitem_collection')) {
            if (!$this->getId()) {
                return new Varien_Data_Collection();
            } else {
                $collection = Mage::getResourceModel('bs_logistics/wgroupitem_collection')
                        ->addFieldToFilter('grouptype_id', $this->getId());
                $this->setData('_wgroupitem_collection', $collection);
            }
        }
        return $this->getData('_wgroupitem_collection');
    }

    /**
     * Retrieve  collection
     *
     * @access public
     * @return BS_Logistics_Model_Wtool_Collection
     * @author Bui Phong
     */
    public function getSelectedWtoolsCollection()
    {
        if (!$this->hasData('_wtool_collection')) {
            if (!$this->getId()) {
                return new Varien_Data_Collection();
            } else {
                $collection = Mage::getResourceModel('bs_logistics/wtool_collection')
                        ->addFieldToFilter('grouptype_id', $this->getId());
                $this->setData('_wtool_collection', $collection);
            }
        }
        return $this->getData('_wtool_collection');
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
