<?php
/**
 * BS_Otherdoc extension
 * 
 * @category       BS
 * @package        BS_Otherdoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Other\'s Course Document tab on product edit form
 *
 * @category    BS
 * @package     BS_Otherdoc
 * @author Bui Phong
 */
class BS_Otherdoc_Block_Adminhtml_Catalog_Product_Edit_Tab_Otherdoc extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('otherdoc_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        if ($this->getProduct()->getId()) {
            $this->setDefaultFilter(array('in_otherdocs'=>1));
        }
    }

    protected function _prepareLayout(){
        $this->setChild('add_otherdoc_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('Add New Document'),
                    'onclick'   => 'window.open(\''.$this->getUrl('*/otherdoc_otherdoc/new', array('_current'=>false, 'product_id'=>$this->getProduct()->getId(),'popup'=>true)).'\',\'\',\'width=1000,height=700,resizable=1,scrollbars=1\')'
                ))
        );

        parent::_prepareLayout();
    }


    public function getMainButtonsHtml()
    {
        $html = '';
        if($this->getFilterVisibility()){
            $html.= $this->getChildHtml('add_otherdoc_button');
            $html.= $this->getResetFilterButtonHtml();
            $html.= $this->getSearchButtonHtml();
        }
        return $html;

    }
    /**
     * prepare the otherdoc collection
     *
     * @access protected
     * @return BS_Otherdoc_Block_Adminhtml_Catalog_Product_Edit_Tab_Otherdoc
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_otherdoc/otherdoc_collection');
        if ($this->getProduct()->getId()) {
            $constraint = 'related.product_id='.$this->getProduct()->getId();
        } else {
            $constraint = 'related.product_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('bs_otherdoc/otherdoc_product')),
            'related.otherdoc_id=main_table.entity_id AND '.$constraint,
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
     * @return BS_Otherdoc_Block_Adminhtml_Catalog_Product_Edit_Tab_Otherdoc
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
     * @return BS_Otherdoc_Block_Adminhtml_Catalog_Product_Edit_Tab_Otherdoc
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_otherdocs',
            array(
                'header_css_class'  => 'a-center',
                'type'  => 'checkbox',
                'name'  => 'in_otherdocs',
                'values'=> $this->_getSelectedOtherdocs(),
                'align' => 'center',
                'index' => 'entity_id'
            )
        );
        $this->addColumn(
            'otherdoc_name',
            array(
                'header' => Mage::helper('bs_otherdoc')->__('Document Name'),
                'align'  => 'left',
                'index'  => 'otherdoc_name',
                'renderer' => 'bs_otherdoc/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getId'
                ),
                'base_link' => 'adminhtml/otherdoc_otherdoc/edit',
            )
        );
        $this->addColumn(
            'otherdoc_type',
            array(
                'header' => Mage::helper('bs_otherdoc')->__('Document Type'),
                'index'  => 'otherdoc_type',
                'type'  => 'options',
                'options' => Mage::helper('bs_otherdoc')->convertOptions(
                    Mage::getModel('bs_otherdoc/otherdoc_attribute_source_otherdoctype')->getAllOptions(false)
                )

            )
        );

        $this->addColumn(
            'otherdoc_date',
            array(
                'header' => Mage::helper('bs_otherdoc')->__('Date'),
                'index'  => 'otherdoc_date',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'download',
            array(
                'header'  =>  Mage::helper('bs_otherdoc')->__('View/Download'),
                'type'      =>'text',
                'renderer' => 'bs_otherdoc/adminhtml_helper_column_renderer_download',

                'filter'    => false,
                'sortable'  => false,
            )
        );

        $this->addColumn(
            'position',
            array(
                'header'         => Mage::helper('bs_otherdoc')->__('Position'),
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
     * Retrieve selected otherdocs
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    protected function _getSelectedOtherdocs()
    {
        $otherdocs = $this->getProductOtherdocs();
        if (!is_array($otherdocs)) {
            $otherdocs = array_keys($this->getSelectedOtherdocs());
        }
        return $otherdocs;
    }

    /**
     * Retrieve selected otherdocs
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    public function getSelectedOtherdocs()
    {
        $otherdocs = array();
        //used helper here in order not to override the product model
        $selected = Mage::helper('bs_otherdoc/product')->getSelectedOtherdocs(Mage::registry('current_product'));
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $otherdoc) {
            $otherdocs[$otherdoc->getId()] = array('position' => $otherdoc->getPosition());
        }
        return $otherdocs;
    }

    /**
     * get row url
     *
     * @access public
     * @param BS_Otherdoc_Model_Otherdoc
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
            '*/*/otherdocsGrid',
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
     * @return BS_Otherdoc_Block_Adminhtml_Catalog_Product_Edit_Tab_Otherdoc
     * @author Bui Phong
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_otherdocs') {
            $otherdocIds = $this->_getSelectedOtherdocs();
            if (empty($otherdocIds)) {
                $otherdocIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$otherdocIds));
            } else {
                if ($otherdocIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$otherdocIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
