<?php
/**
 * BS_Worksheet extension
 * 
 * 
 * @category       BS
 * @package        BS_Worksheet
 * @copyright      Copyright (c) 2015
 */
/**
 * Worksheet admin grid block
 *
 * @category    BS
 * @package     BS_Worksheet
 * @author      Bui Phong
 */
class BS_Worksheet_Block_Adminhtml_Worksheet_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('worksheetGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Worksheet_Block_Adminhtml_Worksheet_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_worksheet/worksheet')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Worksheet_Block_Adminhtml_Worksheet_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_worksheet')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'ws_name',
            array(
                'header'    => Mage::helper('bs_worksheet')->__('Worksheet Name'),
                'align'     => 'left',
                'index'     => 'ws_name',
            )
        );
        

        $this->addColumn(
            'ws_code',
            array(
                'header' => Mage::helper('bs_worksheet')->__('Worksheet Code'),
                'index'  => 'ws_code',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'ws_approved_date',
            array(
                'header' => Mage::helper('bs_worksheet')->__('Approved Date'),
                'index'  => 'ws_approved_date',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'ws_page',
            array(
                'header' => Mage::helper('bs_worksheet')->__('Total Page'),
                'index'  => 'ws_page',
                'type'=> 'text',

            )
        );

        $this->addColumn(
            'ws_revision',
            array(
                'header' => Mage::helper('bs_worksheet')->__('Revision'),
                'index'  => 'ws_revision',
                'type'  => 'options',
                'options' => Mage::helper('bs_worksheet')->convertOptions(
                    Mage::getModel('bs_worksheet/worksheet_attribute_source_wsrevision')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'content',
            array(
                'header' => Mage::helper('bs_worksheet')->__('Content'),
                'type'=> 'text',
                'renderer'  => 'bs_worksheet/adminhtml_helper_column_renderer_download',

            )
        );
        /*$this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_worksheet')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_worksheet')->__('Enabled'),
                    '0' => Mage::helper('bs_worksheet')->__('Disabled'),
                )
            )
        );*/


        $this->addExportType('*/*/exportCsv', Mage::helper('bs_worksheet')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_worksheet')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_worksheet')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_Worksheet_Block_Adminhtml_Worksheet_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('worksheet');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/worksheet/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/worksheet/delete");

        $this->getMassactionBlock()->addItem(
            'generate',
            array(
                'label'=> Mage::helper('bs_worksheet')->__('Generate 8016'),
                'url'  => $this->getUrl('*/*/massGenerateSixteen'),
            )
        );

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_worksheet')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_worksheet')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_worksheet')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_worksheet')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_worksheet')->__('Enabled'),
                                '0' => Mage::helper('bs_worksheet')->__('Disabled'),
                            )
                        )
                    )
                )
            );
            $this->getMassactionBlock()->addItem(
                'ws_revision',
                array(
                    'label'      => Mage::helper('bs_worksheet')->__('Change Revision'),
                    'url'        => $this->getUrl('*/*/massWsRevision', array('_current'=>true)),
                    'additional' => array(
                        'flag_ws_revision' => array(
                            'name'   => 'flag_ws_revision',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_worksheet')->__('Revision'),
                            'values' => Mage::getModel('bs_worksheet/worksheet_attribute_source_wsrevision')
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
     * @param BS_Worksheet_Model_Worksheet
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
     * @return BS_Worksheet_Block_Adminhtml_Worksheet_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
