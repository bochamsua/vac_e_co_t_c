<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2016
 */
/**
 * Group Item model
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Model_Wgroupitem extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_logistics_wgroupitem';
    const CACHE_TAG = 'bs_logistics_wgroupitem';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_logistics_wgroupitem';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'wgroupitem';

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
        $this->_init('bs_logistics/wgroupitem');
    }

    /**
     * before save group item
     *
     * @access protected
     * @return BS_Logistics_Model_Wgroupitem
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
     * save group item relation
     *
     * @access public
     * @return BS_Logistics_Model_Wgroupitem
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
                        ->addFieldToFilter('wgroupitem_id', $this->getId());
                $this->setData('_wtool_collection', $collection);
            }
        }
        return $this->getData('_wtool_collection');
    }

    /**
     * Retrieve parent 
     *
     * @access public
     * @return null|BS_Logistics_Model_Wgroup
     * @author Bui Phong
     */
    public function getParentWgroup()
    {
        if (!$this->hasData('_parent_wgroup')) {
            if (!$this->getWgroupId()) {
                return null;
            } else {
                $wgroup = Mage::getModel('bs_logistics/wgroup')
                    ->load($this->getWgroupId());
                if ($wgroup->getId()) {
                    $this->setData('_parent_wgroup', $wgroup);
                } else {
                    $this->setData('_parent_wgroup', null);
                }
            }
        }
        return $this->getData('_parent_wgroup');
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
     * Retrieve parent
     *
     * @access public
     * @return null|BS_Logistics_Model_Workshop
     * @author Bui Phong
     */
    public function getParentWorkshop()
    {
        if (!$this->hasData('_parent_workshop')) {
            if (!$this->getWorkshopId()) {
                return null;
            } else {
                $workshop = Mage::getModel('bs_logistics/workshop')
                    ->load($this->getWorkshopId());
                if ($workshop->getId()) {
                    $this->setData('_parent_workshop', $workshop);
                } else {
                    $this->setData('_parent_workshop', null);
                }
            }
        }
        return $this->getData('_parent_workshop');
    }

    /**
     * Retrieve parent
     *
     * @access public
     * @return null|BS_Logistics_Model_Grouptype
     * @author Bui Phong
     */
    public function getParentGrouptype()
    {
        if (!$this->hasData('_parent_grouptype')) {
            if (!$this->getGrouptypeId()) {
                return null;
            } else {
                $grouptype = Mage::getModel('bs_logistics/grouptype')
                    ->load($this->getGrouptypeId());
                if ($grouptype->getId()) {
                    $this->setData('_parent_grouptype', $grouptype);
                } else {
                    $this->setData('_parent_grouptype', null);
                }
            }
        }
        return $this->getData('_parent_grouptype');
    }


}
