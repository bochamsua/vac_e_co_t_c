<?php
/**
 * BS_Car extension
 * 
 * @category       BS
 * @package        BS_Car
 * @copyright      Copyright (c) 2016
 */
/**
 * CAR Document admin grid block
 *
 * @category    BS
 * @package     BS_Car
 * @author Bui Phong
 */
class BS_Car_Block_Adminhtml_Cardoc_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('cardocGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Car_Block_Adminhtml_Cardoc_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_car/cardoc')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Car_Block_Adminhtml_Cardoc_Grid
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
            'qacar_id',
            array(
                'header'    => Mage::helper('bs_car')->__('QA Car'),
                'index'     => 'qacar_id',
                'type'      => 'options',
                'options'   => Mage::getResourceModel('bs_car/qacar_collection')
                    ->toOptionHash(),
                'renderer'  => 'bs_car/adminhtml_helper_column_renderer_parent',
                'params'    => array(
                    'id'    => 'getQacarId'
                ),
                'base_link' => 'adminhtml/car_qacar/edit'
            )
        );
        $this->addColumn(
            'doc_name',
            array(
                'header'    => Mage::helper('bs_car')->__('Name'),
                'align'     => 'left',
                'index'     => 'doc_name',
            )
        );
        

        $this->addColumn(
            'doc_date',
            array(
                'header' => Mage::helper('bs_car')->__('Date'),
                'index'  => 'doc_date',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'doc_note',
            array(
                'header' => Mage::helper('bs_car')->__('Note'),
                'index'  => 'doc_note',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_car')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_car')->__('Enabled'),
                    '0' => Mage::helper('bs_car')->__('Disabled'),
                )
            )
        );

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
     * @return BS_Car_Block_Adminhtml_Cardoc_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('cardoc');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_car/cardoc/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_car/cardoc/delete");

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




        $values = Mage::getResourceModel('bs_car/qacar_collection')->toOptionHash();
        $values = array_reverse($values, true);
        $values[''] = '';
        $values = array_reverse($values, true);
        $this->getMassactionBlock()->addItem(
            'qacar_id',
            array(
                'label'      => Mage::helper('bs_car')->__('Change QA Car'),
                'url'        => $this->getUrl('*/*/massQacarId', array('_current'=>true)),
                'additional' => array(
                    'flag_qacar_id' => array(
                        'name'   => 'flag_qacar_id',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_car')->__('QA Car'),
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
     * @param BS_Car_Model_Cardoc
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
     * @return BS_Car_Block_Adminhtml_Cardoc_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
