<?php
/**
 * BS_CourseDoc extension
 * 
 * @category       BS
 * @package        BS_CourseDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Course Document tab on product edit form
 *
 * @category    BS
 * @package     BS_CourseDoc
 * @author      Bui Phong
 */
class BS_CourseDoc_Block_Adminhtml_Catalog_Product_Edit_Tab_Coursedoc extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('coursedoc_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        if ($this->getProduct()->getId()) {
            $this->setDefaultFilter(array('in_coursedocs'=>1));
        }
    }

    protected function _prepareLayout(){
        $this->setChild('add_coursedoc_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('Add New Document'),
                    'onclick'   => 'window.open(\''.$this->getUrl('*/coursedoc_coursedoc/new', array('_current'=>false, 'product_id'=>$this->getProduct()->getId(),'popup'=>true)).'\',\'\',\'width=1000,height=700,resizable=1,scrollbars=1\')'
                ))
        );

        $this->setChild('showpage',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('Show Pages'),
                    'onclick'   => 'setLocation(\''.$this->getUrl('*/catalog_product/edit', array( 'id'=>$this->getProduct()->getId(),'back'=>'edit/tab/product_info_tabs_coursedocs','showpage'=>true)).'\')'
                ))
        );

        parent::_prepareLayout();
    }


    public function getMainButtonsHtml()
    {
        $html = '';
        if($this->getFilterVisibility()){
            $html.= $this->getChildHtml('add_coursedoc_button');
            $html.= $this->getChildHtml('showpage');
            $html.= $this->getResetFilterButtonHtml();
            $html.= $this->getSearchButtonHtml();
        }
        return $html;

    }
    /**
     * prepare the coursedoc collection
     *
     * @access protected
     * @return BS_CourseDoc_Block_Adminhtml_Catalog_Product_Edit_Tab_Coursedoc
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_coursedoc/coursedoc_collection');
        if ($this->getProduct()->getId()) {
            $constraint = 'related.product_id='.$this->getProduct()->getId();
        } else {
            $constraint = 'related.product_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('bs_coursedoc/coursedoc_product')),
            'related.coursedoc_id=main_table.entity_id AND '.$constraint,
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
     * @return BS_CourseDoc_Block_Adminhtml_Catalog_Product_Edit_Tab_Coursedoc
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
     * @return BS_CourseDoc_Block_Adminhtml_Catalog_Product_Edit_Tab_Coursedoc
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_coursedocs',
            array(
                'header_css_class'  => 'a-center',
                'type'  => 'checkbox',
                'name'  => 'in_coursedocs',
                'values'=> $this->_getSelectedCoursedocs(),
                'align' => 'center',
                'index' => 'entity_id'
            )
        );
        $this->addColumn(
            'course_doc_name',
            array(
                'header' => Mage::helper('bs_coursedoc')->__('Document Name'),
                'align'  => 'left',
                'index'  => 'course_doc_name',
                'renderer' => 'bs_coursedoc/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getId'
                ),
                'base_link' => 'adminhtml/coursedoc_coursedoc/edit',
            )
        );
        $this->addColumn(
            'doc_inorout',
            array(
                'header'  => Mage::helper('bs_coursedoc')->__('In or Out'),
                'index'   => 'doc_inorout',
                'type'    => 'options',
                'options' => array(
                    '0' => Mage::helper('bs_coursedoc')->__('In Coming'),
                    '1' => Mage::helper('bs_coursedoc')->__('Out Going'),
                )
            )
        );

        $this->addColumn(
            'course_doc_type',
            array(
                'header' => Mage::helper('bs_coursedoc')->__('Document Type'),
                'index'  => 'course_doc_type',
                'type'  => 'options',
                'options' => Mage::helper('bs_coursedoc')->convertOptions(
                    Mage::getModel('bs_coursedoc/coursedoc_attribute_source_coursedoctype')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'doc_dept',
            array(
                'header' => Mage::helper('bs_coursedoc')->__('Department'),
                'index'  => 'doc_dept',
                'type'  => 'options',
                'options' => Mage::helper('bs_coursedoc')->convertOptions(
                    Mage::getModel('bs_coursedoc/coursedoc_attribute_source_coursedocdept')->getAllOptions(false)
                )

            )
        );

        $this->addColumn(
            'doc_date',
            array(
                'header' => Mage::helper('bs_register')->__('Date'),
                'index'  => 'doc_date',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'download',
            array(
                'header'  =>  Mage::helper('bs_coursedoc')->__('View/Download'),
                'type'      =>'text',
                'renderer' => 'bs_coursedoc/adminhtml_helper_column_renderer_download',

                'filter'    => false,
                'sortable'  => false,
            )
        );
        $this->addColumn(
            'position',
            array(
                'header'         => Mage::helper('bs_coursedoc')->__('Position'),
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
     * Retrieve selected coursedocs
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    protected function _getSelectedCoursedocs()
    {
        $coursedocs = $this->getProductCoursedocs();
        if (!is_array($coursedocs)) {
            $coursedocs = array_keys($this->getSelectedCoursedocs());
        }
        return $coursedocs;
    }

    /**
     * Retrieve selected coursedocs
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    public function getSelectedCoursedocs()
    {
        $coursedocs = array();
        //used helper here in order not to override the product model
        $selected = Mage::helper('bs_coursedoc/product')->getSelectedCoursedocs(Mage::registry('current_product'));
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $coursedoc) {
            $coursedocs[$coursedoc->getId()] = array('position' => $coursedoc->getPosition());
        }
        return $coursedocs;
    }

    /**
     * get row url
     *
     * @access public
     * @param BS_CourseDoc_Model_Coursedoc
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
            '*/*/coursedocsGrid',
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
     * @return BS_CourseDoc_Block_Adminhtml_Catalog_Product_Edit_Tab_Coursedoc
     * @author Bui Phong
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_coursedocs') {
            $coursedocIds = $this->_getSelectedCoursedocs();
            if (empty($coursedocIds)) {
                $coursedocIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$coursedocIds));
            } else {
                if ($coursedocIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$coursedocIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
