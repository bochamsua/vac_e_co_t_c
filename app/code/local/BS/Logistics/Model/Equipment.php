<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Equipment model
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Model_Equipment extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_logistics_equipment';
    const CACHE_TAG = 'bs_logistics_equipment';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_logistics_equipment';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'equipment';

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
        $this->_init('bs_logistics/equipment');
    }

    /**
     * before save equipment
     *
     * @access protected
     * @return BS_Logistics_Model_Equipment
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
     * save equipment relation
     *
     * @access public
     * @return BS_Logistics_Model_Equipment
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
     * @return null|BS_Logistics_Model_Classroom
     * @author Bui Phong
     */
    public function getParentClassroom()
    {
        if (!$this->hasData('_parent_classroom')) {
            if (!$this->getClassroomId()) {
                return null;
            } else {
                $classroom = Mage::getModel('bs_logistics/classroom')
                    ->load($this->getClassroomId());
                if ($classroom->getId()) {
                    $this->setData('_parent_classroom', $classroom);
                } else {
                    $this->setData('_parent_classroom', null);
                }
            }
        }
        return $this->getData('_parent_classroom');
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
     * @return null|BS_Logistics_Model_Otherroom
     * @author Bui Phong
     */
    public function getParentOtherroom()
    {
        if (!$this->hasData('_parent_otherroom')) {
            if (!$this->getOtherroomId()) {
                return null;
            } else {
                $otherroom = Mage::getModel('bs_logistics/otherroom')
                    ->load($this->getOtherroomId());
                if ($otherroom->getId()) {
                    $this->setData('_parent_otherroom', $otherroom);
                } else {
                    $this->setData('_parent_otherroom', null);
                }
            }
        }
        return $this->getData('_parent_otherroom');
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

