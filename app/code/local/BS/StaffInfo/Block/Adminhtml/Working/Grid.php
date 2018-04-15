<?php
/**
 * BS_StaffInfo extension
 * 
 * @category       BS
 * @package        BS_StaffInfo
 * @copyright      Copyright (c) 2015
 */
/**
 * Related Working admin grid block
 *
 * @category    BS
 * @package     BS_StaffInfo
 * @author Bui Phong
 */
class BS_StaffInfo_Block_Adminhtml_Working_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    protected $_defaultLimit = 50;
    public function __construct()
    {
        parent::__construct();
        $this->setId('workingGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_StaffInfo_Block_Adminhtml_Working_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_staffinfo/working')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_StaffInfo_Block_Adminhtml_Working_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_staffinfo')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'staff_id',
            array(
                'header' => Mage::helper('bs_staffinfo')->__('Staff ID'),
                'type'      => 'text',
                'renderer'  => 'bs_staffinfo/adminhtml_helper_column_renderer_customer',
                'filter_condition_callback' => array($this, '_customerFilter'),

            )
        );

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


        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_staffinfo')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_staffinfo')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_staffinfo')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_staffinfo')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_staffinfo')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_StaffInfo_Block_Adminhtml_Working_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('working');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("customer/working/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("customer/working/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_staffinfo')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_staffinfo')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_staffinfo')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_staffinfo')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_staffinfo')->__('Enabled'),
                                '0' => Mage::helper('bs_staffinfo')->__('Disabled'),
                            )
                        )
                    )
                )
            );




        }
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param BS_StaffInfo_Model_Working
     * @return string
     * @author Bui Phong
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * get the grid url
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    /**
     * after collection load
     *
     * @access protected
     * @return BS_StaffInfo_Block_Adminhtml_Working_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
