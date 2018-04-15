<?php
/**
 * BS_Register extension
 * 
 * @category       BS
 * @package        BS_Register
 * @copyright      Copyright (c) 2015
 */
/**
 * Attendance model
 *
 * @category    BS
 * @package     BS_Register
 * @author Bui Phong
 */
class BS_Register_Model_Attendance extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_register_attendance';
    const CACHE_TAG = 'bs_register_attendance';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_register_attendance';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'attendance';

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
        $this->_init('bs_register/attendance');
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
    public function getParentTrainee()
    {
        if (!$this->hasData('_parent_trainee')) {
            if (!$this->getTraineeId()) {
                return null;
            } else {
                $trainee = Mage::getModel('bs_trainee/trainee')->setStoreId(Mage::app()->getStore()->getId())
                    ->load($this->getTraineeId());
                if ($trainee->getId()) {
                    $this->setData('_parent_trainee', $trainee);
                } else {
                    $this->setData('_parent_trainee', null);
                }
            }
        }
        return $this->getData('_parent_trainee');
    }



    /**
     * before save attendance
     *
     * @access protected
     * @return BS_Register_Model_Attendance
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
     * save attendance relation
     *
     * @access public
     * @return BS_Register_Model_Attendance
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
