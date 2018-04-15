<?php
/**
 * BS_Certificate extension
 * 
 * @category       BS
 * @package        BS_Certificate
 * @copyright      Copyright (c) 2015
 */
/**
 * Certificate admin grid block
 *
 * @category    BS
 * @package     BS_Certificate
 * @author Bui Phong
 */
class BS_Certificate_Block_Adminhtml_Certificate_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('certificateGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Certificate_Block_Adminhtml_Certificate_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_certificate/certificate')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Certificate_Block_Adminhtml_Certificate_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_certificate')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'staff_name',
            array(
                'header'    => Mage::helper('bs_certificate')->__('Staff Name'),
                'align'     => 'left',
                'index'     => 'staff_name',
            )
        );
        

        $this->addColumn(
            'vaeco_id',
            array(
                'header' => Mage::helper('bs_certificate')->__('VAECO ID'),
                'index'  => 'vaeco_id',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'cert_type',
            array(
                'header' => Mage::helper('bs_certificate')->__('Cert Type'),
                'index'  => 'cert_type',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'description',
            array(
                'header' => Mage::helper('bs_certificate')->__('Description'),
                'index'  => 'description',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'apply_for',
            array(
                'header' => Mage::helper('bs_certificate')->__('Apply For'),
                'index'  => 'apply_for',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'cert_no',
            array(
                'header' => Mage::helper('bs_certificate')->__('Cert Number'),
                'index'  => 'cert_no',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'issue_date',
            array(
                'header' => Mage::helper('bs_certificate')->__('Issue Date'),
                'index'  => 'issue_date',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'expire_date',
            array(
                'header' => Mage::helper('bs_certificate')->__('Expire Date'),
                'index'  => 'expire_date',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_certificate')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_certificate')->__('Enabled'),
                    '0' => Mage::helper('bs_certificate')->__('Disabled'),
                )
            )
        );

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_certificate')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_certificate')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_certificate')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_certificate')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_certificate')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_Certificate_Block_Adminhtml_Certificate_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('certificate');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("customer/certificate/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("customer/certificate/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_certificate')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_certificate')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_certificate')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_certificate')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_certificate')->__('Enabled'),
                                '0' => Mage::helper('bs_certificate')->__('Disabled'),
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
     * @param BS_Certificate_Model_Certificate
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
     * @return BS_Certificate_Block_Adminhtml_Certificate_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
