<?php
/**
 * BS_TraineeDoc extension
 * 
 * @category       BS
 * @package        BS_TraineeDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Trainee Document admin grid block
 *
 * @category    BS
 * @package     BS_TraineeDoc
 * @author      Bui Phong
 */
class BS_TraineeDoc_Block_Adminhtml_Traineedoc_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('traineedocGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_TraineeDoc_Block_Adminhtml_Traineedoc_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_traineedoc/traineedoc')
            ->getCollection();
        $collection->getSelect()->joinLeft(array('pro'=>'catalog_product_entity'),'course_id = pro.entity_id','sku');
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_TraineeDoc_Block_Adminhtml_Traineedoc_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_traineedoc')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'sku',
            array(
                'header' => Mage::helper('bs_traineecert')->__('Course'),
                'index'  => 'sku',
                'type'=> 'text',
                'renderer' => 'bs_trainee/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getCourseId'
                ),
                'base_link' => 'adminhtml/catalog_product/edit',
                'filter_index'  => 'sku'

            )
        );
        $this->addColumn(
            'trainee',
            array(
                'header'  =>  Mage::helper('bs_traineecert')->__('Trainee'),
                'type'      =>'text',
                'renderer' => 'bs_traineedoc/adminhtml_helper_column_renderer_trainee',

                'filter'    => false,
                'sortable'  => false,
            )
        );

        $this->addColumn(
            'trainee_id',
            array(
                'header'  =>  Mage::helper('bs_traineecert')->__('Trainee ID'),
                'type'      =>'text',
                'renderer' => 'bs_traineedoc/adminhtml_helper_column_renderer_traineeid',

                'filter'    => false,
                'sortable'  => false,
            )
        );

        $this->addColumn(
            'trainee_doc_name',
            array(
                'header'    => Mage::helper('bs_traineedoc')->__('Document Name'),
                'align'     => 'left',
                'index'     => 'trainee_doc_name',
            )
        );
        

        $this->addColumn(
            'trainee_doc_type',
            array(
                'header' => Mage::helper('bs_traineedoc')->__('Document Type'),
                'index'  => 'trainee_doc_type',
                'type'  => 'options',
                'options' => Mage::helper('bs_traineedoc')->convertOptions(
                    Mage::getModel('bs_traineedoc/traineedoc_attribute_source_traineedoctype')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'trainee_doc_date',
            array(
                'header'    => Mage::helper('bs_traineedoc')->__('Date'),
                'type'     => 'date',
                'index'     => 'trainee_doc_date',
            )
        );

        $this->addColumn(
            'trainee_doc_file',
            array(
                'header'  =>  Mage::helper('bs_traineedoc')->__('View/Download'),
                'type'      =>'text',
                'renderer' => 'bs_traineedoc/adminhtml_helper_column_renderer_download',

                'index'    => 'trainee_doc_file',
            )
        );

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_traineedoc')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_traineedoc')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_traineedoc')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_traineedoc')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_traineedoc')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_TraineeDoc_Block_Adminhtml_Traineedoc_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('traineedoc');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_material/traineedoc/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_material/traineedoc/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_traineedoc')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_traineedoc')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_traineedoc')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_traineedoc')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_traineedoc')->__('Enabled'),
                                '0' => Mage::helper('bs_traineedoc')->__('Disabled'),
                            )
                        )
                    )
                )
            );




        $this->getMassactionBlock()->addItem(
            'trainee_doc_type',
            array(
                'label'      => Mage::helper('bs_traineedoc')->__('Change Document Type'),
                'url'        => $this->getUrl('*/*/massTraineeDocType', array('_current'=>true)),
                'additional' => array(
                    'flag_trainee_doc_type' => array(
                        'name'   => 'flag_trainee_doc_type',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_traineedoc')->__('Document Type'),
                        'values' => Mage::getModel('bs_traineedoc/traineedoc_attribute_source_traineedoctype')
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
     * @param BS_TraineeDoc_Model_Traineedoc
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
     * @return BS_TraineeDoc_Block_Adminhtml_Traineedoc_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
