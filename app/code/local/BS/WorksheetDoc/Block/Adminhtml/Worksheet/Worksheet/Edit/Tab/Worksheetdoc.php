<?php
/**
 * BS_WorksheetDoc extension
 * 
 * @category       BS
 * @package        BS_WorksheetDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Worksheet Document tab on worksheet edit form
 *
 * @category    BS
 * @package     BS_WorksheetDoc
 * @author      Bui Phong
 */
class BS_WorksheetDoc_Block_Adminhtml_Worksheet_Worksheet_Edit_Tab_Worksheetdoc extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('worksheetdoc_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        if ($this->getWorksheet()->getId()) {
            $this->setDefaultFilter(array('in_worksheetdocs'=>1));
        }
    }

    protected function _prepareLayout(){
        $this->setChild('add_wsdoc_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('Add New Document'),
                    'onclick'   => 'window.open(\''.$this->getUrl('*/worksheetdoc_worksheetdoc/new', array('_current'=>false, 'worksheet_id'=>$this->getWorksheet()->getId(),'popup'=>true)).'\',\'\',\'width=1000,height=700,resizable=1,scrollbars=1\')'
                ))
        );

        parent::_prepareLayout();
    }


    public function getMainButtonsHtml()
    {
        $html = '';
        if($this->getFilterVisibility()){
            $html.= $this->getChildHtml('add_wsdoc_button');
            $html.= $this->getResetFilterButtonHtml();
            $html.= $this->getSearchButtonHtml();
        }
        return $html;

    }
    /**
     * prepare the worksheetdoc collection
     *
     * @access protected
     * @return BS_WorksheetDoc_Block_Adminhtml_Worksheet_Worksheet_Edit_Tab_Worksheetdoc
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_worksheetdoc/worksheetdoc_collection');
        if ($this->getWorksheet()->getId()) {
            $constraint = 'related.worksheet_id='.$this->getWorksheet()->getId();
        } else {
            $constraint = 'related.worksheet_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('bs_worksheetdoc/worksheetdoc_worksheet')),
            'related.worksheetdoc_id=main_table.entity_id AND '.$constraint,
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
     * @return BS_WorksheetDoc_Block_Adminhtml_Worksheet_Worksheet_Edit_Tab_Worksheetdoc
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
     * @return BS_WorksheetDoc_Block_Adminhtml_Worksheet_Worksheet_Edit_Tab_Worksheetdoc
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_worksheetdocs',
            array(
                'header_css_class'  => 'a-center',
                'type'  => 'checkbox',
                'name'  => 'in_worksheetdocs',
                'values'=> $this->_getSelectedWorksheetdocs(),
                'align' => 'center',
                'index' => 'entity_id'
            )
        );
        $this->addColumn(
            'wsdoc_name',
            array(
                'header' => Mage::helper('bs_worksheetdoc')->__('Document Name'),
                'align'  => 'left',
                'index'  => 'wsdoc_name',
                'renderer' => 'bs_worksheetdoc/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getId'
                ),
                'base_link' => 'adminhtml/worksheetdoc_worksheetdoc/edit',
            )
        );
        $this->addColumn(
            'wsdoc_type',
            array(
                'header' => Mage::helper('bs_worksheetdoc')->__('Document Type'),
                'index'  => 'wsdoc_type',
                'type'  => 'options',
                'options' => Mage::helper('bs_worksheetdoc')->convertOptions(
                    Mage::getModel('bs_worksheetdoc/worksheetdoc_attribute_source_wsdoctype')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'download',
            array(
                'header'  =>  Mage::helper('bs_curriculumdoc')->__('View/Download'),
                'type'      =>'text',
                'renderer' => 'bs_worksheetdoc/adminhtml_helper_column_renderer_download',

                'filter'    => false,
                'sortable'  => false,
            )
        );
        $this->addColumn(
            'position',
            array(
                'header'         => Mage::helper('bs_worksheetdoc')->__('Position'),
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
     * Retrieve selected worksheetdocs
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    protected function _getSelectedWorksheetdocs()
    {
        $worksheetdocs = $this->getWorksheetWorksheetdocs();
        if (!is_array($worksheetdocs)) {
            $worksheetdocs = array_keys($this->getSelectedWorksheetdocs());
        }
        return $worksheetdocs;
    }

    /**
     * Retrieve selected worksheetdocs
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    public function getSelectedWorksheetdocs()
    {
        $worksheetdocs = array();
        //used helper here in order not to override the worksheet model
        $selected = Mage::helper('bs_worksheetdoc/worksheet')->getSelectedWorksheetdocs(Mage::registry('current_worksheet'));
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $worksheetdoc) {
            $worksheetdocs[$worksheetdoc->getId()] = array('position' => $worksheetdoc->getPosition());
        }
        return $worksheetdocs;
    }

    /**
     * get row url
     *
     * @access public
     * @param BS_WorksheetDoc_Model_Worksheetdoc
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
            '*/*/worksheetdocsGrid',
            array(
                'id'=>$this->getWorksheet()->getId()
            )
        );
    }

    /**
     * get the current worksheet
     *
     * @access public
     * @return BS_Worksheet_Model_Worksheet
     * @author Bui Phong
     */
    public function getWorksheet()
    {
        return Mage::registry('current_worksheet');
    }

    /**
     * Add filter
     *
     * @access protected
     * @param object $column
     * @return BS_WorksheetDoc_Block_Adminhtml_Worksheet_Worksheet_Edit_Tab_Worksheetdoc
     * @author Bui Phong
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_worksheetdocs') {
            $worksheetdocIds = $this->_getSelectedWorksheetdocs();
            if (empty($worksheetdocIds)) {
                $worksheetdocIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$worksheetdocIds));
            } else {
                if ($worksheetdocIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$worksheetdocIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
