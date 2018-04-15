<?php
/**
 * BS_Exam extension
 * 
 * @category       BS
 * @package        BS_Exam
 * @copyright      Copyright (c) 2015
 */
/**
 * Exam admin grid block
 *
 * @category    BS
 * @package     BS_Exam
 * @author Bui Phong
 */
class BS_Exam_Block_Adminhtml_Exam_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('examGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Exam_Block_Adminhtml_Exam_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_exam/exam')
            ->getCollection();

        $collection->getSelect()->joinLeft(array('pro'=>'catalog_product_entity'),'course_id = pro.entity_id','sku');
        $collection->getSelect()->joinLeft(array('room'=>'bs_logistics_classroom'),'room_id = room.entity_id','classroom_name');
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Exam_Block_Adminhtml_Exam_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_exam')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'course_id',
            array(
                'header'    => Mage::helper('bs_exam')->__('Course'),
                'type'      => 'text',
                'renderer'  => 'bs_exam/adminhtml_helper_column_renderer_course',
                'filter_condition_callback' => array($this, '_courseFilter'),

            )
        );

        $this->addColumn(
            'exam_content',
            array(
                'header'    => Mage::helper('bs_exam')->__('Exam Content'),
                'align'     => 'left',
                'index'     => 'exam_content',
            )
        );
        

        $this->addColumn(
            'exam_date',
            array(
                'header' => Mage::helper('bs_exam')->__('Exam Date'),
                'index'  => 'exam_date',
                'type'=> 'date',

            )
        );

        $customers = Mage::getModel('customer/customer')->getCollection()->addFieldToFilter('is_examiner', 1);
        $options = array();
        if(count($customers)){
            foreach ($customers as $cus) {
                $customer = Mage::getModel('customer/customer')->load($cus->getId());
                $options[$customer->getId()] = $customer->getName();

            }


        }

        $this->addColumn(
            'exam_qty',
            array(
                'header'    => Mage::helper('bs_exam')->__('Total Questions'),
                'index'     => 'exam_qty',
            )
        );

        $this->addColumn(
            'exam_duration',
            array(
                'header'    => Mage::helper('bs_exam')->__('Total Minutes'),
                'index'     => 'exam_duration',
            )
        );
        $this->addColumn(
            'start_time',
            array(
                'header'    => Mage::helper('bs_exam')->__('Start Time'),
                'index'     => 'start_time',
                'note'      => Mage::helper('bs_exam')->__('For example, 9AM or 9 giờ'),
            )
        );

        $this->addColumn(
            'examiners',
            array(
                'header'    => Mage::helper('bs_exam')->__('Examiner'),
                'type'      => 'options',
                'options'    => $options,
                'renderer'  => 'bs_exam/adminhtml_helper_column_renderer_examiner',
                'filter_condition_callback' => array($this, '_examinerFilter'),

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
            'exam_times',
            array(
                'header'  => Mage::helper('bs_exam')->__('Exam Times?'),
                'index'   => 'exam_times',
                'type'    => 'options',
                'options' => array(
                    '0' => Mage::helper('bs_exam')->__('1st Exam'),
                    '1' => Mage::helper('bs_exam')->__('Retaking Exam'),
                )
            )
        );
        $this->addColumn(
            'exam_note',
            array(
                'header' => Mage::helper('bs_exam')->__('Note'),
                'index'  => 'exam_note',
                'type'=> 'text',

            )
        );

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_exam')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_exam')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_exam')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_exam')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_exam')->__('XML'));
        return parent::_prepareColumns();
    }

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

    protected function _examinerFilter($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addFieldToFilter('examiners', array('finset' => $value));
    }

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
     * @return BS_Exam_Block_Adminhtml_Exam_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('exam');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_exam/exam/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_exam/exam/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_exam')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_exam')->__('Are you sure?')
                )
            );
        }

        $this->getMassactionBlock()->addItem(
            'generate',
            array(
                'label'      => Mage::helper('bs_exam')->__('Generate Dispatch'),
                'url'        => $this->getUrl('*/*/massGenerate', array('_current'=>true)),

            )
        );

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_exam')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_exam')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_exam')->__('Enabled'),
                                '0' => Mage::helper('bs_exam')->__('Disabled'),
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
     * @param BS_Exam_Model_Exam
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
     * @return BS_Exam_Block_Adminhtml_Exam_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
