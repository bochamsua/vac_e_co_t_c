<?php
/**
 * BS_Otherdoc extension
 * 
 * @category       BS
 * @package        BS_Otherdoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Other\'s Course Document admin grid block
 *
 * @category    BS
 * @package     BS_Otherdoc
 * @author Bui Phong
 */
class BS_Otherdoc_Block_Adminhtml_Otherdoc_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('otherdocGrid');
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
                    'onclick'   => 'setLocation(\''.$this->getUrl('*/otherdoc_otherdoc', array('showpage'=>true)).'\')'
                ))
        );

        $this->setChild('listrar',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('List Rar/Zip Files'),
                    'onclick'   => 'setLocation(\''.$this->getUrl('*/otherdoc_otherdoc', array('listrar'=>true)).'\')'
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
     * @return BS_Otherdoc_Block_Adminhtml_Otherdoc_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_otherdoc/otherdoc')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Otherdoc_Block_Adminhtml_Otherdoc_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_otherdoc')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'otherdoc_name',
            array(
                'header'    => Mage::helper('bs_otherdoc')->__('Document Name'),
                'align'     => 'left',
                'index'     => 'otherdoc_name',
            )
        );
        

        $this->addColumn(
            'otherdoc_type',
            array(
                'header' => Mage::helper('bs_otherdoc')->__('Document Type'),
                'index'  => 'otherdoc_type',
                'type'  => 'options',
                'options' => Mage::helper('bs_otherdoc')->convertOptions(
                    Mage::getModel('bs_otherdoc/otherdoc_attribute_source_otherdoctype')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'otherdoc_date',
            array(
                'header' => Mage::helper('bs_otherdoc')->__('Date'),
                'index'  => 'otherdoc_date',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'otherdoc_rev',
            array(
                'header' => Mage::helper('bs_otherdoc')->__('Revision'),
                'index'  => 'otherdoc_rev',
                'type'  => 'options',
                'options' => Mage::helper('bs_otherdoc')->convertOptions(
                    Mage::getModel('bs_otherdoc/otherdoc_attribute_source_otherdocrev')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'otherdoc_file',
            array(
                'header'  =>  Mage::helper('bs_otherdoc')->__('View/Download'),
                'type'      =>'text',
                'renderer' => 'bs_otherdoc/adminhtml_helper_column_renderer_download',

                'index'    => 'otherdoc_file',
            )
        );

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_otherdoc')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_otherdoc')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_otherdoc')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_otherdoc')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_otherdoc')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_Otherdoc_Block_Adminhtml_Otherdoc_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('otherdoc');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_material/otherdoc/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_material/otherdoc/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_otherdoc')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_otherdoc')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_otherdoc')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_otherdoc')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_otherdoc')->__('Enabled'),
                                '0' => Mage::helper('bs_otherdoc')->__('Disabled'),
                            )
                        )
                    )
                )
            );




        $this->getMassactionBlock()->addItem(
            'otherdoc_type',
            array(
                'label'      => Mage::helper('bs_otherdoc')->__('Change Document Type'),
                'url'        => $this->getUrl('*/*/massOtherdocType', array('_current'=>true)),
                'additional' => array(
                    'flag_otherdoc_type' => array(
                        'name'   => 'flag_otherdoc_type',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_otherdoc')->__('Document Type'),
                        'values' => Mage::getModel('bs_otherdoc/otherdoc_attribute_source_otherdoctype')
                            ->getAllOptions(true),

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'otherdoc_rev',
            array(
                'label'      => Mage::helper('bs_otherdoc')->__('Change Revision'),
                'url'        => $this->getUrl('*/*/massOtherdocRev', array('_current'=>true)),
                'additional' => array(
                    'flag_otherdoc_rev' => array(
                        'name'   => 'flag_otherdoc_rev',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_otherdoc')->__('Revision'),
                        'values' => Mage::getModel('bs_otherdoc/otherdoc_attribute_source_otherdocrev')
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
     * @param BS_Otherdoc_Model_Otherdoc
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
     * @return BS_Otherdoc_Block_Adminhtml_Otherdoc_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
