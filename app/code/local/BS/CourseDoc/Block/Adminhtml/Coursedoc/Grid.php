<?php
/**
 * BS_CourseDoc extension
 * 
 * @category       BS
 * @package        BS_CourseDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Course Document admin grid block
 *
 * @category    BS
 * @package     BS_CourseDoc
 * @author      Bui Phong
 */
class BS_CourseDoc_Block_Adminhtml_Coursedoc_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('coursedocGrid');
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
                    'onclick'   => 'setLocation(\''.$this->getUrl('*/coursedoc_coursedoc', array('showpage'=>true)).'\')'
                ))
        );

        $this->setChild('listrar',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('List Rar/Zip Files'),
                    'onclick'   => 'setLocation(\''.$this->getUrl('*/coursedoc_coursedoc', array('listrar'=>true)).'\')'
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
     * @return BS_CourseDoc_Block_Adminhtml_Coursedoc_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_coursedoc/coursedoc')
            ->getCollection();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_CourseDoc_Block_Adminhtml_Coursedoc_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_coursedoc')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );

        $this->addColumn(
            'course_doc_name',
            array(
                'header'    => Mage::helper('bs_coursedoc')->__('Document Name'),
                'align'     => 'left',
                'index'     => 'course_doc_name',
            )
        );

        $this->addColumn(
            'doc_code',
            array(
                'header'    => Mage::helper('bs_coursedoc')->__('Document Code'),
                'align'     => 'left',
                'index'     => 'doc_code',
            )
        );

        $this->addColumn(
            'doc_inorout',
            array(
                'header'  => Mage::helper('bs_coursedoc')->__('In or Out'),
                'index'   => 'doc_inorout',
                'type'    => 'options',
                'options' => array(
                    '0' => Mage::helper('bs_coursedoc')->__('In Coming'),
                    '1' => Mage::helper('bs_coursedoc')->__('Out Going'),
                )
            )
        );

        $this->addColumn(
            'course_doc_type',
            array(
                'header' => Mage::helper('bs_coursedoc')->__('Document Type'),
                'index'  => 'course_doc_type',
                'type'  => 'options',
                'options' => Mage::helper('bs_coursedoc')->convertOptions(
                    Mage::getModel('bs_coursedoc/coursedoc_attribute_source_coursedoctype')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'doc_dept',
            array(
                'header' => Mage::helper('bs_coursedoc')->__('Department'),
                'index'  => 'doc_dept',
                'type'  => 'options',
                'options' => Mage::helper('bs_coursedoc')->convertOptions(
                    Mage::getModel('bs_coursedoc/coursedoc_attribute_source_coursedocdept')->getAllOptions(false)
                )

            )
        );

        $this->addColumn(
            'doc_date',
            array(
                'header' => Mage::helper('bs_register')->__('Date'),
                'index'  => 'doc_date',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'course_doc_rev',
            array(
                'header' => Mage::helper('bs_coursedoc')->__('Revision'),
                'index'  => 'course_doc_rev',
                'type'  => 'options',
                'options' => Mage::helper('bs_coursedoc')->convertOptions(
                    Mage::getModel('bs_coursedoc/coursedoc_attribute_source_coursedocrev')->getAllOptions(false)
                )

            )
        );

        $this->addColumn(
            'course_doc_file',
            array(
                'header'  =>  Mage::helper('bs_coursedoc')->__('View/Download'),
                'type'      =>'text',
                'renderer' => 'bs_coursedoc/adminhtml_helper_column_renderer_download',

                'index'  => 'course_doc_file',
            )
        );
        /*$this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_coursedoc')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_coursedoc')->__('Enabled'),
                    '0' => Mage::helper('bs_coursedoc')->__('Disabled'),
                )
            )
        );*/

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_coursedoc')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_coursedoc')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_coursedoc')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_coursedoc')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_coursedoc')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_CourseDoc_Block_Adminhtml_Coursedoc_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('coursedoc');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_material/coursedoc/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_material/coursedoc/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_coursedoc')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_coursedoc')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_coursedoc')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_coursedoc')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_coursedoc')->__('Enabled'),
                                '0' => Mage::helper('bs_coursedoc')->__('Disabled'),
                            )
                        )
                    )
                )
            );




        $this->getMassactionBlock()->addItem(
            'course_doc_type',
            array(
                'label'      => Mage::helper('bs_coursedoc')->__('Change Document Type'),
                'url'        => $this->getUrl('*/*/massCourseDocType', array('_current'=>true)),
                'additional' => array(
                    'flag_course_doc_type' => array(
                        'name'   => 'flag_course_doc_type',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_coursedoc')->__('Document Type'),
                        'values' => Mage::getModel('bs_coursedoc/coursedoc_attribute_source_coursedoctype')
                            ->getAllOptions(true),

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'course_doc_rev',
            array(
                'label'      => Mage::helper('bs_coursedoc')->__('Change Revision'),
                'url'        => $this->getUrl('*/*/massCourseDocRev', array('_current'=>true)),
                'additional' => array(
                    'flag_course_doc_rev' => array(
                        'name'   => 'flag_course_doc_rev',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_coursedoc')->__('Revision'),
                        'values' => Mage::getModel('bs_coursedoc/coursedoc_attribute_source_coursedocrev')
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
     * @param BS_CourseDoc_Model_Coursedoc
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
     * @return BS_CourseDoc_Block_Adminhtml_Coursedoc_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
