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
class BS_Register_Block_Adminhtml_Catalog_Product_Edit_Tab_Absence extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('absence_grid');
       // $this->setDefaultSort('position');
        //$this->setDefaultDir('DESC');
        $this->setUseAjax(true);

    }

    protected function _prepareLayout(){
        $this->setChild('add_absence_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('Add Record'),
                    'onclick'   => 'window.open(\''.$this->getUrl('*/register_attendance/new', array('_current'=>false, 'product_id'=>$this->getProduct()->getId(),'popup'=>true)).'\',\'\',\'width=1000,height=700,resizable=1,scrollbars=1\')'
                ))
        );

        parent::_prepareLayout();
    }


    public function getMainButtonsHtml()
    {
        $html = '';
        if($this->getFilterVisibility()){
            $html.= $this->getChildHtml('add_absence_button');
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
        $collection = Mage::getResourceModel('bs_register/attendance_collection');
        if ($this->getProduct()->getId()) {
            $collection->addFieldToFilter('course_id', $this->getProduct()->getId());
        }
        $collection->getSelect()->joinLeft(array('trainee'=>'bs_trainee_trainee_varchar'),'trainee_id = trainee.entity_id AND trainee.attribute_id = 276','trainee.value');
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
            'trainee_id',
            array(
                'header'    => Mage::helper('bs_register')->__('Trainee'),
                'type'      => 'text',
                'renderer'  => 'bs_register/adminhtml_helper_column_renderer_trainee',
                'filter_condition_callback' => array($this, '_traineeFilter'),

            )
        );

        $this->addColumn(
            'att_start_date',
            array(
                'header' => Mage::helper('bs_register')->__('OFF From Date'),
                'index'  => 'att_start_date',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'att_start_time',
            array(
                'header' => Mage::helper('bs_register')->__('OFF From Time'),
                'index'  => 'att_start_time',
                'type'  => 'options',
                'options' => Mage::helper('bs_register')->convertOptions(
                    Mage::getModel('bs_register/attendance_attribute_source_attstarttime')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'att_finish_date',
            array(
                'header' => Mage::helper('bs_register')->__('OFF To Date'),
                'index'  => 'att_finish_date',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'att_finish_time',
            array(
                'header' => Mage::helper('bs_register')->__('OFF To Time'),
                'index'  => 'att_finish_time',
                'type'  => 'options',
                'options' => Mage::helper('bs_register')->convertOptions(
                    Mage::getModel('bs_register/attendance_attribute_source_attfinishtime')->getAllOptions(false)
                )

            )
        );

        $this->addColumn(
            'att_excuse',
            array(
                'header' => Mage::helper('bs_register')->__('Excuse'),
                'index'  => 'att_excuse',
                'type'    => 'options',
                'options'    => array(
                    '1' => Mage::helper('bs_register')->__('Yes'),
                    '0' => Mage::helper('bs_register')->__('No'),
                )

            )
        );
        $this->addColumn(
            'att_note',
            array(
                'header'    => Mage::helper('bs_register')->__('Note'),
                'align'     => 'left',
                'index'     => 'att_note',
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
                        'url'     => array('base' => '*/register_attendance/edit', 'params' => array('popup'=> 1)),
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

    protected function _traineeFilter($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }

        $this->getCollection()->getSelect()->where(
            "trainee.value LIKE ?"
            , "%$value%");


        return $this;
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
            '*/*/absencesGrid',
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
}
