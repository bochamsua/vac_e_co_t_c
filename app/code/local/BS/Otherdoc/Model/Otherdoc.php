<?php
/**
 * BS_Otherdoc extension
 * 
 * @category       BS
 * @package        BS_Otherdoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Other\'s Course Document model
 *
 * @category    BS
 * @package     BS_Otherdoc
 * @author Bui Phong
 */
class BS_Otherdoc_Model_Otherdoc extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_otherdoc_otherdoc';
    const CACHE_TAG = 'bs_otherdoc_otherdoc';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_otherdoc_otherdoc';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'otherdoc';
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
        $this->_init('bs_otherdoc/otherdoc');
    }

    /**
     * before save other\'s course document
     *
     * @access protected
     * @return BS_Otherdoc_Model_Otherdoc
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
     * save other\'s course document relation
     *
     * @access public
     * @return BS_Otherdoc_Model_Otherdoc
     * @author Bui Phong
     */
    protected function _afterSave()
    {
        $this->getProductInstance()->saveOtherdocRelation($this);
        return parent::_afterSave();
    }

    /**
     * get product relation model
     *
     * @access public
     * @return BS_Otherdoc_Model_Otherdoc_Product
     * @author Bui Phong
     */
    public function getProductInstance()
    {
        if (!$this->_productInstance) {
            $this->_productInstance = Mage::getSingleton('bs_otherdoc/otherdoc_product');
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
     * @return BS_Otherdoc_Resource_Otherdoc_Product_Collection
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
