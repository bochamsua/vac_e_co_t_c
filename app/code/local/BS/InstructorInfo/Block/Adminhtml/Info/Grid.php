<?php
/**
 * BS_InstructorInfo extension
 * 
 * @category       BS
 * @package        BS_InstructorInfo
 * @copyright      Copyright (c) 2015
 */
/**
 * Info admin grid block
 *
 * @category    BS
 * @package     BS_InstructorInfo
 * @author Bui Phong
 */
class BS_InstructorInfo_Block_Adminhtml_Info_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('infoGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_InstructorInfo_Block_Adminhtml_Info_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_instructorinfo/info')
            ->getCollection();

        $collection->getSelect()->joinLeft(array('ins'=>'bs_instructor_instructor_varchar'),'instructor_id = ins.entity_id AND ins.attribute_id = 270','ins.value');
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_InstructorInfo_Block_Adminhtml_Info_Grid
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
            'compliance_with',
            array(
                'header' => Mage::helper('bs_instructorinfo')->__('Compliance With'),
                'index'  => 'compliance_with',
                'type'  => 'options',
                'options' => Mage::helper('bs_instructorinfo')->convertOptions(
                    Mage::getModel('bs_instructorinfo/info_attribute_source_compliancewith')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'approved_course',
            array(
                'header'    => Mage::helper('bs_instructorinfo')->__('Approved Course'),
                'align'     => 'left',
                'index'     => 'approved_course',
            )
        );
        


        $this->addColumn(
            'approved_function',
            array(
                'header' => Mage::helper('bs_instructorinfo')->__('Approved Function'),
                'index'  => 'approved_function',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'approved_doc',
            array(
                'header' => Mage::helper('bs_instructorinfo')->__('Approved Doc'),
                'index'  => 'approved_doc',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'approved_date',
            array(
                'header' => Mage::helper('bs_instructorinfo')->__('Approved Date'),
                'index'  => 'approved_date',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'expire_date',
            array(
                'header' => Mage::helper('bs_instructorinfo')->__('Expire Date'),
                'index'  => 'expire_date',
                'type'=> 'date',

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

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_InstructorInfo_Block_Adminhtml_Info_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('info');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/bs_instructor/info/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/bs_instructor/info/delete");

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




        $this->getMassactionBlock()->addItem(
            'compliance_with',
            array(
                'label'      => Mage::helper('bs_instructorinfo')->__('Change Compliance With'),
                'url'        => $this->getUrl('*/*/massComplianceWith', array('_current'=>true)),
                'additional' => array(
                    'flag_compliance_with' => array(
                        'name'   => 'flag_compliance_with',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_instructorinfo')->__('Compliance With'),
                        'values' => Mage::getModel('bs_instructorinfo/info_attribute_source_compliancewith')
                            ->getAllOptions(true),

                    )
                )
            )
        );
        }
        return $this;
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
     * get the row url
     *
     * @access public
     * @param BS_InstructorInfo_Model_Info
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
     * @return BS_InstructorInfo_Block_Adminhtml_Info_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
