<?php
/**
 * BS_CourseCost extension
 * 
 * @category       BS
 * @package        BS_CourseCost
 * @copyright      Copyright (c) 2016
 */
/**
 * Manage Group Items model
 *
 * @category    BS
 * @package     BS_CourseCost
 * @author Bui Phong
 */
class BS_CourseCost_Model_Costitem extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_coursecost_costitem';
    const CACHE_TAG = 'bs_coursecost_costitem';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_coursecost_costitem';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'costitem';

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
        $this->_init('bs_coursecost/costitem');
    }

    /**
     * before save manage group items
     *
     * @access protected
     * @return BS_CourseCost_Model_Costitem
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
     * save manage group items relation
     *
     * @access public
     * @return BS_CourseCost_Model_Costitem
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
                        ->addFieldToFilter('costitem_id', $this->getId());
                $this->setData('_coursecost_collection', $collection);
            }
        }
        return $this->getData('_coursecost_collection');
    }

    /**
     * Retrieve parent 
     *
     * @access public
     * @return null|BS_CourseCost_Model_Costgroup
     * @author Bui Phong
     */
    public function getParentCostgroup()
    {
        if (!$this->hasData('_parent_costgroup')) {
            if (!$this->getCostgroupId()) {
                return null;
            } else {
                $costgroup = Mage::getModel('bs_coursecost/costgroup')
                    ->load($this->getCostgroupId());
                if ($costgroup->getId()) {
                    $this->setData('_parent_costgroup', $costgroup);
                } else {
                    $this->setData('_parent_costgroup', null);
                }
            }
        }
        return $this->getData('_parent_costgroup');
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
