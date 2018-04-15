<?php
/**
 * BS_Tc extension
 * 
 * @category       BS
 * @package        BS_Tc
 * @copyright      Copyright (c) 2015
 */
/**
 * Employee admin grid block
 *
 * @category    BS
 * @package     BS_Tc
 * @author Bui Phong
 */
class BS_Tc_Block_Adminhtml_Employee_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('employeeGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Tc_Block_Adminhtml_Employee_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_tc/employee')
            ->getCollection()
            ->addAttributeToSelect('*');
        
        $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
        $store = $this->_getStore();
        $collection->joinAttribute(
            'ename', 
            'bs_tc_employee/ename', 
            'entity_id', 
            null, 
            'inner', 
            $adminStore
        );
        if ($store->getId()) {
            $collection->joinAttribute('
                bs_tc_employee_ename', 
                'bs_tc_employee/ename', 
                'entity_id', 
                null, 
                'inner', 
                $store->getId()
            );
        }

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Tc_Block_Adminhtml_Employee_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_tc')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'ename',
            array(
                'header'    => Mage::helper('bs_tc')->__('Full Name'),
                'align'     => 'left',
                'index'     => 'ename',
            )
        );

        $this->addColumn(
            'eposition',
            array(
                'header'    => Mage::helper('bs_tc')->__('Position'),
                'align'     => 'left',
                'index'     => 'eposition',
            )
        );


        $this->addColumn(
            'edob',
            array(
                'header' => Mage::helper('bs_tc')->__('Birthday'),
                'index'  => 'edob',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'ephone',
            array(
                'header'    => Mage::helper('bs_tc')->__('Mobile Phone'),
                'align'     => 'left',
                'index'     => 'ephone',
            )
        );

        $this->addColumn(
            'ehomephone',
            array(
                'header'    => Mage::helper('bs_tc')->__('Home Phone'),
                'align'     => 'left',
                'index'     => 'ehomephone',
            )
        );

        $this->addColumn(
            'eaddress',
            array(
                'header'    => Mage::helper('bs_tc')->__('Address'),
                'align'     => 'left',
                'index'     => 'eaddress',
            )
        );

        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_tc')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_tc')->__('Enabled'),
                    '0' => Mage::helper('bs_tc')->__('Disabled'),
                )
            )
        );

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_tc')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_tc')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_tc')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_tc')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_tc')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * get the selected store
     *
     * @access protected
     * @return Mage_Core_Model_Store
     * @author Bui Phong
     */
    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_Tc_Block_Adminhtml_Employee_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('employee');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_tc/employee/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_tc/employee/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_tc')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_tc')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_tc')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_tc')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_tc')->__('Enabled'),
                                '0' => Mage::helper('bs_tc')->__('Disabled'),
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
     * @param BS_Tc_Model_Employee
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
}
