<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Classroom/Examroom - product relation edit block
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Classroom_Edit_Tab_Product extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('product_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        if ($this->getClassroom()->getId()) {
            $this->setDefaultFilter(array('in_products'=>1));
        }
    }

    /**
     * prepare the product collection
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Classroom_Edit_Tab_Product
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('catalog/product_collection');
        $collection->addAttributeToSelect('price');
        $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
        $collection->joinAttribute('product_name', 'catalog_product/name', 'entity_id', null, 'left', $adminStore);
        if ($this->getClassroom()->getId()) {
            $constraint = '{{table}}.classroom_id='.$this->getClassroom()->getId();
        } else {
            $constraint = '{{table}}.classroom_id=0';
        }
        $collection->joinField(
            'position',
            'bs_logistics/classroom_product',
            'position',
            'product_id=entity_id',
            $constraint,
            'left'
        );
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * prepare mass action grid
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Classroom_Edit_Tab_Product
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
     * @return BS_Logistics_Block_Adminhtml_Classroom_Edit_Tab_Product
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_products',
            array(
                'header_css_class'  => 'a-center',
                'type'  => 'checkbox',
                'name'  => 'in_products',
                'values'=> $this->_getSelectedProducts(),
                'align' => 'center',
                'index' => 'entity_id'
            )
        );
        $this->addColumn(
            'sku',
            array(
                'header' => Mage::helper('catalog')->__('Course Code'),
                'align'  => 'left',
                'index'  => 'sku',
            )
        );

        $this->addColumn(
            'product_name',
            array(
                'header'    => Mage::helper('catalog')->__('Course Name'),
                'align'     => 'left',
                'index'     => 'product_name',
                'renderer'  => 'bs_logistics/adminhtml_helper_column_renderer_relation',
                'params'    => array(
                    'id'    => 'getId'
                ),
                'base_link' => 'adminhtml/catalog_product/edit',
            )
        );

       /* $this->addColumn(
            'price',
            array(
                'header'        => Mage::helper('catalog')->__('Price'),
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
                'header'         => Mage::helper('catalog')->__('Position'),
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
     * Retrieve selected products
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    protected function _getSelectedProducts()
    {
        $products = $this->getClassroomProducts();
        if (!is_array($products)) {
            $products = array_keys($this->getSelectedProducts());
        }
        return $products;
    }

    /**
     * Retrieve selected products
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    public function getSelectedProducts()
    {
        $products = array();
        $selected = Mage::registry('current_classroom')->getSelectedProducts();
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $product) {
            $products[$product->getId()] = array('position' => $product->getPosition());
        }
        return $products;
    }

    /**
     * get row url
     *
     * @access public
     * @param BS_Logistics_Model_Product
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
            '*/*/productsGrid',
            array(
                'id' => $this->getClassroom()->getId()
            )
        );
    }

    /**
     * get the current classroom/examroom
     *
     * @access public
     * @return BS_Logistics_Model_Classroom
     * @author Bui Phong
     */
    public function getClassroom()
    {
        return Mage::registry('current_classroom');
    }

    /**
     * Add filter
     *
     * @access protected
     * @param object $column
     * @return BS_Logistics_Block_Adminhtml_Classroom_Edit_Tab_Product
     * @author Bui Phong
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in product flag
        if ($column->getId() == 'in_products') {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in' => $productIds));
            } else {
                if ($productIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin' => $productIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
