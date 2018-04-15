<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Workshop model
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Model_Workshop extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_logistics_workshop';
    const CACHE_TAG = 'bs_logistics_workshop';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_logistics_workshop';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'workshop';
    protected $_productInstance = null;

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
        $this->_init('bs_logistics/workshop');
    }

    /**
     * before save workshop
     *
     * @access protected
     * @return BS_Logistics_Model_Workshop
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
     * save workshop relation
     *
     * @access public
     * @return BS_Logistics_Model_Workshop
     * @author Bui Phong
     */
    protected function _afterSave()
    {
        $this->getProductInstance()->saveWorkshopRelation($this);
        return parent::_afterSave();
    }

    /**
     * get product relation model
     *
     * @access public
     * @return BS_Logistics_Model_Workshop_Product
     * @author Bui Phong
     */
    public function getProductInstance()
    {
        if (!$this->_productInstance) {
            $this->_productInstance = Mage::getSingleton('bs_logistics/workshop_product');
        }
        return $this->_productInstance;
    }

    /**
     * get selected products array
     *
     * @access public
     * @return array
     * @author Bui Phong
     */
    public function getSelectedProducts()
    {
        if (!$this->hasSelectedProducts()) {
            $products = array();
            foreach ($this->getSelectedProductsCollection() as $product) {
                $products[] = $product;
            }
            $this->setSelectedProducts($products);
        }
        return $this->getData('selected_products');
    }

    /**
     * Retrieve collection selected products
     *
     * @access public
     * @return BS_Logistics_Resource_Workshop_Product_Collection
     * @author Bui Phong
     */
    public function getSelectedProductsCollection()
    {
        $collection = $this->getProductInstance()->getProductCollection($this);
        return $collection;
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
                    ->addFieldToFilter('workshop_id', $this->getId());

                $this->setData('_equipment_collection', $collection);
            }
        }
        return $this->getData('_equipment_collection');
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
                    ->addFieldToFilter('workshop_id', $this->getId());
                $this->setData('_wtool_collection', $collection);
            }
        }
        return $this->getData('_wtool_collection');
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
                    ->addFieldToFilter('workshop_id', $this->getId());
                $this->setData('_wgroupitem_collection', $collection);
            }
        }
        return $this->getData('_wgroupitem_collection');
    }



}
