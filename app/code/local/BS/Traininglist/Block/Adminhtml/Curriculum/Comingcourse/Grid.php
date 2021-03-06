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
class BS_Traininglist_Block_Adminhtml_Curriculum_Comingcourse_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('comingcourseGrid');
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
            ->addAttributeToFilter('course_report',0)
            ->addAttributeToFilter('course_start_date', array('from' => $currentDate))
            ->addAttributeToFilter('course_finish_date',array('from' => $currentDate))
        ;

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
        ));

        $this->addColumn('course_start_date', array(
            'header'    => $this->__('Start Date'),
            'sortable'  => false,
            'type'      => 'date',
            'index'     => 'course_start_date'
        ));

        $this->addColumn('course_finish_date', array(
            'header'    => $this->__('Finish Date'),
            'sortable'  => false,
            'type'      => 'date',
            'index'     => 'course_finish_date'
        ));

        $this->addColumn('number_trainees',
            array(
                'header'=> Mage::helper('catalog')->__('No. Trainees'),
                'index' => 'number_trainees',
                'filter_condition_callback' => array($this, '_filterTrainee'),
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
                'url'        => $this->getUrl('*/traininglist_curriculum/massGenerateSeven', array('_current'=>true, 'backto'=>'comingcourse')),

            )
        );

        return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/traininglist_curriculum_comingcourse/grid', array('_current'=>true));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/catalog_product/edit', array(
                'store'=>$this->getRequest()->getParam('store'),
                'id'=>$row->getId())
        );
    }
}
