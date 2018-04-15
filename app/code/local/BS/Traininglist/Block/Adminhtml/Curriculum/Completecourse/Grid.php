<?php
/**
 * BS_Traininglist extension
 *
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * Training Curriculum admin grid block
 *
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */
class BS_Traininglist_Block_Adminhtml_Curriculum_Completecourse_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('completecourseGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);

    }



    protected function _prepareCollection()
    {

        $currentDate = date("Y-m-d", Mage::getModel('core/date')->timestamp(time()));

        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('status', 1)
            ->addAttributeToFilter('course_start_date', array('to' => $currentDate))
            ->addAttributeToFilter('course_finish_date',array('to' => $currentDate))

            // ->addExpressionAttributeToSelect('total_trainees','SUM({{number_trainees}} + {{number_trainees2}} + {{number_trainees3}})', array('number_trainees','number_trainees2','number_trainees3'))
        ;



        $collection->addAttributeToSelect('price');
        $collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner');
        $collection->joinAttribute('course_report', 'catalog_product/course_report', 'entity_id', null, 'inner');
        $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner');

        $this->setCollection($collection);


        parent::_prepareCollection();

        return $this;
    }


    protected function _prepareColumns()
    {
        /*$this->addColumn('sku',
            array(
                'header'=> Mage::helper('catalog')->__('Course Code'),
                'width' => '80px',
                'index' => 'sku',
            ));*/
        $this->addColumn('entity_id',
            array(
                'header'=> Mage::helper('catalog')->__('ID'),
                'width' => '50px',
                'type'  => 'number',
                'index' => 'entity_id',
        ));
        $this->addColumn('course_requested_name',
            array(
                'header'=> Mage::helper('catalog')->__('Course Requested Name'),
                'index' => 'course_requested_name',
                'renderer' => 'bs_traininglist/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getId'
                ),
                'base_link' => '*/catalog_product/edit',
            ));

        $this->addColumn('sku',
            array(
                'header'=> Mage::helper('catalog')->__('Course Code'),
                'width' => '80px',
                'index' => 'sku',
            ));
        $this->addColumn('name',
            array(
                'header'=> Mage::helper('catalog')->__('Course Name'),
                'index' => 'name',
                'renderer' => 'bs_traininglist/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getId'
                ),
                'base_link' => '*/catalog_product/edit',
            ));

        $this->addColumn(
            'course_start_date',
            array(
                'header' => Mage::helper('catalog')->__('Start Date'),
                'index'  => 'course_start_date',
                'type'=> 'date',

            )
        );

        $this->addColumn(
            'course_finish_date',
            array(
                'header' => Mage::helper('catalog')->__('Finish Date'),
                'index'  => 'course_finish_date',
                'type'=> 'date',

            )
        );

        $this->addColumn(
            'conducting_place',
            array(
                'header' => Mage::helper('catalog')->__('Place'),
                'index'  => 'conducting_place',
                'type'  => 'options',
                'options' => Mage::helper('bs_traininglist')->convertOptions(
                    Mage::getModel('eav/config')->getAttribute('catalog_product', 'conducting_place')->getSource()->getAllOptions(false)
                )

            )
        );

        /*$this->addColumn(
            'course_status',
            array(
                'header'  => Mage::helper('catalog')->__('Course Status'),
                'index'   => 'course_status',
                'type'    => 'options',
                'options' => Mage::helper('bs_traininglist')->convertOptions(
                    Mage::getModel('eav/config')->getAttribute('catalog_product', 'course_status')->getSource()->getAllOptions(false)
                )
            )
        );*/

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_traininglist')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_traininglist')->__('Edit'),
                        'url'     => array('base'=> '*/catalog_product/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );




        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {

        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('product');

        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("catalog/products/delete");
        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_traininglist')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_traininglist')->__('Are you sure?')
                )
            );
        }

        $this->getMassactionBlock()->addItem(
            'generateseven',
            array(
                'label'      => Mage::helper('bs_traininglist')->__('Generate 8007'),
                'url'        => $this->getUrl('*/traininglist_curriculum/massGenerateSeven', array('_current'=>true, 'backto'=>'completecourse')),

            )
        );

        return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/traininglist_curriculum_completecourse/grid', array('_current'=>true));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/catalog_product/edit', array(
                'store'=>$this->getRequest()->getParam('store'),
                'id'=>$row->getId())
        );
    }
}
