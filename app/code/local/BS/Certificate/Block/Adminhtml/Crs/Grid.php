<?php
/**
 * BS_Certificate extension
 * 
 * @category       BS
 * @package        BS_Certificate
 * @copyright      Copyright (c) 2015
 */
/**
 * CRS admin grid block
 *
 * @category    BS
 * @package     BS_Certificate
 * @author Bui Phong
 */
class BS_Certificate_Block_Adminhtml_Crs_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('crsGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Certificate_Block_Adminhtml_Crs_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_certificate/crs')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Certificate_Block_Adminhtml_Crs_Grid
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
            'name',
            array(
                'header'    => Mage::helper('bs_certificate')->__('Name'),
                'align'     => 'left',
                'index'     => 'name',
            )
        );
        

        $this->addColumn(
            'vaeco_id',
            array(
                'header' => Mage::helper('bs_certificate')->__('Staff ID'),
                'index'  => 'vaeco_id',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'authorization_number',
            array(
                'header' => Mage::helper('bs_certificate')->__('Authorization Number'),
                'index'  => 'authorization_number',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'category',
            array(
                'header' => Mage::helper('bs_certificate')->__('Category'),
                'index'  => 'category',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'ac_type',
            array(
                'header' => Mage::helper('bs_certificate')->__('A/C Type'),
                'index'  => 'ac_type',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'engine_type',
            array(
                'header' => Mage::helper('bs_certificate')->__('Engine Type'),
                'index'  => 'engine_type',
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
            'function_title',
            array(
                'header' => Mage::helper('bs_certificate')->__('Function'),
                'index'  => 'function_title',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'limitation',
            array(
                'header' => Mage::helper('bs_certificate')->__('Limitation'),
                'index'  => 'limitation',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'reason',
            array(
                'header' => Mage::helper('bs_certificate')->__('Reason'),
                'index'  => 'reason',
                'type'=> 'text',

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
     * @return BS_Certificate_Block_Adminhtml_Crs_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('crs');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("customer/crs/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("customer/crs/delete");

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
     * @param BS_Certificate_Model_Crs
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
     * @return BS_Certificate_Block_Adminhtml_Crs_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
