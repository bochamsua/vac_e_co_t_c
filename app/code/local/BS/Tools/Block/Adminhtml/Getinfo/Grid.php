<?php
/**
 * BS_Tools extension
 * 
 * @category       BS
 * @package        BS_Tools
 * @copyright      Copyright (c) 2015
 */
/**
 * Get Info admin grid block
 *
 * @category    BS
 * @package     BS_Tools
 * @author Bui Phong
 */
class BS_Tools_Block_Adminhtml_Getinfo_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('getinfoGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Tools_Block_Adminhtml_Getinfo_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_tools/getinfo')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Tools_Block_Adminhtml_Getinfo_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_tools')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'vaeco_ids',
            array(
                'header'    => Mage::helper('bs_tools')->__('VAECO IDs'),
                'align'     => 'left',
                'index'     => 'vaeco_ids',
            )
        );
        

        $this->addColumn(
            'action_type',
            array(
                'header' => Mage::helper('bs_tools')->__('Action'),
                'index'  => 'action_type',
                'type'  => 'options',
                'options' => Mage::helper('bs_tools')->convertOptions(
                    Mage::getModel('bs_tools/getinfo_attribute_source_actiontype')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_tools')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_tools')->__('Enabled'),
                    '0' => Mage::helper('bs_tools')->__('Disabled'),
                )
            )
        );

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_tools')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_tools')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_tools')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_tools')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_tools')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_Tools_Block_Adminhtml_Getinfo_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('getinfo');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_tools/getinfo/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_tools/getinfo/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_tools')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_tools')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_tools')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_tools')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_tools')->__('Enabled'),
                                '0' => Mage::helper('bs_tools')->__('Disabled'),
                            )
                        )
                    )
                )
            );




        $this->getMassactionBlock()->addItem(
            'action_type',
            array(
                'label'      => Mage::helper('bs_tools')->__('Change Action'),
                'url'        => $this->getUrl('*/*/massActionType', array('_current'=>true)),
                'additional' => array(
                    'flag_action_type' => array(
                        'name'   => 'flag_action_type',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_tools')->__('Action'),
                        'values' => Mage::getModel('bs_tools/getinfo_attribute_source_actiontype')
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
     * @param BS_Tools_Model_Getinfo
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
     * @return BS_Tools_Block_Adminhtml_Getinfo_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
