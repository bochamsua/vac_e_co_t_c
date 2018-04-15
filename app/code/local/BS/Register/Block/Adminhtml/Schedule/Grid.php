<?php
/**
 * BS_Register extension
 * 
 * @category       BS
 * @package        BS_Register
 * @copyright      Copyright (c) 2015
 */
/**
 * Course Schedule admin grid block
 *
 * @category    BS
 * @package     BS_Register
 * @author Bui Phong
 */
class BS_Register_Block_Adminhtml_Schedule_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('scheduleGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Register_Block_Adminhtml_Schedule_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_register/schedule')
            ->getCollection();

        $collection->getSelect()->joinLeft(array('pro'=>'catalog_product_entity'),'course_id = pro.entity_id','sku');
        $collection->getSelect()->joinLeft(array('sub'=>'bs_subject_subject'),'subject_id = sub.entity_id','subject_name');
        $collection->getSelect()->joinLeft(array('ins'=>'bs_instructor_instructor_varchar'),'instructor_id = ins.entity_id AND ins.attribute_id = 270','ins.value');
        $collection->getSelect()->joinLeft(array('room'=>'bs_logistics_classroom'),'room_id = room.entity_id','classroom_name');



        //print_r($collection->getSelect()->__toString());
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Register_Block_Adminhtml_Schedule_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_register')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number',
                'filter_index'  => 'main_table.entity_id'
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
            'subject_id',
            array(
                'header'    => Mage::helper('bs_register')->__('Subject'),
                'type'      => 'text',
                'renderer'  => 'bs_register/adminhtml_helper_column_renderer_subject',
                'filter_condition_callback' => array($this, '_subjectFilter'),

            )
        );

        $this->addColumn(
            'instructor_id',
            array(
                'header'    => Mage::helper('bs_register')->__('Instructor'),
                'type'      => 'text',
                'renderer'  => 'bs_register/adminhtml_helper_column_renderer_instructor',
                'filter_condition_callback' => array($this, '_instructorFilter'),

            )
        );

        $this->addColumn(
            'room_id',
            array(
                'header'    => Mage::helper('bs_register')->__('Room'),
                'type'      => 'text',
                'renderer'  => 'bs_register/adminhtml_helper_column_renderer_room',
                'filter_condition_callback' => array($this, '_roomFilter'),

            )
        );



        $this->addColumn(
            'schedule_start_date',
            array(
                'header' => Mage::helper('bs_register')->__('Start Date'),
                'index'  => 'schedule_start_date',
                'type'=> 'date',

            )
        );

        $this->addColumn(
            'schedule_start_time',
            array(
                'header' => Mage::helper('bs_register')->__('Start Time'),
                'type'=> 'text',
                'renderer'  => 'bs_register/adminhtml_helper_column_renderer_starttime',
                'filter'=>false

            )
        );



        $this->addColumn(
            'schedule_finish_date',
            array(
                'header' => Mage::helper('bs_register')->__('Finish Date'),
                'index'  => 'schedule_finish_date',
                'type'=> 'date',

            )
        );

        $this->addColumn(
            'schedule_finish_time',
            array(
                'header' => Mage::helper('bs_register')->__('Finish Time'),
                'type'=> 'text',
                'renderer'  => 'bs_register/adminhtml_helper_column_renderer_finishtime',
                'filter'=>false

            )
        );

        $this->addColumn(
            'schedule_hours',
            array(
                'header'    => Mage::helper('bs_register')->__('Total Hours'),
                'align'     => 'left',
                'index'     => 'schedule_hours',
            )
        );

        $this->addColumn(
            'schedule_order',
            array(
                'header'    => Mage::helper('bs_register')->__('Sort Order'),
                'align'     => 'left',
                'index'     => 'schedule_order',
            )
        );

        $this->addColumn(
            'schedule_note',
            array(
                'header'    => Mage::helper('bs_register')->__('Note'),
                'align'     => 'left',
                'index'     => 'schedule_note',
            )
        );
        /*
        $this->addColumn(
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
     * subject callback filter
     *
     * @access protected
     * @return BS_Register_Block_Adminhtml_Schedule_Grid
     * @author Bui Phong
     */

    protected function _subjectFilter($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }

        $this->getCollection()->getSelect()->where(
            "subject_name LIKE ?"
            , "%$value%");


        return $this;
    }

    /**
     * instructor callback filter
     *
     * @access protected
     * @return BS_Register_Block_Adminhtml_Schedule_Grid
     * @author Bui Phong
     */

    protected function _instructorFilter($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }

        $this->getCollection()->getSelect()->where(
            "ins.value LIKE ?"
            , "%$value%");


        return $this;
    }

    /**
     * room callback filter
     *
     * @access protected
     * @return BS_Register_Block_Adminhtml_Schedule_Grid
     * @author Bui Phong
     */

    protected function _roomFilter($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }

        $this->getCollection()->getSelect()->where(
            "classroom_name LIKE ?"
            , "%$value%");


        return $this;
    }




    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_Register_Block_Adminhtml_Schedule_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('schedule');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("catalog/bs_register/schedule/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("catalog/bs_register/schedule/delete");

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

        }
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param BS_Register_Model_Schedule
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
     * @return BS_Register_Block_Adminhtml_Schedule_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
