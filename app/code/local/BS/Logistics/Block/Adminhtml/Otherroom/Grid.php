<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Other room admin grid block
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Otherroom_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('otherroomGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Otherroom_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_logistics/otherroom')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Otherroom_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        /*$this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_logistics')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );*/
        $this->addColumn(
            'otherroom_name',
            array(
                'header'    => Mage::helper('bs_logistics')->__('Name'),
                'align'     => 'left',
                'index'     => 'otherroom_name',
            )
        );
        

        $this->addColumn(
            'otherroom_code',
            array(
                'header' => Mage::helper('bs_logistics')->__('Code'),
                'index'  => 'otherroom_code',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'otherroom_location',
            array(
                'header' => Mage::helper('bs_logistics')->__('Location'),
                'index'  => 'otherroom_location',
                'type'  => 'options',
                'options' => Mage::helper('bs_logistics')->convertOptions(
                    Mage::getModel('bs_logistics/otherroom_attribute_source_otherroomlocation')->getAllOptions(false)
                )

            )
        );
        /*$this->addColumn(
            'otherroom_area',
            array(
                'header' => Mage::helper('bs_logistics')->__('Area'),
                'index'  => 'otherroom_area',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_logistics')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_logistics')->__('Enabled'),
                    '0' => Mage::helper('bs_logistics')->__('Disabled'),
                )
            )
        );*/

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_logistics')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_logistics')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_logistics')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_logistics')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_logistics')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Otherroom_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('otherroom');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_logistics/otherroom/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_logistics/otherroom/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_logistics')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_logistics')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_logistics')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_logistics')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_logistics')->__('Enabled'),
                                '0' => Mage::helper('bs_logistics')->__('Disabled'),
                            )
                        )
                    )
                )
            );




        $this->getMassactionBlock()->addItem(
            'otherroom_location',
            array(
                'label'      => Mage::helper('bs_logistics')->__('Change Location'),
                'url'        => $this->getUrl('*/*/massOtherroomLocation', array('_current'=>true)),
                'additional' => array(
                    'flag_otherroom_location' => array(
                        'name'   => 'flag_otherroom_location',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_logistics')->__('Location'),
                        'values' => Mage::getModel('bs_logistics/otherroom_attribute_source_otherroomlocation')
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
     * @param BS_Logistics_Model_Otherroom
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
     * @return BS_Logistics_Block_Adminhtml_Otherroom_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
