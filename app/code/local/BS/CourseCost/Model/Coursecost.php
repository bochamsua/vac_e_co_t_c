<?php
/**
 * BS_CourseCost extension
 * 
 * @category       BS
 * @package        BS_CourseCost
 * @copyright      Copyright (c) 2016
 */
/**
 * Course Cost model
 *
 * @category    BS
 * @package     BS_CourseCost
 * @author Bui Phong
 */
class BS_CourseCost_Model_Coursecost extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_coursecost_coursecost';
    const CACHE_TAG = 'bs_coursecost_coursecost';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_coursecost_coursecost';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'coursecost';

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
        $this->_init('bs_coursecost/coursecost');
    }

    /**
     * before save course cost
     *
     * @access protected
     * @return BS_CourseCost_Model_Coursecost
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
     * save course cost relation
     *
     * @access public
     * @return BS_CourseCost_Model_Coursecost
     * @author Bui Phong
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
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
     * Retrieve parent 
     *
     * @access public
     * @return null|BS_CourseCost_Model_Costitem
     * @author Bui Phong
     */
    public function getParentCostitem()
    {
        if (!$this->hasData('_parent_costitem')) {
            if (!$this->getCostitemId()) {
                return null;
            } else {
                $costitem = Mage::getModel('bs_coursecost/costitem')
                    ->load($this->getCostitemId());
                if ($costitem->getId()) {
                    $this->setData('_parent_costitem', $costitem);
                } else {
                    $this->setData('_parent_costitem', null);
                }
            }
        }
        return $this->getData('_parent_costitem');
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
