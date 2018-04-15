<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor collection resource model
 *
 * @category    BS
 * @package     BS_Instructor
 * @author      Bui Phong
 */
class BS_Instructor_Model_Resource_Instructor_Collection extends Mage_Catalog_Model_Resource_Collection_Abstract
{
    protected $_joinedFields = array();

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('bs_instructor/instructor');
    }

    /**
     * get instructors as array
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @param array $additional
     * @return array
     * @author Bui Phong
     */
    protected function _toOptionArray($valueField='entity_id', $labelField='iname', $additional=array())
    {
        $this->addAttributeToSelect('iname');
        return parent::_toOptionArray($valueField, $labelField, $additional);
    }

    /**
     * get options hash
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @return array
     * @author Bui Phong
     */
    protected function _toOptionHash($valueField='entity_id', $labelField='iname')
    {
        $this->addAttributeToSelect('iname');
        return parent::_toOptionHash($valueField, $labelField);
    }


    public function toFullOptionArray(){
        $this->addAttributeToSelect('*');
        return $this->_toFullOptionArray('entity_id','iname');
    }

    protected function _toFullOptionArray($valueField='id', $labelField='name', $additional=array())
    {
        $res = array();
        $additional['value'] = $valueField;
        $additional['label'] = $labelField;

        foreach ($this as $item) {
            foreach ($additional as $code => $field) {
                $add = '';
                if($item->getData('ivaecoid') != ''){
                    $add .= ' - '.$item->getData('ivaecoid');
                }
                if($field == 'entity_id'){
                    $data[$code] = $item->getData($field);
                }else {
                    $data[$code] = $item->getData($field).$add;
                }

            }
            $res[] = $data;
        }
        return $res;
    }

    /**
     * add the product filter to collection
     *
     * @access public
     * @param mixed (Mage_Catalog_Model_Product|int) $product
     * @return BS_Instructor_Model_Resource_Instructor_Collection
     * @author Bui Phong
     */
    public function addProductFilter($product)
    {
        if ($product instanceof Mage_Catalog_Model_Product) {
            $product = $product->getId();
        }
        if (!isset($this->_joinedFields['product'])) {
            $this->getSelect()->join(
                array('related_product' => $this->getTable('bs_instructor/instructor_product')),
                'related_product.instructor_id = e.entity_id',
                array('position')
            );
            $this->getSelect()->where('related_product.product_id = ?', $product);
            $this->_joinedFields['product'] = true;
        }
        return $this;
    }

    public function addCurriculumFilter($curriculum)
    {
        if ($curriculum instanceof BS_Traininglist_Model_Curriculum) {
            $curriculum = $curriculum->getId();
        }
        if (!isset($this->_joinedFields['curriculum'])) {
            $this->getSelect()->join(
                array('related_curriculum' => $this->getTable('bs_instructor/instructor_curriculum')),
                'related_curriculum.instructor_id = e.entity_id',
                array('position')
            );
            $this->getSelect()->where('related_curriculum.curriculum_id = ?', $curriculum);
            $this->_joinedFields['curriculum'] = true;
        }
        return $this;
    }

    /**
     * add the category filter to collection
     *
     * @access public
     * @param mixed (Mage_Catalog_Model_Category|int) $category
     * @return BS_Instructor_Model_Resource_Instructor_Collection
     * @author Bui Phong
     */
    public function addCategoryFilter($category)
    {
        if ($category instanceof Mage_Catalog_Model_Category) {
            $category = $category->getId();
        }
        if (!isset($this->_joinedFields['category'])) {
            $this->getSelect()->join(
                array('related_category' => $this->getTable('bs_instructor/instructor_category')),
                'related_category.instructor_id = e.entity_id',
                array('position')
            );
            $this->getSelect()->where('related_category.category_id = ?', $category);
            $this->_joinedFields['category'] = true;
        }
        return $this;
    }

    /**
     * Get SQL for get record count.
     * Extra GROUP BY strip added.
     *
     * @access public
     * @return Varien_Db_Select
     * @author Bui Phong
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(Zend_Db_Select::GROUP);
        return $countSelect;
    }
}
