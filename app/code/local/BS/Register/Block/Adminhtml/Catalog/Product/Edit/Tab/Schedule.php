<?php
/**
 * BS_Register extension
 * 
 * @category       BS
 * @package        BS_Register
 * @copyright      Copyright (c) 2015
 */
/**
 * Course Schedule tab on product edit form
 *
 * @category    BS
 * @package     BS_Register
 * @author Bui Phong
 */
class BS_Register_Block_Adminhtml_Catalog_Product_Edit_Tab_Schedule extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set grid params
     *
     * @access public
     * @author Bui Phong
     */

    protected $_defaultLimit = 50;
    public function __construct()
    {
        parent::__construct();
        $this->setId('schedule_grid');
        $this->setDefaultSort('schedule_order');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);

    }

    protected function _prepareLayout(){
        $this->setChild('add_schedule_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('New Schedule'),
                    'onclick'   => 'window.open(\''.$this->getUrl('*/register_schedule/new', array('_current'=>false, 'product_id'=>$this->getProduct()->getId(),'popup'=>true)).'\',\'\',\'width=1000,height=700,resizable=1,scrollbars=1\')'
                ))
        );

        $this->setChild('add_schedule_v2_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('New Schedule V2'),
                    'onclick'   => 'window.open(\''.$this->getUrl('*/register_schedule/new', array('_current'=>false, 'product_id'=>$this->getProduct()->getId(),'popup'=>true, 'v2' => true)).'\',\'\',\'width=1000,height=700,resizable=1,scrollbars=1\')'
                ))
        );

        $this->setChild('clear_schedule_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('Clear All Schedules'),
                    'onclick'   => 'deleteConfirm(\'This action should be taken VERY CAREFULLY. All SCHEDULES will be DELETED! Are you sure you want to do this?\',\''.$this->getUrl('*/register_schedule/clear', array('_current'=>false, 'product_id'=>$this->getProduct()->getId(),'popup'=>true)).'\')'
                ))
        );

        //window.open(url, '','width=1000,height=700,resizable=1,scrollbars=1');
        parent::_prepareLayout();
    }


    public function getMainButtonsHtml()
    {
        $html = '';
        if($this->getFilterVisibility()){
            $html.= $this->getChildHtml('add_schedule_button');
            $html.= $this->getChildHtml('add_schedule_v2_button');

            $isAllowed = Mage::getSingleton('admin/session')->isAllowed("catalog/bs_register/schedule/clearall");
            if($isAllowed){
                $html.= $this->getChildHtml('clear_schedule_button');
            }

            $html.= $this->getResetFilterButtonHtml();
            $html.= $this->getSearchButtonHtml();
        }
        return $html;

    }

    /**
     * prepare the schedule collection
     *
     * @access protected
     * @return BS_Register_Block_Adminhtml_Catalog_Product_Edit_Tab_Schedule
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_register/schedule_collection');
        if ($this->getProduct()->getId()) {
            $collection->addFieldToFilter('course_id', $this->getProduct()->getId());
        }

        //$collection->getSelect()->joinLeft(array('sub'=>'bs_subject_subject'),'subject_id = sub.entity_id','subject_name');
        $collection->getSelect()->joinLeft(array('ins'=>'bs_instructor_instructor_varchar'),'instructor_id = ins.entity_id AND ins.attribute_id = 270','ins.value');
        $collection->getSelect()->joinLeft(array('room'=>'bs_logistics_classroom'),'room_id = room.entity_id','classroom_name');

        $this->setCollection($collection);
        parent::_prepareCollection();
        $this->_prepareTotals('schedule_hours');
        return $this;
    }

    /**
     * prepare mass action grid
     *
     * @access protected
     * @return BS_Register_Block_Adminhtml_Catalog_Product_Edit_Tab_Schedule
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        return $this;
    }

    /**
     * prepare the grid columns
     *
     * @access protected
     * @return BS_Register_Block_Adminhtml_Catalog_Product_Edit_Tab_Schedule
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {

        $this->addColumn(
            'schedule_start_date',
            array(
                'header' => Mage::helper('bs_register')->__('From Date'),
                'index'  => 'schedule_start_date',
                'type'=> 'date',
                'totals_label'      => Mage::helper('bs_register')->__('Total hours')

            )
        );
        $this->addColumn(
            'schedule_finish_date',
            array(
                'header' => Mage::helper('bs_register')->__('To Date'),
                'index'  => 'schedule_finish_date',
                'type'=> 'date',
                'totals_label'      => ''

            )
        );

        /*$this->addColumn(
            'subject_id',
            array(
                'header'    => Mage::helper('bs_register')->__('Subject'),
                'type'      => 'text',
                'renderer'  => 'bs_register/adminhtml_helper_column_renderer_subject',
                'filter_condition_callback' => array($this, '_subjectFilter'),

            )
        );*/

        $this->addColumn(
            'schedule_subject_names',
            array(
                'header'    => Mage::helper('bs_register')->__('Subject(s)'),
                'type'      => 'text',
                'index'     => 'schedule_subject_names',
                //'renderer'  => 'bs_register/adminhtml_helper_column_renderer_schedulesubjects',
                'totals_label'      => '',

            )
        );

        $this->addColumn(
            'instructor_id',
            array(
                'header'    => Mage::helper('bs_register')->__('Instructor'),
                'type'      => 'text',
                'renderer'  => 'bs_register/adminhtml_helper_column_renderer_instructor',
                'filter_condition_callback' => array($this, '_instructorFilter'),
                'totals_label'      => ''

            )
        );
        $this->addColumn(
            'schedule_hours',
            array(
                'header'    => Mage::helper('bs_register')->__('Total Hours'),
                'align'     => 'left',
                'index'     => 'schedule_hours',
            )
        );

        $this->addColumn(
            'room_id',
            array(
                'header'    => Mage::helper('bs_register')->__('Room'),
                'type'      => 'text',
                'renderer'  => 'bs_register/adminhtml_helper_column_renderer_room',
                'filter_condition_callback' => array($this, '_roomFilter'),
                'totals_label'      => ''

            )
        );




        /*$this->addColumn(
            'schedule_start_time',
            array(
                'header' => Mage::helper('bs_register')->__('Start Time'),
                'index'  => 'schedule_start_time',
                'renderer'  => 'bs_register/adminhtml_helper_column_renderer_starttime',
                'filter'=>false

            )
        );*/

        /*$this->addColumn(
            'schedule_finish_time',
            array(
                'header' => Mage::helper('bs_register')->__('Finish Time'),
                'index'  => 'schedule_finish_time',
                'renderer'  => 'bs_register/adminhtml_helper_column_renderer_finishtime',
                'filter'=>false

            )
        );*/


        $this->addColumn(
            'schedule_order',
            array(
                'header'    => Mage::helper('bs_register')->__('Order'),
                'align'     => 'left',
                'index'     => 'schedule_order',
                'editable'       => true,
                'type'          => 'input',
                'renderer'      => 'bs_register/adminhtml_helper_column_renderer_scheduleinput',
                'totals_label'      => ''
            )
        );
        $this->addColumn(
            'schedule_note',
            array(
                'header'    => Mage::helper('bs_register')->__('Note'),
                'align'     => 'left',
                'index'     => 'schedule_note',
                'totals_label'      => ''
            )
        );

        $this->addColumn('action',
            array(
                'header'    => Mage::helper('catalog')->__('Action'),
                'width'     => '100px',
                'type'      => 'text',
                'edit_link' => '*/register_schedule/edit',
                'delete_link' => '*/register_schedule/delete',
                'allowed_edit'          => 'catalog/bs_register/schedule/edit',
                'allowed_delete'          => 'catalog/bs_register/schedule/delete',
                'grid_id'          => $this->getId().'JsObject',
                'renderer'  => 'bs_register/adminhtml_helper_column_renderer_act',
                'totals_label'      => '',
                'filter'    => false,
                'sortable'  => false
            )
        );

        return parent::_prepareColumns();
    }


    /**
     * instructor callback filter
     *
     * @access protected
     * @return BS_Register_Block_Adminhtml_Schedule_Grid
     * @author Bui Phong
     */

    protected function _instructorFilter($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }

        $this->getCollection()->getSelect()->where(
            "ins.value LIKE ?"
            , "%$value%");


        return $this;
    }



    /**
     * room callback filter
     *
     * @access protected
     * @return BS_Register_Block_Adminhtml_Schedule_Grid
     * @author Bui Phong
     */

    protected function _roomFilter($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }

        $this->getCollection()->getSelect()->where(
            "classroom_name LIKE ?"
            , "%$value%");


        return $this;
    }

    public function getRowUrl($item)
    {
        return '#';//$this->getUrl('*/register_schedule/edit', array('id' => $item->getId()));
    }

    /**
     * get grid url
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getGridUrl()
    {
        return $this->getUrl(
            '*/*/schedulesGrid',
            array(
                'id'=>$this->getProduct()->getId()
            )
        );
    }

    /**
     * get the current product
     *
     * @access public
     * @return Mage_Catalog_Model_Product
     * @author Bui Phong
     */
    public function getProduct()
    {
        return Mage::registry('current_product');
    }

    protected function _prepareTotals($columns = 'schedule_hours'){
        $columns=explode(',',$columns);
        if(!$columns){
            return;
        }
        $this->_countTotals = true;
        $totals = new Varien_Object();
        $fields = array();
        foreach($columns as $column){
            $fields[$column]    = 0;
        }

        foreach ($this->getCollection() as $item) {
            foreach($fields as $field=>$value){
                $fields[$field]+=$item->getData($field);
            }
        }

        $totals->setData($fields);
        $this->setTotals($totals);
        return;
    }

    protected function _afterToHtml($html)
    {

        $html1 = "<script>

                </script>";
        return parent::_afterToHtml($html);
    }
}
