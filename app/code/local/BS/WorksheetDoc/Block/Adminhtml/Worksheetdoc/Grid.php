<?php
/**
 * BS_WorksheetDoc extension
 * 
 * @category       BS
 * @package        BS_WorksheetDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Worksheet Document admin grid block
 *
 * @category    BS
 * @package     BS_WorksheetDoc
 * @author      Bui Phong
 */
class BS_WorksheetDoc_Block_Adminhtml_Worksheetdoc_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('worksheetdocGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_WorksheetDoc_Block_Adminhtml_Worksheetdoc_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_worksheetdoc/worksheetdoc')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_WorksheetDoc_Block_Adminhtml_Worksheetdoc_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_worksheetdoc')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'wsdoc_name',
            array(
                'header'    => Mage::helper('bs_worksheetdoc')->__('Document Name'),
                'align'     => 'left',
                'index'     => 'wsdoc_name',
            )
        );
        

        $this->addColumn(
            'wsdoc_type',
            array(
                'header' => Mage::helper('bs_worksheetdoc')->__('Document Type'),
                'index'  => 'wsdoc_type',
                'type'  => 'options',
                'options' => Mage::helper('bs_worksheetdoc')->convertOptions(
                    Mage::getModel('bs_worksheetdoc/worksheetdoc_attribute_source_wsdoctype')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'wsdoc_rev',
            array(
                'header' => Mage::helper('bs_worksheetdoc')->__('Revision'),
                'index'  => 'wsdoc_rev',
                'type'  => 'options',
                'options' => Mage::helper('bs_worksheetdoc')->convertOptions(
                    Mage::getModel('bs_worksheetdoc/worksheetdoc_attribute_source_wsdocrev')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_worksheetdoc')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_worksheetdoc')->__('Enabled'),
                    '0' => Mage::helper('bs_worksheetdoc')->__('Disabled'),
                )
            )
        );

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_worksheetdoc')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_worksheetdoc')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_worksheetdoc')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_worksheetdoc')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_worksheetdoc')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_WorksheetDoc_Block_Adminhtml_Worksheetdoc_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('worksheetdoc');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_material/worksheetdoc/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_material/worksheetdoc/delete");


        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_worksheetdoc')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_worksheetdoc')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_worksheetdoc')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_worksheetdoc')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_worksheetdoc')->__('Enabled'),
                                '0' => Mage::helper('bs_worksheetdoc')->__('Disabled'),
                            )
                        )
                    )
                )
            );
            $this->getMassactionBlock()->addItem(
                'wsdoc_type',
                array(
                    'label'      => Mage::helper('bs_worksheetdoc')->__('Change Document Type'),
                    'url'        => $this->getUrl('*/*/massWsdocType', array('_current'=>true)),
                    'additional' => array(
                        'flag_wsdoc_type' => array(
                            'name'   => 'flag_wsdoc_type',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_worksheetdoc')->__('Document Type'),
                            'values' => Mage::getModel('bs_worksheetdoc/worksheetdoc_attribute_source_wsdoctype')
                                ->getAllOptions(true),

                        )
                    )
                )
            );
            $this->getMassactionBlock()->addItem(
                'wsdoc_rev',
                array(
                    'label'      => Mage::helper('bs_worksheetdoc')->__('Change Revision'),
                    'url'        => $this->getUrl('*/*/massWsdocRev', array('_current'=>true)),
                    'additional' => array(
                        'flag_wsdoc_rev' => array(
                            'name'   => 'flag_wsdoc_rev',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_worksheetdoc')->__('Revision'),
                            'values' => Mage::getModel('bs_worksheetdoc/worksheetdoc_attribute_source_wsdocrev')
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
     * @param BS_WorksheetDoc_Model_Worksheetdoc
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
     * @return BS_WorksheetDoc_Block_Adminhtml_Worksheetdoc_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
