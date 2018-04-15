<?php
/**
 * BS_Material extension
 * 
 * 
 * @category       BS
 * @package        BS_Material
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Document admin grid block
 *
 * @category    BS
 * @package     BS_Material
 * @author      Bui Phong
 */
class BS_Material_Block_Adminhtml_Instructordoc_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('instructordocGrid');
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
                    'onclick'   => 'setLocation(\''.$this->getUrl('*/material_instructordoc', array('showpage'=>true)).'\')'
                ))
        );

        $this->setChild('listrar',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('List Rar/Zip Files'),
                    'onclick'   => 'setLocation(\''.$this->getUrl('*/material_instructordoc', array('listrar'=>true)).'\')'
                ))
        );

        parent::_prepareLayout();
    }


    public function getMainButtonsHtml()
    {
        $html = '';
        if($this->getFilterVisibility()){
            $html.= $this->getChildHtml('showpage');
            $html.= $this->getChildHtml('listrar');
            $html.= $this->getResetFilterButtonHtml();
            $html.= $this->getSearchButtonHtml();

        }
        return $html;

    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Material_Block_Adminhtml_Instructordoc_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_material/instructordoc')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Material_Block_Adminhtml_Instructordoc_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_material')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'instructor',
            array(
                'header'  =>  Mage::helper('bs_material')->__('Instructor'),
                'type'      =>'text',
                'renderer' => 'bs_material/adminhtml_helper_column_renderer_instructor',

                'filter'    => false,
                'sortable'  => false,
            )
        );

        $this->addColumn(
            'idoc_name',
            array(
                'header'    => Mage::helper('bs_material')->__('Document Name'),
                'align'     => 'left',
                'index'     => 'idoc_name',
            )
        );

        $this->addColumn(
            'idoc_type',
            array(
                'header' => Mage::helper('bs_material')->__('Document Type'),
                'index'  => 'idoc_type',
                'type'  => 'options',
                'options' => Mage::helper('bs_material')->convertOptions(
                    Mage::getModel('bs_material/instructordoc_attribute_source_idoctype')->getAllOptions(false)
                )

            )
        );

        $this->addColumn(
            'idoc_file',
            array(
                'header'  =>  Mage::helper('bs_material')->__('View/Download'),
                'type'      =>'text',
                'renderer' => 'bs_material/adminhtml_helper_column_renderer_download',

                'index'    => 'idoc_file',
            )
        );

        $this->addColumn(
            'idoc_date',
            array(
                'header' => Mage::helper('bs_material')->__('Approved/Revised'),
                'index'  => 'idoc_date',
                'type'  => 'date',


            )
        );

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_material')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_material')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_material')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_material')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_material')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_Material_Block_Adminhtml_Instructordoc_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('instructordoc');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_material/instructordoc/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_material/instructordoc/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_material')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_material')->__('Are you sure?')
                )
            );
        }

        $this->getMassactionBlock()->addItem(
            'approved_date',
            array(
                'label'      => Mage::helper('bs_material')->__('Update Approved Date'),
                'url'        => $this->getUrl('*/*/massApprovedDate', array('_current'=>true)),
                'additional' => array(
                    'approved_date' => array(
                        'name'   => 'approved_date',
                        'type'   => 'date',
                        'class'  => 'required-entry',
                        'image' => $this->getSkinUrl('images/grid-cal.gif'),
                        'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
                        'label'  => Mage::helper('bs_material')->__('Date'),

                    )
                )
            )
        );


        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param BS_Material_Model_Instructordoc
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
     * @return BS_Material_Block_Adminhtml_Instructordoc_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
