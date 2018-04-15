<?php
/**
 * BS_Report extension
 * 
 * @category       BS
 * @package        BS_Report
 * @copyright      Copyright (c) 2015
 */
/**
 * Individual Report admin grid block
 *
 * @category    BS
 * @package     BS_Report
 * @author Bui Phong
 */
class BS_Report_Block_Adminhtml_Report_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('reportGrid');
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Report_Block_Adminhtml_Report_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $currentUser = Mage::getSingleton('admin/session')->getUser();

        $collection = Mage::getModel('bs_report/report')
            ->getCollection();

        $collection->addFieldToFilter('user_id', $currentUser->getUserId());
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Report_Block_Adminhtml_Report_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        /*$this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_report')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );*/
        $this->addColumn(
            'tctask_id',
            array(
                'header'    => Mage::helper('bs_report')->__('Task'),
                'index'     => 'tctask_id',
                'type'=> 'options',
                'options' => Mage::helper('bs_report')->convertOptions(
                    Mage::getModel('bs_report/report_attribute_source_tasks')->getAllOptions(false, false, false)
                )
            )
        );

        $this->addColumn(
            'detail',
            array(
                'header' => Mage::helper('bs_report')->__('Detail'),
                'index'  => 'detail',
                'type'=> 'text',

            )
        );
        

        $this->addColumn(
            'supervisor_id',
            array(
                'header' => Mage::helper('bs_report')->__('Supervisor'),
                'index'  => 'supervisor_id',
                'type'=> 'options',
                'options' => Mage::helper('bs_report')->convertOptions(
                    Mage::getModel('bs_report/report_attribute_source_supervisor')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'task_qty',
            array(
                'header' => Mage::helper('bs_report')->__('Quantity'),
                'index'  => 'task_qty',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'task_time',
            array(
                'header' => Mage::helper('bs_report')->__('Doing time'),
                'index'  => 'task_time',
                'type'=> 'number',

            )
        );
        $this->addColumn(
            'task_status',
            array(
                'header' => Mage::helper('bs_report')->__('Status'),
                'index'  => 'task_status',
                'type'  => 'options',
                'options' => Mage::helper('bs_report')->convertOptions(
                    Mage::getModel('bs_report/report_attribute_source_taskstatus')->getAllOptions(false)
                )

            )
        );

        /*$this->addColumn(
            'complete',
            array(
                'header' => Mage::helper('bs_report')->__('Complete (%)'),
                'index'  => 'complete',
                'type'  => 'options',
                'options' => Mage::helper('bs_report')->convertOptions(
                    Mage::getModel('bs_report/report_attribute_source_complete')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'expected_date',
            array(
                'header' => Mage::helper('bs_report')->__('Expected Complete Date'),
                'index'  => 'expected_date',
                'type'=> 'date',

            )
        );*/

        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('bs_report')->__('Created Date'),
                'index'  => 'created_at',
                'type'=> 'date',

            )
        );
        /*$this->addColumn(
            'updated_at',
            array(
                'header' => Mage::helper('bs_report')->__('Updated Date'),
                'index'  => 'updated_at',
                'type'=> 'datetime',

            )
        );*/

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_report')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_report')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_report')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_report')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_report')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_Report_Block_Adminhtml_Report_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('report');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_report/report/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_report/report/delete");

        if($isAllowedDelete){
//            $this->getMassactionBlock()->addItem(
//                'delete',
//                array(
//                    'label'=> Mage::helper('bs_report')->__('Delete'),
//                    'url'  => $this->getUrl('*/*/massDelete'),
//                    'confirm'  => Mage::helper('bs_report')->__('Are you sure?')
//                )
//            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'task_status',
                array(
                    'label'      => Mage::helper('bs_report')->__('Change Status'),
                    'url'        => $this->getUrl('*/*/massTaskStatus', array('_current'=>true)),
                    'additional' => array(
                        'flag_task_status' => array(
                            'name'   => 'flag_task_status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_report')->__('Status'),
                            'values' => Mage::getModel('bs_report/report_attribute_source_taskstatus')
                                ->getAllOptions(true),

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
     * @param BS_Report_Model_Report
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
     * @return BS_Report_Block_Adminhtml_Report_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
