<?php
/**
 * BS_Tc extension
 * 
 * @category       BS
 * @package        BS_Tc
 * @copyright      Copyright (c) 2015
 */
/**
 * Family admin grid block
 *
 * @category    BS
 * @package     BS_Tc
 * @author Bui Phong
 */
class BS_Tc_Block_Adminhtml_Family_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('familyGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Tc_Block_Adminhtml_Family_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_tc/family')
            ->getCollection()
            ->addAttributeToSelect('*');
        
        $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
        $store = $this->_getStore();
        $collection->joinAttribute(
            'fname', 
            'bs_tc_family/fname', 
            'entity_id', 
            null, 
            'inner', 
            $adminStore
        );
        if ($store->getId()) {
            $collection->joinAttribute('
                bs_tc_family_fname', 
                'bs_tc_family/fname', 
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
     * @return BS_Tc_Block_Adminhtml_Family_Grid
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
            'employee_id',
            array(
                'header'    => Mage::helper('bs_tc')->__('Employee'),
                'index'     => 'employee_id',
                'type'      => 'options',
                'options'   => Mage::getResourceModel('bs_tc/employee_collection')
                    ->addAttributeToSelect('ename')->toOptionHash(),
                'renderer'  => 'bs_tc/adminhtml_helper_column_renderer_parent',
                'params'    => array(
                    'id'    => 'getEmployeeId'
                ),
                'base_link' => 'adminhtml/tc_employee/edit'
            )
        );
        $this->addColumn(
            'fname',
            array(
                'header'    => Mage::helper('bs_tc')->__('Full Name'),
                'align'     => 'left',
                'index'     => 'fname',
            )
        );

        $this->addColumn(
            'frelation',
            array(
                'header' => Mage::helper('bs_tc')->__('Relationship'),
                'index'  => 'frelation',
                'type'  => 'options',
                'options' => Mage::helper('bs_tc')->convertOptions(
                    Mage::getModel('eav/config')->getAttribute('bs_tc_family', 'frelation')->getSource()->getAllOptions(false)
                )

            )
        );

        $this->addColumn(
            'fdob',
            array(
                'header'    => Mage::helper('bs_tc')->__('Birthday'),
                'align'     => 'left',
                'index'     => 'fdob',
                'type'      => 'date',
                //'format'  => 'dd/MM/YYYY',

            )
        );

        $this->addColumn(
            'faddress',
            array(
                'header'    => Mage::helper('bs_tc')->__('Address'),
                'align'     => 'left',
                'index'     => 'faddress',
                'note'      => 'Leave empty if live in the same house'
            )
        );

        $this->addColumn(
            'fjob',
            array(
                'header'    => Mage::helper('bs_tc')->__('Current Job'),
                'align'     => 'left',
                'index'     => 'fjob',
            )
        );

        $this->addColumn(
            'fcompany',
            array(
                'header'    => Mage::helper('bs_tc')->__('Company/School'),
                'align'     => 'left',
                'index'     => 'fcompany',
            )
        );


        /*$this->addColumn(
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
        );*/

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
     * @return BS_Tc_Block_Adminhtml_Family_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('family');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_tc/family/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_tc/family/delete");

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




        $values = Mage::getResourceModel('bs_tc/employee_collection')->toOptionHash();
        $values = array_reverse($values, true);
        $values[''] = '';
        $values = array_reverse($values, true);
        $this->getMassactionBlock()->addItem(
            'employee_id',
            array(
                'label'      => Mage::helper('bs_tc')->__('Change Employee'),
                'url'        => $this->getUrl('*/*/massEmployeeId', array('_current'=>true)),
                'additional' => array(
                    'flag_employee_id' => array(
                        'name'   => 'flag_employee_id',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_tc')->__('Employee'),
                        'values' => $values
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
     * @param BS_Tc_Model_Family
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
