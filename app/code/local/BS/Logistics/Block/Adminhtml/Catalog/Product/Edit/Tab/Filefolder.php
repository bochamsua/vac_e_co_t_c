<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * File Folder tab on product edit form
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Catalog_Product_Edit_Tab_Filefolder extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('filefolder_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        if ($this->getProduct()->getId()) {
            $this->setDefaultFilter(array('in_filefolders'=>1));
        }
    }

    /**
     * prepare the filefolder collection
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Catalog_Product_Edit_Tab_Filefolder
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_logistics/filefolder_collection');
        if ($this->getProduct()->getId()) {
            $constraint = 'related.product_id='.$this->getProduct()->getId();
        } else {
            $constraint = 'related.product_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('bs_logistics/filefolder_product')),
            'related.filefolder_id=main_table.entity_id AND '.$constraint,
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
     * @return BS_Logistics_Block_Adminhtml_Catalog_Product_Edit_Tab_Filefolder
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
     * @return BS_Logistics_Block_Adminhtml_Catalog_Product_Edit_Tab_Filefolder
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_filefolders',
            array(
                'header_css_class'  => 'a-center',
                'type'  => 'checkbox',
                'name'  => 'in_filefolders',
                'values'=> $this->_getSelectedFilefolders(),
                'align' => 'center',
                'index' => 'entity_id'
            )
        );
        $this->addColumn(
            'filefolder_code',
            array(
                'header' => Mage::helper('bs_logistics')->__('Code'),
                'align'  => 'left',
                'index'  => 'filefolder_code',
                'renderer' => 'bs_logistics/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getId'
                ),
                'base_link' => 'adminhtml/logistics_filefolder/edit',
            )
        );
        $this->addColumn(
            'filefolder_name',
            array(
                'header' => Mage::helper('bs_logistics')->__('Name'),
                'align'  => 'left',
                'index'  => 'filefolder_name',
                'renderer' => 'bs_logistics/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getId'
                ),
                'base_link' => 'adminhtml/logistics_filefolder/edit',
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
     * Retrieve selected filefolders
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    protected function _getSelectedFilefolders()
    {
        $filefolders = $this->getProductFilefolders();
        if (!is_array($filefolders)) {
            $filefolders = array_keys($this->getSelectedFilefolders());
        }
        return $filefolders;
    }

    /**
     * Retrieve selected filefolders
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    public function getSelectedFilefolders()
    {
        $filefolders = array();
        //used helper here in order not to override the product model
        $selected = Mage::helper('bs_logistics/product')->getSelectedFilefolders(Mage::registry('current_product'));
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $filefolder) {
            $filefolders[$filefolder->getId()] = array('position' => $filefolder->getPosition());
        }
        return $filefolders;
    }

    /**
     * get row url
     *
     * @access public
     * @param BS_Logistics_Model_Filefolder
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
            '*/*/filefoldersGrid',
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
     * @return BS_Logistics_Block_Adminhtml_Catalog_Product_Edit_Tab_Filefolder
     * @author Bui Phong
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_filefolders') {
            $filefolderIds = $this->_getSelectedFilefolders();
            if (empty($filefolderIds)) {
                $filefolderIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$filefolderIds));
            } else {
                if ($filefolderIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$filefolderIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
