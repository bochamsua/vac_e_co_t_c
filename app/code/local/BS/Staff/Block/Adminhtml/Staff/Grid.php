<?php
/**
 * BS_Staff extension
 * 
 * @category       BS
 * @package        BS_Staff
 * @copyright      Copyright (c) 2015
 */
/**
 * Staff admin grid block
 *
 * @category    BS
 * @package     BS_Staff
 * @author Bui Phong
 */
class BS_Staff_Block_Adminhtml_Staff_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('staffGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Staff_Block_Adminhtml_Staff_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_staff/staff')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Staff_Block_Adminhtml_Staff_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_staff')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'username',
            array(
                'header'    => Mage::helper('bs_staff')->__('VAECO Username'),
                'align'     => 'left',
                'index'     => 'username',
            )
        );
        

        $this->addColumn(
            'vaeco_id',
            array(
                'header' => Mage::helper('bs_staff')->__('VAECO ID'),
                'index'  => 'vaeco_id',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'fullname',
            array(
                'header' => Mage::helper('bs_staff')->__('Full Name'),
                'index'  => 'fullname',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'phone',
            array(
                'header' => Mage::helper('bs_staff')->__('Phone'),
                'index'  => 'phone',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'position',
            array(
                'header' => Mage::helper('bs_staff')->__('Position'),
                'index'  => 'position',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'division',
            array(
                'header' => Mage::helper('bs_staff')->__('Division'),
                'index'  => 'division',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'department',
            array(
                'header' => Mage::helper('bs_staff')->__('Department'),
                'index'  => 'department',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_staff')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_staff')->__('Enabled'),
                    '0' => Mage::helper('bs_staff')->__('Disabled'),
                )
            )
        );

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_staff')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_staff')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_staff')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_staff')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_staff')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_Staff_Block_Adminhtml_Staff_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('staff');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_staff/staff/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_staff/staff/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_staff')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_staff')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_staff')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_staff')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_staff')->__('Enabled'),
                                '0' => Mage::helper('bs_staff')->__('Disabled'),
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
     * @param BS_Staff_Model_Staff
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
     * @return BS_Staff_Block_Adminhtml_Staff_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
