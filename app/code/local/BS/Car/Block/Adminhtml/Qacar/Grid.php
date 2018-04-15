<?php
/**
 * BS_Car extension
 * 
 * @category       BS
 * @package        BS_Car
 * @copyright      Copyright (c) 2016
 */
/**
 * QA Car admin grid block
 *
 * @category    BS
 * @package     BS_Car
 * @author Bui Phong
 */
class BS_Car_Block_Adminhtml_Qacar_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('qacarGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Car_Block_Adminhtml_Qacar_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_car/qacar')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Car_Block_Adminhtml_Qacar_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_car')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'car_no',
            array(
                'header'    => Mage::helper('bs_car')->__('No'),
                'align'     => 'left',
                'index'     => 'car_no',
            )
        );
        

        $this->addColumn(
            'car_date',
            array(
                'header' => Mage::helper('bs_car')->__('Date'),
                'index'  => 'car_date',
                'type'=> 'date',

            )
        );
        /*$this->addColumn(
            'sendto',
            array(
                'header' => Mage::helper('bs_car')->__('Send To'),
                'index'  => 'sendto',
                'type'=> 'text',

            )
        );*/
        /*$this->addColumn(
            'auditor',
            array(
                'header' => Mage::helper('bs_car')->__('Auditor'),
                'index'  => 'auditor',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'auditee',
            array(
                'header' => Mage::helper('bs_car')->__('Auditee representative'),
                'index'  => 'auditee',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'ref',
            array(
                'header' => Mage::helper('bs_car')->__('Audit Report Ref'),
                'index'  => 'ref',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'level',
            array(
                'header' => Mage::helper('bs_car')->__('Level'),
                'index'  => 'level',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'nc',
            array(
                'header' => Mage::helper('bs_car')->__('NC Cause'),
                'index'  => 'nc',
                'type'=> 'text',

            )
        );
        */
        $this->addColumn(
            'expire_date',
            array(
                'header' => Mage::helper('bs_car')->__('Correct Before Date'),
                'index'  => 'expire_date',
                'type'=> 'date',

            )
        );

        $this->addColumn('description', array(
            'header'    => $this->__('Non-Conformity Description'),
            'index'     => 'description',
            'renderer' => 'bs_car/adminhtml_helper_column_renderer_text',
            'char_limit'    => 200,
            'col_name' => 'description',


        ));

        $this->addColumn('root_cause', array(
            'header'    => $this->__('Identification Of Root Cause'),
            'index'     => 'root_cause',
            'renderer' => 'bs_car/adminhtml_helper_column_renderer_text',
            'char_limit'    => 200,
            'col_name' => 'root_cause',

        ));

        $this->addColumn('corrective', array(
            'header'    => $this->__('Corrective Action'),
            'index'     => 'corrective',
            'renderer' => 'bs_car/adminhtml_helper_column_renderer_text',
            'char_limit'    => 200,
            'col_name' => 'corrective',

        ));

        $this->addColumn('preventive', array(
            'header'    => $this->__('Preventive Action'),
            'index'     => 'preventive',
            'renderer' => 'bs_car/adminhtml_helper_column_renderer_text',
            'char_limit'    => 200,
            'col_name' => 'preventive',

        ));

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_car')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_car')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_car')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_car')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_car')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_Car_Block_Adminhtml_Qacar_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('qacar');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_car/qacar/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_car/qacar/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_car')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_car')->__('Are you sure?')
                )
            );
        }

        $this->getMassactionBlock()->addItem(
            'generate',
            array(
                'label'=> Mage::helper('bs_car')->__('Generate'),
                'url'  => $this->getUrl('*/*/massGenerateCar'),
                'additional' => array(
                    'compress' => array(
                        'name'   => 'compress',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_car')->__('Compress?'),
                        'values' => array(
                            '1' => Mage::helper('bs_car')->__('Yes'),
                            '0' => Mage::helper('bs_car')->__('No'),
                        )
                    )
                )
            )
        );

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_car')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_car')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_car')->__('Enabled'),
                                '0' => Mage::helper('bs_car')->__('Disabled'),
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
     * @param BS_Car_Model_Qacar
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
     * @return BS_Car_Block_Adminhtml_Qacar_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
