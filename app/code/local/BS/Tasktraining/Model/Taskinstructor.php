<?php
/**
 * BS_Tasktraining extension
 * 
 * @category       BS
 * @package        BS_Tasktraining
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor model
 *
 * @category    BS
 * @package     BS_Tasktraining
 * @author Bui Phong
 */
class BS_Tasktraining_Model_Taskinstructor extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_tasktraining_taskinstructor';
    const CACHE_TAG = 'bs_tasktraining_taskinstructor';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_tasktraining_taskinstructor';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'taskinstructor';
    protected $_categoryInstance = null;

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
        $this->_init('bs_tasktraining/taskinstructor');
    }

    /**
     * before save instructor
     *
     * @access protected
     * @return BS_Tasktraining_Model_Taskinstructor
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
     * @return BS_Tasktraining_Model_Taskinstructor
     * @author Bui Phong
     */
    protected function _afterSave()
    {
        $this->getCategoryInstance()->saveTaskinstructorRelation($this);
        return parent::_afterSave();
    }

    /**
     * get category relation model
     *
     * @access public
     * @return BS_Tasktraining_Model_Taskinstructor_Category
     * @author Bui Phong
     */
    public function getCategoryInstance()
    {
        if (!$this->_categoryInstance) {
            $this->_categoryInstance = Mage::getSingleton('bs_tasktraining/taskinstructor_category');
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
     * @return BS_Tasktraining_Resource_Taskinstructor_Category_Collection
     * @author Bui Phong
     */
    public function getSelectedCategoriesCollection()
    {
        $collection = $this->getCategoryInstance()->getCategoryCollection($this);
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
