<?php
/**
 * BS_Formtemplate extension
 * 
 * @category       BS
 * @package        BS_Formtemplate
 * @copyright      Copyright (c) 2015
 */
/**
 * Form Template admin grid block
 *
 * @category    BS
 * @package     BS_Formtemplate
 * @author Bui Phong
 */
class BS_Formtemplate_Block_Adminhtml_Formtemplate_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('formtemplateGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Formtemplate_Block_Adminhtml_Formtemplate_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_formtemplate/formtemplate')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Formtemplate_Block_Adminhtml_Formtemplate_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_formtemplate')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'template_name',
            array(
                'header'    => Mage::helper('bs_formtemplate')->__('Name'),
                'align'     => 'left',
                'index'     => 'template_name',
            )
        );
        

        $this->addColumn(
            'template_code',
            array(
                'header' => Mage::helper('bs_formtemplate')->__('Code'),
                'index'  => 'template_code',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'template_date',
            array(
                'header' => Mage::helper('bs_formtemplate')->__('Approved Date'),
                'index'  => 'template_date',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'template_revision',
            array(
                'header' => Mage::helper('bs_formtemplate')->__('Revision'),
                'index'  => 'template_revision',
                'type'  => 'options',
                'options' => Mage::helper('bs_formtemplate')->convertOptions(
                    Mage::getModel('bs_formtemplate/formtemplate_attribute_source_templaterevision')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'template_file',
            array(
                'header' => Mage::helper('bs_formtemplate')->__('File'),
                'type'=> 'text',
                'renderer'  => 'bs_formtemplate/adminhtml_helper_column_renderer_file'

            )
        );

        $this->addColumn(
            'template_note',
            array(
                'header' => Mage::helper('bs_formtemplate')->__('Note'),
                'index'  => 'template_note',
                'type'=> 'text',

            )
        );


        /*$this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_formtemplate')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_formtemplate')->__('Enabled'),
                    '0' => Mage::helper('bs_formtemplate')->__('Disabled'),
                )
            )
        );*/

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_formtemplate')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_formtemplate')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_formtemplate')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_formtemplate')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_formtemplate')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_Formtemplate_Block_Adminhtml_Formtemplate_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('formtemplate');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("system/formtemplate/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("system/formtemplate/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_formtemplate')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_formtemplate')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_formtemplate')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_formtemplate')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_formtemplate')->__('Enabled'),
                                '0' => Mage::helper('bs_formtemplate')->__('Disabled'),
                            )
                        )
                    )
                )
            );




        $this->getMassactionBlock()->addItem(
            'template_revision',
            array(
                'label'      => Mage::helper('bs_formtemplate')->__('Change Revision'),
                'url'        => $this->getUrl('*/*/massTemplateRevision', array('_current'=>true)),
                'additional' => array(
                    'flag_template_revision' => array(
                        'name'   => 'flag_template_revision',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_formtemplate')->__('Revision'),
                        'values' => Mage::getModel('bs_formtemplate/formtemplate_attribute_source_templaterevision')
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
     * @param BS_Formtemplate_Model_Formtemplate
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
     * @return BS_Formtemplate_Block_Adminhtml_Formtemplate_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
