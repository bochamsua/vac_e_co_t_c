<?php
/**
 * BS_Report extension
 * 
 * @category       BS
 * @package        BS_Report
 * @copyright      Copyright (c) 2015
 */
/**
 * Manage admin grid block
 *
 * @category    BS
 * @package     BS_Report
 * @author Bui Phong
 */
class BS_Report_Block_Adminhtml_Manage_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('manageGrid');
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
        $collection = Mage::getModel('bs_report/report')
            ->getCollection();

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
       /* $this->addColumn(
            'brief',
            array(
                'header'    => Mage::helper('bs_report')->__('Brief'),
                'align'     => 'left',
                'index'     => 'brief',
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
            'task_qty',
            array(
                'header' => Mage::helper('bs_report')->__('Quantity'),
                'index'  => 'task_qty',
                'type'=> 'text',

            )
        );

        $this->addColumn(
            'user_id',
            array(
                'header' => Mage::helper('bs_report')->__('Employee'),
                'index'  => 'user_id',
                'type'=> 'options',
                'options' => Mage::helper('bs_report')->convertOptions(
                    Mage::getModel('bs_report/report_attribute_source_users')->getAllOptions(false)
                )

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
        $this->addColumn(
            'rate_one',
            array(
                'header' => Mage::helper('bs_report')->__('Quantity'),
                'index'  => 'rate_one',
                'type'  => 'options',
                'options' => Mage::helper('bs_report')->convertOptions(
                    Mage::getModel('bs_report/report_attribute_source_rateone')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'rate_two',
            array(
                'header' => Mage::helper('bs_report')->__('Quality'),
                'index'  => 'rate_two',
                'type'  => 'options',
                'options' => Mage::helper('bs_report')->convertOptions(
                    Mage::getModel('bs_report/report_attribute_source_ratetwo')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'rate_three',
            array(
                'header' => Mage::helper('bs_report')->__('Attitude'),
                'index'  => 'rate_three',
                'type'  => 'options',
                'options' => Mage::helper('bs_report')->convertOptions(
                    Mage::getModel('bs_report/report_attribute_source_ratethree')->getAllOptions(false)
                )

            )
        );


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
                        'url'     => array('base'=> '*/report_report/edit'),
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
//            $this->getMassactionBlock()->addItem(
//                'status',
//                array(
//                    'label'      => Mage::helper('bs_report')->__('Change status'),
//                    'url'        => $this->getUrl('*/report_report/massStatus', array('_current'=>true, 'backto'=>'manage')),
//                    'additional' => array(
//                        'status' => array(
//                            'name'   => 'status',
//                            'type'   => 'select',
//                            'class'  => 'required-entry',
//                            'label'  => Mage::helper('bs_report')->__('Status'),
//                            'values' => array(
//                                '1' => Mage::helper('bs_report')->__('Enabled'),
//                                '0' => Mage::helper('bs_report')->__('Disabled'),
//                            )
//                        )
//                    )
//                )
//            );

            $this->getMassactionBlock()->addItem(
                'rate_one',
                array(
                    'label'      => Mage::helper('bs_report')->__('Change khối lượng công việc'),
                    'url'        => $this->getUrl('*/report_report/massRateOne', array('_current'=>true, 'backto'=>'manage')),
                    'additional' => array(
                        'flag_rate_one' => array(
                            'name'   => 'flag_rate_one',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_report')->__('Khối lượng công việc'),
                            'values' => Mage::getModel('bs_report/report_attribute_source_rateone')
                                ->getAllOptions(true),

                        )
                    )
                )
            );
            $this->getMassactionBlock()->addItem(
                'rate_two',
                array(
                    'label'      => Mage::helper('bs_report')->__('Change chất lượng công việc'),
                    'url'        => $this->getUrl('*/report_report/massRateTwo', array('_current'=>true, 'backto'=>'manage')),
                    'additional' => array(
                        'flag_rate_two' => array(
                            'name'   => 'flag_rate_two',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_report')->__('Chất lượng công việc'),
                            'values' => Mage::getModel('bs_report/report_attribute_source_ratetwo')
                                ->getAllOptions(true),

                        )
                    )
                )
            );
            $this->getMassactionBlock()->addItem(
                'rate_three',
                array(
                    'label'      => Mage::helper('bs_report')->__('Change ý thức tổ chức kỷ luật'),
                    'url'        => $this->getUrl('*/report_report/massRateThree', array('_current'=>true, 'backto'=>'manage')),
                    'additional' => array(
                        'flag_rate_three' => array(
                            'name'   => 'flag_rate_three',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_report')->__('Ý thức tổ chức kỷ luật'),
                            'values' => Mage::getModel('bs_report/report_attribute_source_ratethree')
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
        return $this->getUrl('*/report_report/edit', array('id' => $row->getId()));
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

    protected function _afterToHtml($html)
    {
        $html .= "<script>
                    if($('manageGrid_filter_tctask_id') != undefined){
                        $('manageGrid_filter_tctask_id').observe('change', function(){

                            manageGridJsObject.doFilter();
                        });
                    }

                    if($('manageGrid_filter_user_id') != undefined){
                        $('manageGrid_filter_user_id').observe('change', function(){

                            manageGridJsObject.doFilter();
                        });
                    }
                    if($('manageGrid_filter_supervisor_id') != undefined){
                        $('manageGrid_filter_supervisor_id').observe('change', function(){

                            manageGridJsObject.doFilter();
                        });
                    }

                </script>";
        return parent::_afterToHtml($html); // TODO: Change the autogenerated stub
    }
}
