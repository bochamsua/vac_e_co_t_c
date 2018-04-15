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
class BS_Report_Block_Adminhtml_Supervisor_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('supervisorGrid');
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

        $userId = $currentUser->getUserId();
        if($userId != 1){
            $collection->addFieldToFilter('supervisor_id', $userId);
        }


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
            'task_qty',
            array(
                'header' => Mage::helper('bs_report')->__('Quantity'),
                'index'  => 'task_qty',
                'type'=> 'text',

            )
        );

        $this->addColumn(
            'rate_one',
            array(
                'header' => Mage::helper('bs_report')->__('Khối lượng công việc'),
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
                'header' => Mage::helper('bs_report')->__('Chất lượng công việc'),
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
                'header' => Mage::helper('bs_report')->__('Ý thức tổ chức kỷ luật'),
                'index'  => 'rate_three',
                'type'  => 'options',
                'options' => Mage::helper('bs_report')->convertOptions(
                    Mage::getModel('bs_report/report_attribute_source_ratethree')->getAllOptions(false)
                )

            )
        );

        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('bs_report')->__('Created Date'),
                'index'  => 'created_at',
                'type'=> 'date',

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
            $this->getMassactionBlock()->addItem(
                'update',
                array(
                    'label'=> Mage::helper('bs_report')->__('Rate'),
                    'url'  => $this->getUrl('*/report_report/new'),
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
                    if($('supervisorGrid_filter_tctask_id') != undefined){
                        $('supervisorGrid_filter_tctask_id').observe('change', function(){

                            supervisorGridJsObject.doFilter();
                        });
                    }

                    if($('supervisorGrid_filter_user_id') != undefined){
                        $('supervisorGrid_filter_user_id').observe('change', function(){

                            supervisorGridJsObject.doFilter();
                        });
                    }




                </script>";
        return parent::_afterToHtml($html); // TODO: Change the autogenerated stub
    }
}
