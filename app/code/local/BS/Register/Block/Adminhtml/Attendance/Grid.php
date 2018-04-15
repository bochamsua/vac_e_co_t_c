<?php
/**
 * BS_Register extension
 * 
 * @category       BS
 * @package        BS_Register
 * @copyright      Copyright (c) 2015
 */
/**
 * Attendance admin grid block
 *
 * @category    BS
 * @package     BS_Register
 * @author Bui Phong
 */
class BS_Register_Block_Adminhtml_Attendance_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('attendanceGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Register_Block_Adminhtml_Attendance_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_register/attendance')
            ->getCollection();

        $collection->getSelect()->joinLeft(array('pro'=>'catalog_product_entity'),'course_id = pro.entity_id','sku');
        $collection->getSelect()->joinLeft(array('trainee'=>'bs_trainee_trainee_varchar'),'trainee_id = trainee.entity_id AND trainee.attribute_id = 276','trainee.value');
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Register_Block_Adminhtml_Attendance_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {

        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_register')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'course_id',
            array(
                'header'    => Mage::helper('bs_register')->__('Course'),
                'type'      => 'text',
                'renderer'  => 'bs_register/adminhtml_helper_column_renderer_course',
                'filter_condition_callback' => array($this, '_courseFilter'),

            )
        );
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

        /*$this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_register')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_register')->__('Enabled'),
                    '0' => Mage::helper('bs_register')->__('Disabled'),
                )
            )
        );*/

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_register')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_register')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_register')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_register')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_register')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * course callback filter
     *
     * @access protected
     * @return BS_Register_Block_Adminhtml_Schedule_Grid
     * @author Bui Phong
     */

    protected function _courseFilter($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }

        $this->getCollection()->getSelect()->where(
            "sku LIKE ?"
            , "%$value%");


        return $this;
    }


    /**
     * trainee callback filter
     *
     * @access protected
     * @return BS_Register_Block_Adminhtml_Schedule_Grid
     * @author Bui Phong
     */

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

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_Register_Block_Adminhtml_Attendance_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('attendance');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("catalog/bs_register/attendance/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("catalog/bs_register/attendance/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_register')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_register')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_register')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_register')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_register')->__('Enabled'),
                                '0' => Mage::helper('bs_register')->__('Disabled'),
                            )
                        )
                    )
                )
            );




        $this->getMassactionBlock()->addItem(
            'att_start_time',
            array(
                'label'      => Mage::helper('bs_register')->__('Change Time Start'),
                'url'        => $this->getUrl('*/*/massAttStartTime', array('_current'=>true)),
                'additional' => array(
                    'flag_att_start_time' => array(
                        'name'   => 'flag_att_start_time',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_register')->__('Time Start'),
                        'values' => Mage::getModel('bs_register/attendance_attribute_source_attstarttime')
                            ->getAllOptions(true),

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'att_finish_time',
            array(
                'label'      => Mage::helper('bs_register')->__('Change Time End'),
                'url'        => $this->getUrl('*/*/massAttFinishTime', array('_current'=>true)),
                'additional' => array(
                    'flag_att_finish_time' => array(
                        'name'   => 'flag_att_finish_time',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_register')->__('Time End'),
                        'values' => Mage::getModel('bs_register/attendance_attribute_source_attfinishtime')
                            ->getAllOptions(true),

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'att_excuse',
            array(
                'label'      => Mage::helper('bs_register')->__('Change Excuse'),
                'url'        => $this->getUrl('*/*/massAttExcuse', array('_current'=>true)),
                'additional' => array(
                    'flag_att_excuse' => array(
                        'name'   => 'flag_att_excuse',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_register')->__('Excuse'),
                        'values' => array(
                                '1' => Mage::helper('bs_register')->__('Yes'),
                                '0' => Mage::helper('bs_register')->__('No'),
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
     * @param BS_Register_Model_Attendance
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
     * @return BS_Register_Block_Adminhtml_Attendance_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
