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
class BS_Traininglist_Block_Adminhtml_Curriculum_Currentcourse_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('currentcourseGrid');
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
            ->addAttributeToFilter('status',1)
            ->addAttributeToFilter('plan_dispatch_no',array('notnull' => true))
            ->addAttributeToFilter('course_start_date',
                array('to' => $currentDate))
            ->addAttributeToFilter('course_finish_date',
                array('from' => $currentDate)
            );

//        ->addfieldtofilter('course_start_date',
//        array(
//            array('to' => $currentDate),
//            array('course_start_date', 'null'=>'')))
//        ->addfieldtofilter('course_finish_date',
//            array(
//                array('from' => $currentDate),
//                array('course_finish_date', 'null'=>''))
//        );

        ;


        //$collection->addAttributeToSelect('price');
        //$collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner');
        //$collection->joinAttribute('course_report', 'catalog_product/course_report', 'entity_id', null, 'inner');
        //$collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner');

        $this->setCollection($collection);


        parent::_prepareCollection();

        return $this;
    }


    protected function _prepareColumns()
    {
        $this->addColumn('name', array(
            'header'    => $this->__('Course Name'),
            'sortable'  => false,
            'index'     => 'name',
            'renderer' => 'bs_traininglist/adminhtml_helper_column_renderer_relation',
            'params' => array(
                'id' => 'getId'
            ),
            'base_link' => '*/catalog_product/edit',
            'width'     => '250px'

        ));

        $this->addColumn('sku', array(
            'header'    => $this->__('Course Code'),
            'sortable'  => false,
            'index'     => 'sku',
            'renderer' => 'bs_traininglist/adminhtml_helper_column_renderer_relation',
            'params' => array(
                'id' => 'getId'
            ),
            'base_link' => '*/catalog_product/edit',
            'width'     => '100px'

        ));
        $this->addColumn('course_start_date', array(
            'header'    => $this->__('Start Date'),
            'sortable'  => false,
            'type'      => 'date',
            'index'     => 'course_start_date',
            'width'     => '90px'
        ));
        $this->addColumn('course_finish_date', array(
            'header'    => $this->__('Finish Date'),
            'sortable'  => false,
            'type'      => 'date',
            'index'     => 'course_finish_date',
            'width'     => '90px'
        ));

        $this->addColumn('conducting_place', array(
            'header'    => $this->__('Place'),
            'index'     => 'conducting_place',
            'renderer' => 'bs_traininglist/adminhtml_helper_column_renderer_place',
            'width'     => '20px'


        ));

        $this->addColumn('instructor', array(
            'header'    => $this->__('Today Instructors'),
            'index'     => 'instructor',
            'renderer' => 'bs_traininglist/adminhtml_helper_column_renderer_instructor',
            'width'     => '100px'


        ));

        $this->addColumn('subject', array(
            'header'    => $this->__('Today Subjects'),
            'index'     => 'subject',
            'renderer' => 'bs_traininglist/adminhtml_helper_column_renderer_subject',
            'width'     => '200px'


        ));
        $this->addColumn('room', array(
            'header'    => $this->__('Room'),
            'index'     => 'room',
            'renderer' => 'bs_traininglist/adminhtml_helper_column_renderer_room',
            'width'     => '100px'


        ));
        $this->addColumn('course_note', array(
            'header'    => $this->__('Note'),
            'index'     => 'course_note',
            'width'     => '100px'


        ));
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
                'url'        => $this->getUrl('*/traininglist_curriculum/massGenerateSeven', array('_current'=>true, 'backto'=>'currentcourse')),

            )
        );

        return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/traininglist_curriculum_currentcourse/grid', array('_current'=>true));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/catalog_product/edit', array(
                'store'=>$this->getRequest()->getParam('store'),
                'id'=>$row->getId())
        );
    }
}
