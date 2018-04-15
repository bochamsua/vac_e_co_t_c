<?php
/**
 * BS_CourseDoc extension
 * 
 * @category       BS
 * @package        BS_CourseDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Course Document model
 *
 * @category    BS
 * @package     BS_CourseDoc
 * @author      Bui Phong
 */
class BS_CourseDoc_Model_Coursedoc extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_coursedoc_coursedoc';
    const CACHE_TAG = 'bs_coursedoc_coursedoc';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_coursedoc_coursedoc';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'coursedoc';
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
        $this->_init('bs_coursedoc/coursedoc');
    }

    /**
     * before save course doc
     *
     * @access protected
     * @return BS_CourseDoc_Model_Coursedoc
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
     * save course doc relation
     *
     * @access public
     * @return BS_CourseDoc_Model_Coursedoc
     * @author Bui Phong
     */
    protected function _afterSave()
    {
        $this->getProductInstance()->saveCoursedocRelation($this);
        return parent::_afterSave();
    }

    /**
     * get product relation model
     *
     * @access public
     * @return BS_CourseDoc_Model_Coursedoc_Product
     * @author Bui Phong
     */
    public function getProductInstance()
    {
        if (!$this->_productInstance) {
            $this->_productInstance = Mage::getSingleton('bs_coursedoc/coursedoc_product');
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
     * @return BS_CourseDoc_Resource_Coursedoc_Product_Collection
     * @author Bui Phong
     */
    public function getSelectedProductsCollection()
    {
        $collection = $this->getProductInstance()->getProductCollection($this);
        return $collection;
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
