<?php
/**
 * BS_Car extension
 * 
 * @category       BS
 * @package        BS_Car
 * @copyright      Copyright (c) 2016
 */
/**
 * QA Car model
 *
 * @category    BS
 * @package     BS_Car
 * @author Bui Phong
 */
class BS_Car_Model_Qacar extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_car_qacar';
    const CACHE_TAG = 'bs_car_qacar';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_car_qacar';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'qacar';

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
        $this->_init('bs_car/qacar');
    }

    /**
     * before save qa car
     *
     * @access protected
     * @return BS_Car_Model_Qacar
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
     * save qa car relation
     *
     * @access public
     * @return BS_Car_Model_Qacar
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
     * Retrieve  collection
     *
     * @access public
     * @return BS_Car_Model_Cardoc_Collection
     * @author Bui Phong
     */
    public function getSelectedCardocsCollection()
    {
        if (!$this->hasData('_cardoc_collection')) {
            if (!$this->getId()) {
                return new Varien_Data_Collection();
            } else {
                $collection = Mage::getResourceModel('bs_car/cardoc_collection')
                    ->addFieldToFilter('qacar_id', $this->getId());
                $this->setData('_cardoc_collection', $collection);
            }
        }
        return $this->getData('_cardoc_collection');
    }


}
