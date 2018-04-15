<?php
/**
 * BS_InstructorInfo extension
 * 
 * @category       BS
 * @package        BS_InstructorInfo
 * @copyright      Copyright (c) 2015
 */
/**
 * Other Info admin grid block
 *
 * @category    BS
 * @package     BS_InstructorInfo
 * @author Bui Phong
 */
class BS_InstructorInfo_Block_Adminhtml_Otherinfo_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('otherinfoGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_InstructorInfo_Block_Adminhtml_Otherinfo_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_instructorinfo/otherinfo')
            ->getCollection();
        $collection->getSelect()->joinLeft(array('ins'=>'bs_instructor_instructor_varchar'),'instructor_id = ins.entity_id AND ins.attribute_id = 270','ins.value');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_InstructorInfo_Block_Adminhtml_Otherinfo_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_instructorinfo')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'instructor_id',
            array(
                'header' => Mage::helper('bs_instructorinfo')->__('Instructor'),
                'type'=> 'text',
                'renderer'  => 'bs_register/adminhtml_helper_column_renderer_instructor',
                'filter_condition_callback' => array($this, '_instructorFilter'),

            )
        );
        $this->addColumn(
            'title',
            array(
                'header'    => Mage::helper('bs_instructorinfo')->__('Training Description'),
                'align'     => 'left',
                'index'     => 'title',
            )
        );



        $this->addColumn(
            'country',
            array(
                'header' => Mage::helper('bs_instructorinfo')->__('Country'),
                'index'  => 'country',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'start_date',
            array(
                'header' => Mage::helper('bs_instructorinfo')->__('Start Date'),
                'index'  => 'start_date',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'end_date',
            array(
                'header' => Mage::helper('bs_instructorinfo')->__('End Date'),
                'index'  => 'end_date',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'cert_info',
            array(
                'header' => Mage::helper('bs_instructorinfo')->__('Cert.#/Evidence'),
                'index'  => 'cert_info',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_instructorinfo')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_instructorinfo')->__('Enabled'),
                    '0' => Mage::helper('bs_instructorinfo')->__('Disabled'),
                )
            )
        );

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_instructorinfo')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_instructorinfo')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_instructorinfo')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_instructorinfo')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_instructorinfo')->__('XML'));
        return parent::_prepareColumns();
    }
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
     * prepare mass action
     *
     * @access protected
     * @return BS_InstructorInfo_Block_Adminhtml_Otherinfo_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('otherinfo');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/bs_instructor/otherinfo/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/bs_instructor/otherinfo/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_instructorinfo')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_instructorinfo')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){

            $this->getMassactionBlock()->addItem(
                'use',
                array(
                    'label'=> Mage::helper('bs_instructorinfo')->__('Use for Approval'),
                    'url'  => $this->getUrl('*/*/massUse'),
                )
            );

            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_instructorinfo')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_instructorinfo')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_instructorinfo')->__('Enabled'),
                                '0' => Mage::helper('bs_instructorinfo')->__('Disabled'),
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
     * @param BS_InstructorInfo_Model_Otherinfo
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
     * @return BS_InstructorInfo_Block_Adminhtml_Otherinfo_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
