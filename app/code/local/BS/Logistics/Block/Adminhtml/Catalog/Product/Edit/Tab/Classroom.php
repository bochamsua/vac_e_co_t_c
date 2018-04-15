<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Classroom/Examroom tab on product edit form
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Catalog_Product_Edit_Tab_Classroom extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set grid params
     *
     * @access public
     * @author Bui Phong
     */

    public function __construct()
    {
        parent::__construct();
        $this->setId('classroom_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        if ($this->getProduct()->getId()) {
            $this->setDefaultFilter(array('in_classrooms'=>1));
        }
    }

    /**
     * prepare the classroom collection
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Catalog_Product_Edit_Tab_Classroom
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_logistics/classroom_collection');
        if ($this->getProduct()->getId()) {
            $constraint = 'related.product_id='.$this->getProduct()->getId();
        } else {
            $constraint = 'related.product_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('bs_logistics/classroom_product')),
            'related.classroom_id=main_table.entity_id AND '.$constraint,
            array('position')
        );
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * prepare mass action grid
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Catalog_Product_Edit_Tab_Classroom
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        return $this;
    }

    /**
     * prepare the grid columns
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Catalog_Product_Edit_Tab_Classroom
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_classrooms',
            array(
                'header_css_class'  => 'a-center',
                'type'  => 'checkbox',
                'name'  => 'in_classrooms',
                'values'=> $this->_getSelectedClassrooms(),
                'align' => 'center',
                'index' => 'entity_id'
            )
        );
        $this->addColumn(
            'classroom_code',
            array(
                'header' => Mage::helper('bs_logistics')->__('Code'),
                'align'  => 'left',
                'index'  => 'classroom_code',
                'renderer' => 'bs_logistics/adminhtml_helper_column_renderer_room',

            )
        );
        $this->addColumn(
            'classroom_name',
            array(
                'header' => Mage::helper('bs_logistics')->__('Name'),
                'align'  => 'left',
                'index'  => 'classroom_name',
                'renderer' => 'bs_logistics/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getId'
                ),
                'base_link' => 'adminhtml/logistics_classroom/edit',
            )
        );

        $this->addColumn(
            'position',
            array(
                'header'         => Mage::helper('bs_logistics')->__('Position'),
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
     * Retrieve selected classrooms
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    protected function _getSelectedClassrooms()
    {
        $classrooms = $this->getProductClassrooms();
        if (!is_array($classrooms)) {
            $classrooms = array_keys($this->getSelectedClassrooms());
        }
        return $classrooms;
    }

    /**
     * Retrieve selected classrooms
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    public function getSelectedClassrooms()
    {
        $classrooms = array();
        //used helper here in order not to override the product model
        $selected = Mage::helper('bs_logistics/product')->getSelectedClassrooms(Mage::registry('current_product'));
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $classroom) {
            $classrooms[$classroom->getId()] = array('position' => $classroom->getPosition());
        }
        return $classrooms;
    }

    /**
     * get row url
     *
     * @access public
     * @param BS_Logistics_Model_Classroom
     * @return string
     * @author Bui Phong
     */
    public function getRowUrl($item)
    {
        return $this->getUrl(
            '*/register_schedule/new',
            array(
                'product_id'=>$this->getProduct()->getId(),
                'room_id'=>$item->getId()
            )
        );
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
            '*/*/classroomsGrid',
            array(
                'id'=>$this->getProduct()->getId()
            )
        );
    }

    /**
     * get the current product
     *
     * @access public
     * @return Mage_Catalog_Model_Product
     * @author Bui Phong
     */
    public function getProduct()
    {
        return Mage::registry('current_product');
    }

    /**
     * Add filter
     *
     * @access protected
     * @param object $column
     * @return BS_Logistics_Block_Adminhtml_Catalog_Product_Edit_Tab_Classroom
     * @author Bui Phong
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_classrooms') {
            $classroomIds = $this->_getSelectedClassrooms();
            if (empty($classroomIds)) {
                $classroomIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$classroomIds));
            } else {
                if ($classroomIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$classroomIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
