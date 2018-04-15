<?php
/**
 * BS_Report extension
 * 
 * @category       BS
 * @package        BS_Report
 * @copyright      Copyright (c) 2015
 */
/**
 * TC Task admin grid block
 *
 * @category    BS
 * @package     BS_Report
 * @author Bui Phong
 */
class BS_Report_Block_Adminhtml_Tctask_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('tctaskGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Report_Block_Adminhtml_Tctask_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_report/tctask')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Report_Block_Adminhtml_Tctask_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_report')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'tctask_name',
            array(
                'header'    => Mage::helper('bs_report')->__('Task Name'),
                'align'     => 'left',
                'index'     => 'tctask_name',
            )
        );
        

        $this->addColumn(
            'tctask_code',
            array(
                'header' => Mage::helper('bs_report')->__('Task Code'),
                'index'  => 'tctask_code',
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
            'southern_supervisor_id',
            array(
                'header' => Mage::helper('bs_report')->__('Southern Supervisor'),
                'index'  => 'southern_supervisor_id',
                'type'=> 'options',
                'options' => Mage::helper('bs_report')->convertOptions(
                    Mage::getModel('bs_report/report_attribute_source_southernsupervisor')->getAllOptions(false)
                )

            )
        );

        /*$this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_report')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_report')->__('Enabled'),
                    '0' => Mage::helper('bs_report')->__('Disabled'),
                )
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
     * @return BS_Report_Block_Adminhtml_Tctask_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('tctask');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_report/tctask/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_report/tctask/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_report')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_report')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){


            $this->getMassactionBlock()->addItem(
                'supervisor',
                array(
                    'label'      => Mage::helper('bs_report')->__('Change Supervisor'),
                    'url'        => $this->getUrl('*/*/massSupervisor', array('_current'=>true)),
                    'additional' => array(
                        'supervisor' => array(
                            'name'   => 'supervisor',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_report')->__('Supervisor'),
                            'values' => Mage::getModel('bs_report/report_attribute_source_supervisor')->getAllOptions(false)
                        )
                    )
                )
            );

            $this->getMassactionBlock()->addItem(
                'southern_supervisor',
                array(
                    'label'      => Mage::helper('bs_report')->__('Change Southern Supervisor'),
                    'url'        => $this->getUrl('*/*/massSouthernSupervisor', array('_current'=>true)),
                    'additional' => array(
                        'southern_supervisor' => array(
                            'name'   => 'southern_supervisor',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_report')->__('Supervisor'),
                            'values' => Mage::getModel('bs_report/report_attribute_source_southernsupervisor')->getAllOptions(false)
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
     * @param BS_Report_Model_Tctask
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
     * @return BS_Report_Block_Adminhtml_Tctask_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
