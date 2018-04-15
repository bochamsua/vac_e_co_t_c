<?php
/**
 * BS_Register extension
 * 
 * @category       BS
 * @package        BS_Register
 * @copyright      Copyright (c) 2015
 */
/**
 * Course Schedule tab on product edit form
 *
 * @category    BS
 * @package     BS_Register
 * @author Bui Phong
 */
class BS_StaffInfo_Block_Adminhtml_Customer_Edit_Tab_Working extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('working_grid');
       // $this->setDefaultSort('position');
        //$this->setDefaultDir('DESC');
        $this->setUseAjax(true);

    }

    protected function _prepareLayout(){
        $this->setChild('add_working_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('Add Record'),
                    'onclick'   => 'window.open(\''.$this->getUrl('*/staffinfo_working/new', array('_current'=>false, 'staff_id'=>$this->getCustomer()->getId(),'popup'=>true)).'\',\'\',\'width=1000,height=700,resizable=1,scrollbars=1\')'
                ))
        );

        parent::_prepareLayout();
    }


    public function getMainButtonsHtml()
    {
        $html = '';
        if($this->getFilterVisibility()){
            $html.= $this->getChildHtml('add_working_button');
            $html.= $this->getResetFilterButtonHtml();
            $html.= $this->getSearchButtonHtml();
        }
        return $html;

    }

    /**
     * prepare the schedule collection
     *
     * @access protected
     * @return BS_Register_Block_Adminhtml_Catalog_Product_Edit_Tab_Schedule
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_staffinfo/working_collection');
        if ($this->getCustomer()->getId()) {
            $collection->addFieldToFilter('staff_id', $this->getCustomer()->getId());
        }
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * prepare mass action grid
     *
     * @access protected
     * @return BS_Register_Block_Adminhtml_Catalog_Product_Edit_Tab_Schedule
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
     * @return BS_Register_Block_Adminhtml_Catalog_Product_Edit_Tab_Schedule
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {

        $this->addColumn(
            'start_date',
            array(
                'header' => Mage::helper('bs_staffinfo')->__('Start Date'),
                'index'  => 'start_date',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'end_date',
            array(
                'header' => Mage::helper('bs_staffinfo')->__('End Date'),
                'index'  => 'end_date',
                'type'=> 'date',

            )
        );

        $this->addColumn(
            'working_place',
            array(
                'header'    => Mage::helper('bs_staffinfo')->__('Working Place'),
                'index'     => 'working_place',
                'type'=> 'text',
            )
        );



        $this->addColumn(
            'working_as',
            array(
                'header' => Mage::helper('bs_staffinfo')->__('Working As'),
                'index'  => 'working_as',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'working_on',
            array(
                'header' => Mage::helper('bs_staffinfo')->__('Working On'),
                'index'  => 'working_on',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'remark',
            array(
                'header' => Mage::helper('bs_staffinfo')->__('Remark'),
                'index'  => 'remark',
                'type'=> 'text',

            )
        );

        $this->addColumn('action',
            array(
                'header'    => Mage::helper('catalog')->__('Action'),
                'width'     => '50px',
                'type'      => 'action',
                'getter'     => 'getId',
                'actions'   => array(
                    array(
                        'caption' => 'Edit',
                        'url'     => array('base' => '*/staffinfo_working/edit', 'params' => array('popup'=> 1)),
                        'field'   => 'id',
                        'onclick'   => 'window.open(this.href,\'\',\'width=1000,height=700,resizable=1,scrollbars=1\'); return false;'
                    )
                ),
                'filter'    => false,
                'sortable'  => false
            )
        );

        return parent::_prepareColumns();
    }



    public function getRowUrl($item)
    {
        return '#';//$this->getUrl('*/register_attendance/edit', array('id' => $item->getId()));
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
            '*/*/workingsGrid',
            array(
                'id'=>$this->getCustomer()->getId()
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
    public function getCustomer()
    {
        return Mage::registry('current_customer');
    }
}
