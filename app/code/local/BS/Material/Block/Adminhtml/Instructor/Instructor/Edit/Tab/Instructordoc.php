<?php
/**
 * BS_Material extension
 * 
 * 
 * @category       BS
 * @package        BS_Material
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Document tab on instructor edit form
 *
 * @category    BS
 * @package     BS_Material
 * @author      Bui Phong
 */
class BS_Material_Block_Adminhtml_Instructor_Instructor_Edit_Tab_Instructordoc extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('instructordoc_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        if ($this->getInstructor()->getId()) {
            $this->setDefaultFilter(array('in_instructordocs'=>1));
        }
    }

    protected function _prepareLayout(){
        $this->setChild('add_instructordoc_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('Add New Document'),
                    'onclick'   => 'window.open(\''.$this->getUrl('*/material_instructordoc/new', array('_current'=>false, 'instructor_id'=>$this->getInstructor()->getId(),'popup'=>true)).'\',\'\',\'width=1000,height=700,resizable=1,scrollbars=1\')'
                ))
        );

        parent::_prepareLayout();
    }


    public function getMainButtonsHtml()
    {
        $html = '';
        if($this->getFilterVisibility()){
            $html.= $this->getChildHtml('add_instructordoc_button');
            $html.= $this->getResetFilterButtonHtml();
            $html.= $this->getSearchButtonHtml();
        }
        return $html;

    }
    /**
     * prepare the instructordoc collection
     *
     * @access protected
     * @return BS_Material_Block_Adminhtml_Instructor_Instructor_Edit_Tab_Instructordoc
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_material/instructordoc_collection');
        if ($this->getInstructor()->getId()) {
            $constraint = 'related.instructor_id='.$this->getInstructor()->getId();
        } else {
            $constraint = 'related.instructor_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('bs_material/instructordoc_instructor')),
            'related.instructordoc_id=main_table.entity_id AND '.$constraint,
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
     * @return BS_Material_Block_Adminhtml_Instructor_Instructor_Edit_Tab_Instructordoc
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
     * @return BS_Material_Block_Adminhtml_Instructor_Instructor_Edit_Tab_Instructordoc
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_instructordocs',
            array(
                'header_css_class'  => 'a-center',
                'type'  => 'checkbox',
                'name'  => 'in_instructordocs',
                'values'=> $this->_getSelectedInstructordocs(),
                'align' => 'center',
                'index' => 'entity_id'
            )
        );
        $this->addColumn(
            'idoc_name',
            array(
                'header' => Mage::helper('bs_material')->__('Document Name'),
                'align'  => 'left',
                'index'  => 'idoc_name',
                'renderer' => 'bs_material/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getId'
                ),
                'base_link' => 'adminhtml/material_instructordoc/edit',
            )
        );

        $this->addColumn(
            'idoc_type',
            array(
                'header' => Mage::helper('bs_material')->__('Document Type'),
                'index'  => 'idoc_type',
                'type'  => 'options',
                'options' => Mage::helper('bs_material')->convertOptions(
                    Mage::getModel('bs_material/instructordoc_attribute_source_idoctype')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'idoc_date',
            array(
                'header' => Mage::helper('bs_material')->__('Approved/Revised Date'),
                'index'  => 'idoc_date',
                'type'  => 'date',
            )
        );
        $this->addColumn(
            'download',
            array(
                'header'  =>  Mage::helper('bs_material')->__('View/Download'),
                'type'      =>'text',
                'renderer' => 'bs_material/adminhtml_helper_column_renderer_download',

                'filter'    => false,
                'sortable'  => false,
            )
        );
        $this->addColumn(
            'position',
            array(
                'header'         => Mage::helper('bs_material')->__('Position'),
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
     * Retrieve selected instructordocs
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    protected function _getSelectedInstructordocs()
    {
        $instructordocs = $this->getInstructorInstructordocs();
        if (!is_array($instructordocs)) {
            $instructordocs = array_keys($this->getSelectedInstructordocs());
        }
        return $instructordocs;
    }

    /**
     * Retrieve selected instructordocs
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    public function getSelectedInstructordocs()
    {
        $instructordocs = array();
        //used helper here in order not to override the instructor model
        $selected = Mage::helper('bs_material/instructor')->getSelectedInstructordocs(Mage::registry('current_instructor'));
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $instructordoc) {
            $instructordocs[$instructordoc->getId()] = array('position' => $instructordoc->getPosition());
        }
        return $instructordocs;
    }

    /**
     * get row url
     *
     * @access public
     * @param BS_Material_Model_Instructordoc
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
            '*/*/instructordocsGrid',
            array(
                'id'=>$this->getInstructor()->getId()
            )
        );
    }

    /**
     * get the current instructor
     *
     * @access public
     * @return BS_Instructor_Model_Instructor
     * @author Bui Phong
     */
    public function getInstructor()
    {
        return Mage::registry('current_instructor');
    }

    /**
     * Add filter
     *
     * @access protected
     * @param object $column
     * @return BS_Material_Block_Adminhtml_Instructor_Instructor_Edit_Tab_Instructordoc
     * @author Bui Phong
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_instructordocs') {
            $instructordocIds = $this->_getSelectedInstructordocs();
            if (empty($instructordocIds)) {
                $instructordocIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$instructordocIds));
            } else {
                if ($instructordocIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$instructordocIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
