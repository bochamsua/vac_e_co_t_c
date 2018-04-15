<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Workshop tab on product edit form
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Catalog_Product_Edit_Tab_Workshop extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('workshop_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        if ($this->getProduct()->getId()) {
            $this->setDefaultFilter(array('in_workshops'=>1));
        }
    }

    /**
     * prepare the workshop collection
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Catalog_Product_Edit_Tab_Workshop
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_logistics/workshop_collection');
        if ($this->getProduct()->getId()) {
            $constraint = 'related.product_id='.$this->getProduct()->getId();
        } else {
            $constraint = 'related.product_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('bs_logistics/workshop_product')),
            'related.workshop_id=main_table.entity_id AND '.$constraint,
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
     * @return BS_Logistics_Block_Adminhtml_Catalog_Product_Edit_Tab_Workshop
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
     * @return BS_Logistics_Block_Adminhtml_Catalog_Product_Edit_Tab_Workshop
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_workshops',
            array(
                'header_css_class'  => 'a-center',
                'type'  => 'checkbox',
                'name'  => 'in_workshops',
                'values'=> $this->_getSelectedWorkshops(),
                'align' => 'center',
                'index' => 'entity_id'
            )
        );
        $this->addColumn(
            'workshop_code',
            array(
                'header' => Mage::helper('bs_logistics')->__('Code'),
                'align'  => 'left',
                'index'  => 'workshop_code',
                'renderer' => 'bs_logistics/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getId'
                ),
                'base_link' => 'adminhtml/logistics_workshop/edit',
            )
        );
        $this->addColumn(
            'workshop_name',
            array(
                'header' => Mage::helper('bs_logistics')->__('Name'),
                'align'  => 'left',
                'index'  => 'workshop_name',
                'renderer' => 'bs_logistics/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getId'
                ),
                'base_link' => 'adminhtml/logistics_workshop/edit',
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
     * Retrieve selected workshops
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    protected function _getSelectedWorkshops()
    {
        $workshops = $this->getProductWorkshops();
        if (!is_array($workshops)) {
            $workshops = array_keys($this->getSelectedWorkshops());
        }
        return $workshops;
    }

    /**
     * Retrieve selected workshops
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    public function getSelectedWorkshops()
    {
        $workshops = array();
        //used helper here in order not to override the product model
        $selected = Mage::helper('bs_logistics/product')->getSelectedWorkshops(Mage::registry('current_product'));
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $workshop) {
            $workshops[$workshop->getId()] = array('position' => $workshop->getPosition());
        }
        return $workshops;
    }

    /**
     * get row url
     *
     * @access public
     * @param BS_Logistics_Model_Workshop
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
            '*/*/workshopsGrid',
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
     * @return BS_Logistics_Block_Adminhtml_Catalog_Product_Edit_Tab_Workshop
     * @author Bui Phong
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_workshops') {
            $workshopIds = $this->_getSelectedWorkshops();
            if (empty($workshopIds)) {
                $workshopIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$workshopIds));
            } else {
                if ($workshopIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$workshopIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
