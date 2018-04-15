<?php
/**
 * BS_CurriculumDoc extension
 * 
 * 
 * @category       BS
 * @package        BS_CurriculumDoc
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Curriculum Document admin grid block
 *
 * @category    BS
 * @package     BS_CurriculumDoc
 * @author      Bui Phong
 */
class BS_CurriculumDoc_Block_Adminhtml_Curriculumdoc_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('curriculumdocGrid');
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
                    'onclick'   => 'setLocation(\''.$this->getUrl('*/curriculumdoc_curriculumdoc', array('showpage'=>true)).'\')'
                ))
        );
        $this->setChild('listrar',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('List Rar/Zip Files'),
                    'onclick'   => 'setLocation(\''.$this->getUrl('*/curriculumdoc_curriculumdoc', array('listrar'=>true)).'\')'
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
     * @return BS_CurriculumDoc_Block_Adminhtml_Curriculumdoc_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_curriculumdoc/curriculumdoc')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_CurriculumDoc_Block_Adminhtml_Curriculumdoc_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_curriculumdoc')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'cdoc_name',
            array(
                'header'    => Mage::helper('bs_curriculumdoc')->__('Document Name'),
                'align'     => 'left',
                'index'     => 'cdoc_name',
            )
        );
        

        $this->addColumn(
            'cdoc_type',
            array(
                'header' => Mage::helper('bs_curriculumdoc')->__('Document Type'),
                'index'  => 'cdoc_type',
                'type'  => 'options',
                'options' => Mage::helper('bs_curriculumdoc')->convertOptions(
                    Mage::getModel('bs_curriculumdoc/curriculumdoc_attribute_source_cdoctype')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'cdoc_rev',
            array(
                'header' => Mage::helper('bs_curriculumdoc')->__('Revision'),
                'index'  => 'cdoc_rev',
                'type'  => 'options',
                'options' => Mage::helper('bs_curriculumdoc')->convertOptions(
                    Mage::getModel('bs_curriculumdoc/curriculumdoc_attribute_source_cdocrev')->getAllOptions(false)
                )

            )
        );

        $this->addColumn(
            'cdoc_date',
            array(
                'header' => Mage::helper('bs_curriculumdoc')->__('Approved/Revised Date'),
                'type'  => 'date',
                'index'  => 'cdoc_date',

            )
        );
        $this->addColumn(
            'cdoc_page',
            array(
                'header' => Mage::helper('bs_curriculumdoc')->__('Number of Pages'),
                'type'  => 'text',
                'index'     => 'cdoc_page',

            )
        );
        $this->addColumn(
            'cdoc_file',
            array(
                'header'  =>  Mage::helper('bs_curriculumdoc')->__('View/Download'),
                'type'      =>'text',
                'renderer' => 'bs_curriculumdoc/adminhtml_helper_column_renderer_download',
                'index'     => 'cdoc_file'

                //'filter'    => false,
                //'sortable'  => false,
            )
        );
        /*$this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_curriculumdoc')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_curriculumdoc')->__('Enabled'),
                    '0' => Mage::helper('bs_curriculumdoc')->__('Disabled'),
                )
            )
        );*/

        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('bs_curriculumdoc')->__('Created Date'),
                'type'  => 'date',
                'index'  => 'created_at',

            )
        );

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_curriculumdoc')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_curriculumdoc')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_curriculumdoc')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_curriculumdoc')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_curriculumdoc')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_CurriculumDoc_Block_Adminhtml_Curriculumdoc_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('curriculumdoc');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_material/curriculumdoc/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_material/curriculumdoc/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_curriculumdoc')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_curriculumdoc')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_curriculumdoc')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_curriculumdoc')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_curriculumdoc')->__('Enabled'),
                                '0' => Mage::helper('bs_curriculumdoc')->__('Disabled'),
                            )
                        )
                    )
                )
            );
            $this->getMassactionBlock()->addItem(
                'cdoc_type',
                array(
                    'label'      => Mage::helper('bs_curriculumdoc')->__('Change Document Type'),
                    'url'        => $this->getUrl('*/*/massCdocType', array('_current'=>true)),
                    'additional' => array(
                        'flag_cdoc_type' => array(
                            'name'   => 'flag_cdoc_type',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_curriculumdoc')->__('Document Type'),
                            'values' => Mage::getModel('bs_curriculumdoc/curriculumdoc_attribute_source_cdoctype')
                                ->getAllOptions(true),

                        )
                    )
                )
            );
            $this->getMassactionBlock()->addItem(
                'cdoc_rev',
                array(
                    'label'      => Mage::helper('bs_curriculumdoc')->__('Change Revision'),
                    'url'        => $this->getUrl('*/*/massCdocRev', array('_current'=>true)),
                    'additional' => array(
                        'flag_cdoc_rev' => array(
                            'name'   => 'flag_cdoc_rev',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_curriculumdoc')->__('Revision'),
                            'values' => Mage::getModel('bs_curriculumdoc/curriculumdoc_attribute_source_cdocrev')
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
     * @param BS_CurriculumDoc_Model_Curriculumdoc
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
     * @return BS_CurriculumDoc_Block_Adminhtml_Curriculumdoc_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
