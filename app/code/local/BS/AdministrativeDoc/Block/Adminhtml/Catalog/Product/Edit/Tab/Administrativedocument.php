<?php
/**
 * BS_AdministrativeDoc extension
 * 
 * @category       BS
 * @package        BS_AdministrativeDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Administrative Document tab on product edit form
 *
 * @category    BS
 * @package     BS_AdministrativeDoc
 * @author Bui Phong
 */
class BS_AdministrativeDoc_Block_Adminhtml_Catalog_Product_Edit_Tab_Administrativedocument extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('administrativedocument_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        if ($this->getProduct()->getId()) {
            $this->setDefaultFilter(array('in_administrativedocuments'=>1));
        }
    }

    /**
     * prepare the administrativedocument collection
     *
     * @access protected
     * @return BS_AdministrativeDoc_Block_Adminhtml_Catalog_Product_Edit_Tab_Administrativedocument
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_administrativedoc/administrativedocument_collection');
        if ($this->getProduct()->getId()) {
            $constraint = 'related.product_id='.$this->getProduct()->getId();
        } else {
            $constraint = 'related.product_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('bs_administrativedoc/administrativedocument_product')),
            'related.administrativedocument_id=main_table.entity_id AND '.$constraint,
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
     * @return BS_AdministrativeDoc_Block_Adminhtml_Catalog_Product_Edit_Tab_Administrativedocument
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
     * @return BS_AdministrativeDoc_Block_Adminhtml_Catalog_Product_Edit_Tab_Administrativedocument
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_administrativedocuments',
            array(
                'header_css_class'  => 'a-center',
                'type'  => 'checkbox',
                'name'  => 'in_administrativedocuments',
                'values'=> $this->_getSelectedAdministrativedocuments(),
                'align' => 'center',
                'index' => 'entity_id'
            )
        );
        $this->addColumn(
            'doc_name',
            array(
                'header' => Mage::helper('bs_administrativedoc')->__('Document Name'),
                'align'  => 'left',
                'index'  => 'doc_name',
                'renderer' => 'bs_administrativedoc/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getId'
                ),
                'base_link' => 'adminhtml/administrativedoc_administrativedocument/edit',
            )
        );
        $this->addColumn(
            'position',
            array(
                'header'         => Mage::helper('bs_administrativedoc')->__('Position'),
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
     * Retrieve selected administrativedocuments
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    protected function _getSelectedAdministrativedocuments()
    {
        $administrativedocuments = $this->getProductAdministrativedocuments();
        if (!is_array($administrativedocuments)) {
            $administrativedocuments = array_keys($this->getSelectedAdministrativedocuments());
        }
        return $administrativedocuments;
    }

    /**
     * Retrieve selected administrativedocuments
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    public function getSelectedAdministrativedocuments()
    {
        $administrativedocuments = array();
        //used helper here in order not to override the product model
        $selected = Mage::helper('bs_administrativedoc/product')->getSelectedAdministrativedocuments(Mage::registry('current_product'));
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $administrativedocument) {
            $administrativedocuments[$administrativedocument->getId()] = array('position' => $administrativedocument->getPosition());
        }
        return $administrativedocuments;
    }

    /**
     * get row url
     *
     * @access public
     * @param BS_AdministrativeDoc_Model_Administrativedocument
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
            '*/*/administrativedocumentsGrid',
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
     * @return BS_AdministrativeDoc_Block_Adminhtml_Catalog_Product_Edit_Tab_Administrativedocument
     * @author Bui Phong
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_administrativedocuments') {
            $administrativedocumentIds = $this->_getSelectedAdministrativedocuments();
            if (empty($administrativedocumentIds)) {
                $administrativedocumentIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$administrativedocumentIds));
            } else {
                if ($administrativedocumentIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$administrativedocumentIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
