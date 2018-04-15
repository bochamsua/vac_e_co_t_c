<?php
/**
 * BS_AdministrativeDoc extension
 * 
 * @category       BS
 * @package        BS_AdministrativeDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Administrative Document admin grid block
 *
 * @category    BS
 * @package     BS_AdministrativeDoc
 * @author Bui Phong
 */
class BS_AdministrativeDoc_Block_Adminhtml_Administrativedocument_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('administrativedocumentGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareLayout(){

        $this->setChild('showpage',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('Show Pages'),
                    'onclick'   => 'setLocation(\''.$this->getUrl('*/administrativedoc_administrativedocument', array('showpage'=>true)).'\')'
                ))
        );

        parent::_prepareLayout();
    }


    public function getMainButtonsHtml()
    {
        $html = '';
        if($this->getFilterVisibility()){
            $html.= $this->getChildHtml('showpage');
            $html.= $this->getResetFilterButtonHtml();
            $html.= $this->getSearchButtonHtml();

        }
        return $html;

    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_AdministrativeDoc_Block_Adminhtml_Administrativedocument_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_administrativedoc/administrativedocument')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_AdministrativeDoc_Block_Adminhtml_Administrativedocument_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_administrativedoc')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'doc_name',
            array(
                'header'    => Mage::helper('bs_administrativedoc')->__('Document Name'),
                'align'     => 'left',
                'index'     => 'doc_name',
            )
        );
        

        $this->addColumn(
            'doc_date',
            array(
                'header' => Mage::helper('bs_administrativedoc')->__('Date'),
                'index'  => 'doc_date',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'doc_file',
            array(
                'header' => Mage::helper('bs_administrativedoc')->__('File'),
                'type'=> 'text',
                'renderer'  => 'bs_administrativedoc/adminhtml_helper_column_renderer_file'

            )
        );


        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_administrativedoc')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_administrativedoc')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_administrativedoc')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_administrativedoc')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_administrativedoc')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_AdministrativeDoc_Block_Adminhtml_Administrativedocument_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('administrativedocument');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_material/administrativedocument/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_material/administrativedocument/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_administrativedoc')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_administrativedoc')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_administrativedoc')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_administrativedoc')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_administrativedoc')->__('Enabled'),
                                '0' => Mage::helper('bs_administrativedoc')->__('Disabled'),
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
     * @param BS_AdministrativeDoc_Model_Administrativedocument
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
     * @return BS_AdministrativeDoc_Block_Adminhtml_Administrativedocument_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
