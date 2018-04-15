<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Other room model
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Model_Otherroom extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_logistics_otherroom';
    const CACHE_TAG = 'bs_logistics_otherroom';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_logistics_otherroom';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'otherroom';

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
        $this->_init('bs_logistics/otherroom');
    }

    /**
     * before save other room
     *
     * @access protected
     * @return BS_Logistics_Model_Otherroom
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
     * save other room relation
     *
     * @access public
     * @return BS_Logistics_Model_Otherroom
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
     * @return BS_Logistics_Model_Equipment_Collection
     * @author Bui Phong
     */
    public function getSelectedEquipmentsCollection()
    {
        if (!$this->hasData('_equipment_collection')) {
            if (!$this->getId()) {
                return new Varien_Data_Collection();
            } else {
                $collection = Mage::getResourceModel('bs_logistics/equipment_collection')
                    ->addFieldToFilter('otherroom_id', $this->getId());

                $this->setData('_equipment_collection', $collection);
            }
        }
        return $this->getData('_equipment_collection');
    }

    /**
     * Retrieve  collection
     *
     * @access public
     * @return BS_Logistics_Model_Filecabinet_Collection
     * @author Bui Phong
     */
    public function getSelectedFilecabinetsCollection()
    {
        if (!$this->hasData('_filecabinet_collection')) {
            if (!$this->getId()) {
                return new Varien_Data_Collection();
            } else {
                $collection = Mage::getResourceModel('bs_logistics/filecabinet_collection')
                        ->addFieldToFilter('otherroom_id', $this->getId());
                $this->setData('_filecabinet_collection', $collection);
            }
        }
        return $this->getData('_filecabinet_collection');
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
