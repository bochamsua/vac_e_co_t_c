<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor tab on category edit form
 *
 * @category    BS
 * @package     BS_Instructor
 * @author      Bui Phong
 */
class BS_Instructor_Block_Adminhtml_Catalog_Category_Tab_Instructor extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('catalog_category_instructor');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
        if ($this->getCategory()->getId()) {
            $this->setDefaultFilter(array('in_instructors'=>1));
        }
    }

    /**
     * get current category
     *
     * @access public
     * @return Mage_Catalog_Model_Category|null
     * @author Bui Phong
     */
    public function getCategory()
    {
        return Mage::registry('current_category');
    }

    /**
     * prepare the collection
     *
     * @access protected
     * @return BS_Instructor_Block_Adminhtml_Catalog_Category_Tab_Instructor
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_instructor/instructor_collection')->addAttributeToSelect('iname');
        if ($this->getCategory()->getId()) {
            $constraint = 'related.category_id='.$this->getCategory()->getId();
        } else {
            $constraint = 'related.category_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('bs_instructor/instructor_category')),
            'related.instructor_id=e.entity_id AND '.$constraint,
            array('position')
        );
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * Prepare the columns
     *
     * @access protected
     * @return BS_Instructor_Block_Adminhtml_Catalog_Category_Tab_Instructor
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_instructors',
            array(
                'header_css_class'  => 'a-center',
                'type'   => 'checkbox',
                'name'   => 'in_instructors',
                'values' => $this->_getSelectedInstructors(),
                'align'  => 'center',
                'index'  => 'entity_id'
            )
        );
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_instructor')->__('Id'),
                'type'   => 'number',
                'align'  => 'left',
                'index'  => 'entity_id',
            )
        );
        $this->addColumn(
            'iname',
            array(
                'header' => Mage::helper('bs_instructor')->__('Instructor Name'),
                'align'  => 'left',
                'index'  => 'iname',
                'renderer' => 'bs_instructor/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getId'
                ),
                'base_link' => 'adminhtml/instructor_instructor/edit',
            )
        );
        $this->addColumn(
            'position',
            array(
                'header'         => Mage::helper('bs_instructor')->__('Position'),
                'name'           => 'position',
                'width'          => 60,
                'type'           => 'number',
                'validate_class' => 'validate-number',
                'index'          => 'position',
                'editable'       => true,
            )
        );
        return parent::_prepareColumns();
    }

    /**
     * Retrieve selected instructors
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    protected function _getSelectedInstructors()
    {
        $instructors = $this->getCategoryInstructors();
        if (!is_array($instructors)) {
            $instructors = array_keys($this->getSelectedInstructors());
        }
        return $instructors;
    }

    /**
     * Retrieve selected instructors
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    public function getSelectedInstructors()
    {
        $instructors = array();
        //used helper here in order not to override the category model
        $selected = Mage::helper('bs_instructor/category')->getSelectedInstructors(Mage::registry('current_category'));
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $instructor) {
            $instructors[$instructor->getId()] = array('position' => $instructor->getPosition());
        }
        return $instructors;
    }

    /**
     * get row url
     *
     * @access public
     * @param BS_Instructor_Model_Instructor
     * @return string
     * @author Bui Phong
     */
    public function getRowUrl($item)
    {
        return '#';
    }

    /**
     * get grid url
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getGridUrl()
    {
        return $this->getUrl(
            'adminhtml/instructor_instructor_catalog_category/instructorsgrid',
            array(
                'id'=>$this->getCategory()->getId()
            )
        );
    }

    /**
     * Add filter
     *
     * @access protected
     * @param object $column
     * @return BS_Instructor_Block_Adminhtml_Catalog_Category_Tab_Instructor
     * @author Bui Phong
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_instructors') {
            $instructorIds = $this->_getSelectedInstructors();
            if (empty($instructorIds)) {
                $instructorIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$instructorIds));
            } else {
                if ($instructorIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$instructorIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
