<?php
/**
 * BS_Tasktraining extension
 * 
 * @category       BS
 * @package        BS_Tasktraining
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor tab on category edit form
 *
 * @category    BS
 * @package     BS_Tasktraining
 * @author Bui Phong
 */
class BS_Tasktraining_Block_Adminhtml_Catalog_Category_Tab_Taskinstructor extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('catalog_category_taskinstructor');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
        if ($this->getCategory()->getId()) {
            $this->setDefaultFilter(array('in_taskinstructors'=>1));
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
     * @return BS_Tasktraining_Block_Adminhtml_Catalog_Category_Tab_Taskinstructor
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_tasktraining/taskinstructor_collection');
        if ($this->getCategory()->getId()) {
            $constraint = 'related.category_id='.$this->getCategory()->getId();
        } else {
            $constraint = 'related.category_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('bs_tasktraining/taskinstructor_category')),
            'related.taskinstructor_id=main_table.entity_id AND '.$constraint,
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
     * @return BS_Tasktraining_Block_Adminhtml_Catalog_Category_Tab_Taskinstructor
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_taskinstructors',
            array(
                'header_css_class'  => 'a-center',
                'type'   => 'checkbox',
                'name'   => 'in_taskinstructors',
                'values' => $this->_getSelectedTaskinstructors(),
                'align'  => 'center',
                'index'  => 'entity_id'
            )
        );
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_tasktraining')->__('Id'),
                'type'   => 'number',
                'align'  => 'left',
                'index'  => 'entity_id',
            )
        );
        $this->addColumn(
            'name',
            array(
                'header' => Mage::helper('bs_tasktraining')->__('Name'),
                'align'  => 'left',
                'index'  => 'name',
                'renderer' => 'bs_tasktraining/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getId'
                ),
                'base_link' => 'adminhtml/tasktraining_taskinstructor/edit',
            )
        );
        $this->addColumn(
            'position',
            array(
                'header'         => Mage::helper('bs_tasktraining')->__('Position'),
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
     * Retrieve selected taskinstructors
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    protected function _getSelectedTaskinstructors()
    {
        $taskinstructors = $this->getCategoryTaskinstructors();
        if (!is_array($taskinstructors)) {
            $taskinstructors = array_keys($this->getSelectedTaskinstructors());
        }
        return $taskinstructors;
    }

    /**
     * Retrieve selected taskinstructors
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    public function getSelectedTaskinstructors()
    {
        $taskinstructors = array();
        //used helper here in order not to override the category model
        $selected = Mage::helper('bs_tasktraining/category')->getSelectedTaskinstructors(Mage::registry('current_category'));
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $taskinstructor) {
            $taskinstructors[$taskinstructor->getId()] = array('position' => $taskinstructor->getPosition());
        }
        return $taskinstructors;
    }

    /**
     * get row url
     *
     * @access public
     * @param BS_Tasktraining_Model_Taskinstructor
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
            'adminhtml/tasktraining_taskinstructor_catalog_category/taskinstructorsgrid',
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
     * @return BS_Tasktraining_Block_Adminhtml_Catalog_Category_Tab_Taskinstructor
     * @author Bui Phong
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_taskinstructors') {
            $taskinstructorIds = $this->_getSelectedTaskinstructors();
            if (empty($taskinstructorIds)) {
                $taskinstructorIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$taskinstructorIds));
            } else {
                if ($taskinstructorIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$taskinstructorIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
