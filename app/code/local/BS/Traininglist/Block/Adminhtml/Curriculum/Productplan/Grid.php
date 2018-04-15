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
class BS_Traininglist_Block_Adminhtml_Curriculum_Productplan_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('productplanGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);

    }



    protected function _prepareCollection()
    {

        $collection = Mage::getModel('catalog/product')->getCollection()

            ->addAttributeToSelect('*')
            ->addAttributeToFilter('status', 0)
            ->addAttributeToFilter('onhold', array(
                array('eq' => 0),
                array('null' => true),
            ))
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
        $this->addColumn(
            'course_type',
            array(
                'header' => Mage::helper('catalog')->__('Type'),
                'index'  => 'course_type',
                'type'  => 'options',
                'options' => Mage::helper('bs_traininglist')->convertOptions(
                    Mage::getModel('eav/config')->getAttribute('catalog_product', 'course_type')->getSource()->getAllOptions(false)
                )

            )
        );

        $this->addColumn('expected_conducting_month',
            array(
                'header'=> Mage::helper('catalog')->__('Expected Month'),
                'index' => 'expected_conducting_month',
                'filter_condition_callback' => array($this, '_filterMonth'),

            ));
        $this->addColumn(
            'course_plan_year',
            array(
                'header' => Mage::helper('catalog')->__('Expected Year'),
                'index'  => 'course_plan_year',
                'type'  => 'options',
                'options' => Mage::helper('bs_traininglist')->convertOptions(
                    Mage::getModel('eav/config')->getAttribute('catalog_product', 'course_plan_year')->getSource()->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'c_compliance_with',
            array(
                'header' => Mage::helper('catalog')->__('Compliance'),
                'index'  => 'c_compliance_with',
                'type'  => 'text',

                'renderer' => 'bs_traininglist/adminhtml_helper_column_renderer_coursecompliance',
                //'options' => Mage::helper('bs_traininglist')->convertOptions(
                //    Mage::getModel('eav/config')->getAttribute('bs_traininglist_curriculum', 'c_compliance_with')->getSource()->getAllOptions(false)
                //),
                //'filter_condition_callback' => array($this, 'filterCompliance'),
                'filter'    => false,
                'sortable'  => false,

            )
        );

        $this->addColumn(
            'hr_note',
            array(
                'header' => Mage::helper('catalog')->__('Note'),
                'index'  => 'hr_note',
                'renderer' => 'bs_traininglist/adminhtml_helper_column_renderer_hrnote',

            )
        );
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

    protected function _filterMonth($collection, $column){
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $values = array($value);

        if(strpos($value, ",")){
            $values = explode(",", $value);
            $values = array_map('trim', $values);

            $newValues = array();
            foreach ($values as $v) {
                if(strpos($v, "-")){
                    $keys = explode("-", $v);
                    $start = (int)$keys[0];
                    $end = (int)$keys[1];
                    for($i=$start; $i<= $end; $i++){
                        $newValues[] = $i;
                    }
                }else {
                    $newValues[] = $v;
                }
            }

            $values = $newValues;


        }

        $this->getCollection()->addFieldToFilter('expected_conducting_month', array('in' => $values));

    }




    protected function _prepareMassaction()
    {

        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('product');

        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed('catalog/products/delete');
        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_traininglist')->__('Delete'),
                    'url'  => $this->getUrl('*/catalog_product/massDelete', array('backto'=>'traininglist_curriculum_productplan')),
                    'confirm'  => Mage::helper('bs_traininglist')->__('Are you sure?')
                )
            );
        }

        $this->getMassactionBlock()->addItem(
            'generateseven',
            array(
                'label'      => Mage::helper('bs_traininglist')->__('Generate 8007'),
                'url'        => $this->getUrl('*/traininglist_curriculum/massGenerateSeven', array('_current'=>true, 'backto'=>'productplan')),

            )
        );

        if (Mage::getSingleton('admin/session')->isAllowed('catalog/update_attributes')){
            $this->getMassactionBlock()->addItem('attributes', array(
                'label' => Mage::helper('catalog')->__('Update Attributes'),
                'url'   => $this->getUrl('*/catalog_product_action_attribute/edit', array('_current'=>true))
            ));
        }

        return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/traininglist_curriculum_productplan/grid', array('_current'=>true));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/catalog_product/edit', array(
                'store'=>$this->getRequest()->getParam('store'),
                'id'=>$row->getId())
        );
    }

    protected function _afterToHtml($html)
    {
        $html .= "<script>
                    if($('productplanGrid_filter_conducting_place') != undefined){
                        $('productplanGrid_filter_conducting_place').observe('change', function(){
                            productplanGridJsObject.doFilter();
                        });
                    }

                    if($('productplanGrid_filter_course_type') != undefined){
                        $('productplanGrid_filter_course_type').observe('change', function(){
                            productplanGridJsObject.doFilter();
                        });
                    }

                    if($('productplanGrid_filter_course_plan_year') != undefined){
                        $('productplanGrid_filter_course_plan_year').observe('change', function(){
                            productplanGridJsObject.doFilter();
                        });
                    }

                    if($('productGrid_product_filter_course_type') != undefined){
                        $('productGrid_product_filter_course_type').observe('change', function(){
                            productplanGridJsObject.doFilter();
                        });
                    }






                </script>";
        return parent::_afterToHtml($html); // TODO: Change the autogenerated stub
    }
}
