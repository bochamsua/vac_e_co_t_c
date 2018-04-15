<?php
/**
 * BS_InstructorApproval extension
 * 
 * @category       BS
 * @package        BS_InstructorApproval
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Approval admin grid block
 *
 * @category    BS
 * @package     BS_InstructorApproval
 * @author Bui Phong
 */
class BS_InstructorApproval_Block_Adminhtml_Iapproval_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('iapprovalGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_InstructorApproval_Block_Adminhtml_Iapproval_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_instructorapproval/iapproval')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_InstructorApproval_Block_Adminhtml_Iapproval_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_instructorapproval')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'iapproval_title',
            array(
                'header'    => Mage::helper('bs_instructorapproval')->__('Approval Title'),
                'align'     => 'left',
                'index'     => 'iapproval_title',
            )
        );
        

        $this->addColumn(
            'iapproval_function',
            array(
                'header' => Mage::helper('bs_instructorapproval')->__('Function'),
                'index'  => 'iapproval_function',
                'type'=> 'text',

            )
        );

        $this->addColumn(
            'vaeco_ids',
            array(
                'header' => Mage::helper('bs_instructorapproval')->__('Instructor'),
                'index'  => 'vaeco_ids',
                'renderer'  => 'bs_instructorapproval/adminhtml_helper_column_renderer_instructor',
                'type'=> 'text',
                //'filter'    => false

            )
        );

        $this->addColumn(
            'type',
            array(
                'header'  => Mage::helper('bs_instructorapproval')->__('Type'),
                'index'   => 'type',
                'type'    => 'options',
                'options' => array(
                    0 => Mage::helper('bs_instructorapproval')->__('Initial'),
                    1 => Mage::helper('bs_instructorapproval')->__('Supplemental'),
                )
            )
        );

        /*$this->addColumn(
            'iapproval_compliance_other',
            array(
                'header' => Mage::helper('bs_instructorapproval')->__('Compliance With Other'),
                'index'  => 'iapproval_compliance_other',
                'type'=> 'text',

            )
        );*/
        $this->addColumn(
            'iapproval_date',
            array(
                'header' => Mage::helper('bs_instructorapproval')->__('Prepared Date'),
                'index'  => 'iapproval_date',
                'type'=> 'date',

            )
        );

        $this->addColumn(
            'include_keyword',
            array(
                'header' => Mage::helper('bs_instructorapproval')->__('Incl. Keyword'),
                'index'  => 'include_keyword',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'exclude_keyword',
            array(
                'header' => Mage::helper('bs_instructorapproval')->__('Excl. Keyword'),
                'index'  => 'exclude_keyword',
                'type'=> 'text',

            )
        );

        $this->addColumn(
            'note',
            array(
                'header' => Mage::helper('bs_instructorapproval')->__('Note'),
                'index'  => 'note',
                'type'=> 'text',

            )
        );



        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_instructorapproval')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_instructorapproval')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_instructorapproval')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_instructorapproval')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_instructorapproval')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_InstructorApproval_Block_Adminhtml_Iapproval_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('iapproval');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/bs_instructor/iapproval/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/bs_instructor/iapproval/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_instructorapproval')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_instructorapproval')->__('Are you sure?')
                )
            );
        }
        $this->getMassactionBlock()->addItem(
            'generate',
            array(
                'label'      => Mage::helper('bs_instructorapproval')->__('Generate Files'),
                'url'        => $this->getUrl('*/*/massGenerate', array('_current'=>true)),
                'additional' => array(
                    'compress' => array(
                        'name'   => 'compress',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_instructorapproval')->__('Compress?'),
                        'values' => array(
                            '1' => Mage::helper('bs_instructorapproval')->__('Yes'),
                            '0' => Mage::helper('bs_instructorapproval')->__('No'),
                        )
                    )
                )

            )
        );

        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param BS_InstructorApproval_Model_Iapproval
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
     * @return BS_InstructorApproval_Block_Adminhtml_Iapproval_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
