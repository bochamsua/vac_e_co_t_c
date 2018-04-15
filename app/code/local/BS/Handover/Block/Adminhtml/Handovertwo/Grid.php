<?php
/**
 * BS_Handover extension
 * 
 * @category       BS
 * @package        BS_Handover
 * @copyright      Copyright (c) 2015
 */
/**
 * Minutes of Handover V2 admin grid block
 *
 * @category    BS
 * @package     BS_Handover
 * @author Bui Phong
 */
class BS_Handover_Block_Adminhtml_Handovertwo_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('handovertwoGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Handover_Block_Adminhtml_Handovertwo_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_handover/handovertwo')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Handover_Block_Adminhtml_Handovertwo_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_handover')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'course_id',
            array(
                'header'    => Mage::helper('bs_handover')->__('Course'),
                'align'     => 'left',
                'index'     => 'course_id',
            )
        );
        $this->addColumn(
            'receiver',
            array(
                'header' => Mage::helper('bs_handover')->__('Receiver'),
                'index'  => 'receiver',
                'type'=> 'text',

            )
        );

        $this->addColumn(
            'note',
            array(
                'header' => Mage::helper('bs_handover')->__('Note'),
                'index'  => 'note',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_handover')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_handover')->__('Enabled'),
                    '0' => Mage::helper('bs_handover')->__('Disabled'),
                )
            )
        );

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_handover')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_handover')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_handover')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_handover')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_handover')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_Handover_Block_Adminhtml_Handovertwo_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('handovertwo');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_material/bs_handover/handovertwo/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_material/bs_handover/handovertwo/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_handover')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_handover')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_handover')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_handover')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_handover')->__('Enabled'),
                                '0' => Mage::helper('bs_handover')->__('Disabled'),
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
     * @param BS_Handover_Model_Handovertwo
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
     * @return BS_Handover_Block_Adminhtml_Handovertwo_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
