<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor model
 *
 * @category    BS
 * @package     BS_Instructor
 * @author      Bui Phong
 */
class BS_Instructor_Model_Instructor extends Mage_Catalog_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_instructor_instructor';
    const CACHE_TAG = 'bs_instructor_instructor';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_instructor_instructor';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'instructor';
    protected $_productInstance = null;
    protected $_categoryInstance = null;
    protected $_curriculumInstance = null;

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
        $this->_init('bs_instructor/instructor');
    }

    /**
     * before save instructor
     *
     * @access protected
     * @return BS_Instructor_Model_Instructor
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
     * save instructor relation
     *
     * @access public
     * @return BS_Instructor_Model_Instructor
     * @author Bui Phong
     */
    protected function _afterSave()
    {
        $this->getProductInstance()->saveInstructorRelation($this);
        $this->getCategoryInstance()->saveInstructorRelation($this);
        $this->getCurriculumInstance()->saveInstructorRelation($this);
        return parent::_afterSave();
    }

    /**
     * get product relation model
     *
     * @access public
     * @return BS_Instructor_Model_Instructor_Product
     * @author Bui Phong
     */
    public function getProductInstance()
    {
        if (!$this->_productInstance) {
            $this->_productInstance = Mage::getSingleton('bs_instructor/instructor_product');
        }
        return $this->_productInstance;
    }

    public function getCurriculumInstance()
    {
        if (!$this->_curriculumInstance) {
            $this->_curriculumInstance = Mage::getSingleton('bs_instructor/instructor_curriculum');
        }
        return $this->_curriculumInstance;
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

    public function getSelectedCurriculums()
    {
        if (!$this->hasSelectedCurriculums()) {
            $curriculums = array();
            foreach ($this->getSelectedCurriculumsCollection() as $curriculum) {
                $curriculums[] = $curriculum;
            }
            $this->setSelectedCurriculums($curriculums);
        }
        return $this->getData('selected_curriculums');
    }

    /**
     * Retrieve collection selected products
     *
     * @access public
     * @return BS_Instructor_Resource_Instructor_Product_Collection
     * @author Bui Phong
     */
    public function getSelectedCurriculumsCollection()
    {
        $collection = $this->getCurriculumInstance()->getCurriculumCollection($this);
        return $collection;
    }

    public function getSelectedProductsCollection()
    {
        $collection = $this->getProductInstance()->getProductCollection($this);
        return $collection;
    }

    /**
     * get category relation model
     *
     * @access public
     * @return BS_Instructor_Model_Instructor_Category
     * @author Bui Phong
     */
    public function getCategoryInstance()
    {
        if (!$this->_categoryInstance) {
            $this->_categoryInstance = Mage::getSingleton('bs_instructor/instructor_category');
        }
        return $this->_categoryInstance;
    }

    /**
     * get selected categories array
     *
     * @access public
     * @return array
     * @author Bui Phong
     */
    public function getSelectedCategories()
    {
        if (!$this->hasSelectedCategories()) {
            $categories = array();
            foreach ($this->getSelectedCategoriesCollection() as $category) {
                $categories[] = $category;
            }
            $this->setSelectedCategories($categories);
        }
        return $this->getData('selected_categories');
    }

    /**
     * Retrieve collection selected categories
     *
     * @access public
     * @return BS_Instructor_Resource_Instructor_Category_Collection
     * @author Bui Phong
     */
    public function getSelectedCategoriesCollection()
    {
        $collection = $this->getCategoryInstance()->getCategoryCollection($this);
        return $collection;
    }

    /**
     * Retrieve default attribute set id
     *
     * @access public
     * @return int
     * @author Bui Phong
     */
    public function getDefaultAttributeSetId()
    {
        return $this->getResource()->getEntityType()->getDefaultAttributeSetId();
    }

    /**
     * get attribute text value
     *
     * @access public
     * @param $attributeCode
     * @return string
     * @author Bui Phong
     */
    public function getAttributeText($attributeCode)
    {
        $text = $this->getResource()
            ->getAttribute($attributeCode)
            ->getSource()
            ->getOptionText($this->getData($attributeCode));
        if (is_array($text)) {
            return implode(', ', $text);
        }
        return $text;
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
