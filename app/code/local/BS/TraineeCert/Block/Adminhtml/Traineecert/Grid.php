<?php
/**
 * BS_TraineeCert extension
 * 
 * @category       BS
 * @package        BS_TraineeCert
 * @copyright      Copyright (c) 2015
 */
/**
 * Trainee Certificate admin grid block
 *
 * @category    BS
 * @package     BS_TraineeCert
 * @author Bui Phong
 */
class BS_TraineeCert_Block_Adminhtml_Traineecert_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('traineecertGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);

        $courseId = $this->getRequest()->getParam('course_id', false);
        if($courseId){

            $course = Mage::getSingleton('catalog/product')->load($courseId);
            $this->setDefaultFilter( array('sku'=>$course->getSku()) );
        }
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_TraineeCert_Block_Adminhtml_Traineecert_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_traineecert/traineecert')
            ->getCollection();

        $collection->getSelect()->joinLeft(array('pro'=>'catalog_product_entity'),'course_id = pro.entity_id','sku');
        $collection->getSelect()->joinLeft(array('trainee1'=>'bs_trainee_trainee_varchar'),'trainee_id = trainee1.entity_id AND trainee1.attribute_id = 276','trainee1.value AS trainee_name');
        $collection->getSelect()->joinLeft(array('trainee2'=>'bs_trainee_trainee_varchar'),'trainee_id = trainee2.entity_id AND trainee2.attribute_id = 278','trainee2.value AS vaeco_id');


        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_TraineeCert_Block_Adminhtml_Traineecert_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_traineecert')->__('Id'),
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
            'trainee_name',
            array(
                'header' => Mage::helper('bs_traineecert')->__('Trainee'),
                'index'  => 'trainee_name',
                'type'=> 'text',
                'filter_index'  => 'trainee1.value'

            )
        );
        $this->addColumn(
            'vaeco_id',
            array(
                'header' => Mage::helper('bs_traineecert')->__('VAECO ID'),
                'index'  => 'vaeco_id',
                'type'=> 'text',
                'filter_index'  => 'trainee2.value'

            )
        );
        $this->addColumn(
            'cert_no',
            array(
                'header'    => Mage::helper('bs_traineecert')->__('Certificate No'),
                'align'     => 'left',
                'index'     => 'cert_no',
            )
        );

        $this->addColumn(
            'issue_date',
            array(
                'header' => Mage::helper('bs_traineecert')->__('Issue Date'),
                'index'  => 'issue_date',
                'type'=> 'date',

            )
        );
        /*$this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_traineecert')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_traineecert')->__('Enabled'),
                    '0' => Mage::helper('bs_traineecert')->__('Disabled'),
                )
            )
        );*/

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_traineecert')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_traineecert')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_traineecert')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_traineecert')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_traineecert')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_TraineeCert_Block_Adminhtml_Traineecert_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('traineecert');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/traineecert/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/traineecert/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_traineecert')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_traineecert')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'issue_date',
                array(
                    'label'      => Mage::helper('bs_traineecert')->__('Update Issue Date'),
                    'url'        => $this->getUrl('*/*/massIssueDate', array('_current'=>true)),
                    'additional' => array(
                        'issue_date' => array(
                            'name'   => 'issue_date',
                            'type'   => 'date',
                            'class'  => 'required-entry',
                            'image' => $this->getSkinUrl('images/grid-cal.gif'),
                            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
                            'label'  => Mage::helper('bs_traineecert')->__('Date'),

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
     * @param BS_TraineeCert_Model_Traineecert
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
     * @return BS_TraineeCert_Block_Adminhtml_Traineecert_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
