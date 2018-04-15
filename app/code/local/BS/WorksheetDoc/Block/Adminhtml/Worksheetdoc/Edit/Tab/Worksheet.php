<?php
/**
 * BS_WorksheetDoc extension
 * 
 * @category       BS
 * @package        BS_WorksheetDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Worksheet Document - worksheet relation edit block
 *
 * @category    BS
 * @package     BS_WorksheetDoc
 * @author      Bui Phong
 */
class BS_WorksheetDoc_Block_Adminhtml_Worksheetdoc_Edit_Tab_Worksheet extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set grid params
     *
     * @access protected
     * @author Bui Phong
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('worksheet_grid');
        //$this->setDefaultSort('position');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        if ($this->getWorksheetdoc()->getId()) {
            $this->setDefaultFilter(array('in_worksheets'=>1));
        }
    }

    /**
     * prepare the worksheet collection
     *
     * @access protected
     * @return BS_WorksheetDoc_Block_Adminhtml_Worksheetdoc_Edit_Tab_Worksheet
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_worksheet/worksheet_collection');

        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * prepare mass action grid
     *
     * @access protected
     * @return BS_WorksheetDoc_Block_Adminhtml_Worksheetdoc_Edit_Tab_Worksheet
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
     * @return BS_WorksheetDoc_Block_Adminhtml_Worksheetdoc_Edit_Tab_Worksheet
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_worksheets',
            array(
                'header_css_class'  => 'a-center',
                'type'  => 'checkbox',
                'name'  => 'in_worksheets',
                'values'=> $this->_getSelectedWorksheets(),
                'align' => 'center',
                'index' => 'entity_id'
            )
        );
        $this->addColumn(
            'ws_name',
            array(
                'header'    => Mage::helper('bs_worksheet')->__('Worksheet Name'),
                'align'     => 'left',
                'index'     => 'ws_name',
                'renderer'  => 'bs_worksheetdoc/adminhtml_helper_column_renderer_relation',
                'params'    => array(
                    'id'    => 'getId'
                ),
                'base_link' => 'adminhtml/worksheet_worksheet/edit',
            )
        );
        $this->addColumn(
            'ws_code',
            array(
                'header' => Mage::helper('bs_worksheet')->__('Worksheet Code'),
                'align'  => 'left',
                'index'  => 'ws_code',
            )
        );
        /*$this->addColumn(
            'price',
            array(
                'header'        => Mage::helper('bs_worksheet')->__('Price'),
                'type'          => 'currency',
                'width'         => '1',
                'currency_code' => (string)Mage::getStoreConfig(
                    Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE
                ),
                'index'         => 'price'
            )
        );*/
        $this->addColumn(
            'position',
            array(
                'header'         => Mage::helper('bs_worksheet')->__('Position'),
                'name'           => 'position',
                'width'          => 60,
                'type'           => 'number',
                'validate_class' => 'validate-number',
                'index'          => 'position',
                'editable'       => true,
            )
        );
    }

    /**
     * Retrieve selected worksheets
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    protected function _getSelectedWorksheets()
    {
        $worksheets = $this->getWorksheetdocWorksheets();
        if (!is_array($worksheets)) {
            $worksheets = array_keys($this->getSelectedWorksheets());
        }
        return $worksheets;
    }

    /**
     * Retrieve selected worksheets
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    public function getSelectedWorksheets()
    {
        $worksheets = array();
        $selected = Mage::registry('current_worksheetdoc')->getSelectedWorksheets();
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $worksheet) {
            $worksheets[$worksheet->getId()] = array('position' => $worksheet->getPosition());
        }
        return $worksheets;
    }

    /**
     * get row url
     *
     * @access public
     * @param BS_WorksheetDoc_Model_Worksheet
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
            '*/*/worksheetsGrid',
            array(
                'id' => $this->getWorksheetdoc()->getId()
            )
        );
    }

    /**
     * get the current worksheet doc
     *
     * @access public
     * @return BS_WorksheetDoc_Model_Worksheetdoc
     * @author Bui Phong
     */
    public function getWorksheetdoc()
    {
        return Mage::registry('current_worksheetdoc');
    }

    /**
     * Add filter
     *
     * @access protected
     * @param object $column
     * @return BS_WorksheetDoc_Block_Adminhtml_Worksheetdoc_Edit_Tab_Worksheet
     * @author Bui Phong
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in worksheet flag
        if ($column->getId() == 'in_worksheets') {
            $worksheetIds = $this->_getSelectedWorksheets();
            if (empty($worksheetIds)) {
                $worksheetIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in' => $worksheetIds));
            } else {
                if ($worksheetIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin' => $worksheetIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
