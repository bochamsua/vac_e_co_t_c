<?php
/**
 * BS_Register extension
 * 
 * @category       BS
 * @package        BS_Register
 * @copyright      Copyright (c) 2015
 */
/**
 * Course Schedule model
 *
 * @category    BS
 * @package     BS_Register
 * @author Bui Phong
 */
class BS_Register_Model_Schedule extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_register_schedule';
    const CACHE_TAG = 'bs_register_schedule';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_register_schedule';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'schedule';

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
        $this->_init('bs_register/schedule');
    }

    /**
     * Retrieve parent
     *
     * @access public
     * @return null|Mage_Catalog_Block_Product
     * @author Bui Phong
     */
    public function getParentProduct()
    {
        if (!$this->hasData('_parent_product')) {
            if (!$this->getProductId()) {
                return null;
            } else {
                $product = Mage::getModel('catalog/product')->setStoreId(Mage::app()->getStore()->getId())
                    ->load($this->getProductId());
                if ($product->getId()) {
                    $this->setData('_parent_product', $product);
                } else {
                    $this->setData('_parent_product', null);
                }
            }
        }
        return $this->getData('_parent_product');
    }

    /**
     * Retrieve parent
     *
     * @access public
     * @return null|BS_Moka_Model_Basa
     * @author Bui Phong
     */
    public function getParentInstructor()
    {
        if (!$this->hasData('_parent_instructor')) {
            if (!$this->getInstructorId()) {
                return null;
            } else {
                $instructor = Mage::getModel('bs_instructor/instructor')->setStoreId(Mage::app()->getStore()->getId())
                    ->load($this->getInstructorId());
                if ($instructor->getId()) {
                    $this->setData('_parent_instructor', $instructor);
                } else {
                    $this->setData('_parent_instructor', null);
                }
            }
        }
        return $this->getData('_parent_instructor');
    }

    /**
     * Retrieve parent
     *
     * @access public
     * @return null|BS_Moka_Model_Basa
     * @author Bui Phong
     */
    public function getParentSubject()
    {
        if (!$this->hasData('_parent_subject')) {
            if (!$this->getSubjectId()) {
                return null;
            } else {
                $subject = Mage::getModel('bs_subject/subject')->load($this->getSubjectId());
                if ($subject->getId()) {
                    $this->setData('_parent_subject', $subject);
                } else {
                    $this->setData('_parent_subject', null);
                }
            }
        }
        return $this->getData('_parent_subject');
    }

    /**
     * before save course schedule
     *
     * @access protected
     * @return BS_Register_Model_Schedule
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
     * save course schedule relation
     *
     * @access public
     * @return BS_Register_Model_Schedule
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

    /**
     * get Start Time
     *
     * @access public
     * @return array
     * @author Bui Phong
     */
    public function getScheduleStartTime()
    {
        if (!$this->getData('schedule_start_time')) {
            return explode(',', $this->getData('schedule_start_time'));
        }
        return $this->getData('schedule_start_time');
    }
    /**
     * get Finish Time
     *
     * @access public
     * @return array
     * @author Bui Phong
     */
    public function getScheduleFinishTime()
    {
        if (!$this->getData('schedule_finish_time')) {
            return explode(',', $this->getData('schedule_finish_time'));
        }
        return $this->getData('schedule_finish_time');
    }

    /**
     * get Subject
     *
     * @access public
     * @return array
     * @author Bui Phong
     */
    public function getScheduleSubjects()
    {
        if (!$this->getData('schedule_subjects')) {
            return explode(',', $this->getData('schedule_subjects'));
        }
        return $this->getData('schedule_subjects');
    }

    public function getScheduleUrl()
    {

        return Mage::getUrl('bs_register/schedule/view', array('id'=>$this->getId()));
    }



}
