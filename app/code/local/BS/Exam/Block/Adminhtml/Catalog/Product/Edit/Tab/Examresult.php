<?php
/**
 * BS_Register extension
 * 
 * @category       BS
 * @package        BS_Register
 * @copyright      Copyright (c) 2015
 */

class BS_Exam_Block_Adminhtml_Catalog_Product_Edit_Tab_Examresult extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set grid params
     *
     * @access public
     * @author Bui Phong
     */

    public function __construct()
    {
        parent::__construct();
        $this->setId('examresult_grid');
       // $this->setDefaultSort('position');
        //$this->setDefaultDir('DESC');
        $this->setUseAjax(true);

    }

    protected function _prepareLayout(){
        $this->setChild('first_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('1st Score'),
                    'onclick'   => 'setLocation(\''.$this->getUrl('*/catalog_product/edit', array('_current'=>false, 'id'=>$this->getProduct()->getId(), 'back' => 'edit/tab/product_info_tabs_examresults')).'\')'
                ))
        );

        $this->setChild('second_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('2nd Score'),
                    'onclick'   => 'setLocation(\''.$this->getUrl('*/catalog_product/edit', array('_current'=>false, 'id'=>$this->getProduct()->getId(), 'tm' => 2, 'back' => 'edit/tab/product_info_tabs_examresults')).'\')'
                ))
        );

        $this->setChild('fillout_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('Fill Out Subjects'),
                    'onclick'   => 'setLocation(\''.$this->getUrl('*/exam_examresult/fill', array('_current'=>false, 'product_id'=>$this->getProduct()->getId(),'popup'=>true)).'\')'
                ))
        );
        $this->setChild('clear_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('Clear Results'),
                    'onclick'   => 'deleteConfirm(\''. Mage::helper('adminhtml')->__('This will clear all existing results. Are you sure you want to do this?').'\',\''.$this->getUrl('*/exam_examresult/clear', array('_current'=>false, 'product_id'=>$this->getProduct()->getId())).'\')'
                ))
        );

        $this->setTemplate('bs_exam/grid.phtml');

        parent::_prepareLayout();
    }


    public function getMainButtonsHtml()
    {
        $isAllowedNew = Mage::getSingleton('admin/session')->isAllowed("bs_exam/examresult/new");
        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_exam/examresult/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_exam/examresult/delete");

        $html = '';
        if($this->getFilterVisibility()){
            //$html.= $this->getChildHtml('add_examresult_button');

            $times = $this->getMarkTime();
            if($times > 1){
                $html.= $this->getChildHtml('first_button');
            }else {
                $html.= $this->getChildHtml('second_button');
            }

            if($isAllowedEdit){
                $html.= $this->getChildHtml('fillout_button');
            }

            if($isAllowedDelete){
                $html.= $this->getChildHtml('clear_button');
            }

            //$html.= $this->getResetFilterButtonHtml();
            //$html.= $this->getSearchButtonHtml();
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
        /*$collection = Mage::getModel('bs_exam/examresult')->getCollection()
                    ->addFieldToFilter('course_id', $this->getProduct()->getId())
        ;

        //$collection->getSelect()->joinLeft(array('pro'=>'catalog_product_entity'),'course_id = pro.entity_id','sku');
        $collection->getSelect()->joinLeft(array('sub'=>'bs_subject_subject'),'subject_id = sub.entity_id','subject_name');//351 -- name, 352 -- code
        $collection->getSelect()->joinLeft(array('trainee'=>'bs_trainee_trainee_varchar'),'trainee_id = trainee.entity_id AND trainee.attribute_id = 276','trainee.value');

        $this->setCollection($collection);*/
        parent::_prepareCollection();
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
            'trainee_id',
            array(
                'header'    => Mage::helper('bs_exam')->__('Trainee'),
                'type'      => 'text',
                'renderer'  => 'bs_exam/adminhtml_helper_column_renderer_trainee',
                'filter_condition_callback' => array($this, '_traineeFilter'),
            )
        );

        $this->addColumn(
            'subject_id',
            array(
                'header'    => Mage::helper('bs_exam')->__('Subject'),
                'type'      => 'text',
                'renderer'  => 'bs_exam/adminhtml_helper_column_renderer_subject',
                'filter_condition_callback' => array($this, '_subjectFilter'),

            )
        );



        $this->addColumn(
            'first_mark',
            array(
                'header'    => Mage::helper('bs_exam')->__('1st Mark'),
                'align'     => 'left',
                'index'     => 'first_mark',
                'editable'       => true,
                'type'          => 'input',
                'renderer'      => 'bs_exam/adminhtml_helper_column_renderer_mark',
                'filter'    => false,
                'mark'      => 'first'
            )
        );



        $this->addColumn(
            'second_mark',
            array(
                'header' => Mage::helper('bs_exam')->__('2nd Mark'),
                'index'  => 'second_mark',
                'editable'       => true,
                'type'          => 'input',
                'renderer'      => 'bs_exam/adminhtml_helper_column_renderer_mark',
                'filter'    => false,
                'mark'      => 'second'

            )
        );
        $this->addColumn(
            'third_mark',
            array(
                'header' => Mage::helper('bs_exam')->__('3rd Mark'),
                'index'  => 'third_mark',
                'editable'       => true,
                'type'          => 'input',
                'renderer'      => 'bs_exam/adminhtml_helper_column_renderer_mark',
                'filter'    => false,
                'mark'      => 'third'

            )
        );
        $this->addColumn(
            'note',
            array(
                'header' => Mage::helper('bs_exam')->__('Note'),
                'index'  => 'note',
                'type'=> 'text',

            )
        );

        $this->addColumn('action',
            array(
                'header'    => Mage::helper('catalog')->__('Action'),
                'width'     => '50px',
                'type'      => 'action',
                'getter'     => 'getId',
                'actions'   => array(
                    array(
                        'caption' => 'Edit',
                        'url'     => array('base' => '*/exam_examresult/edit', 'params' => array('popup'=> 1)),
                        'field'   => 'id',
                        'onclick'   => 'window.open(this.href,\'\',\'width=1000,height=700,resizable=1,scrollbars=1\'); return false;'
                    )
                ),
                'filter'    => false,
                'sortable'  => false
            )
        );

        return parent::_prepareColumns();
    }



    protected function _traineeFilter($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }

        $this->getCollection()->getSelect()->where(
            "trainee.value LIKE ?"
            , "%$value%");


        return $this;
    }

    /**
     * subject callback filter
     *
     * @access protected
     * @return BS_Register_Block_Adminhtml_Schedule_Grid
     * @author Bui Phong
     */

    protected function _subjectFilter($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }

        $this->getCollection()->getSelect()->where(
            "subject_name LIKE ?"
            , "%$value%");


        return $this;
    }


    public function getRowUrl($item)
    {
        return '#';//$this->getUrl('*/register_attendance/edit', array('id' => $item->getId()));
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
            '*/*/examresultsGrid',
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

    public function getSubject($subjectId){
        $subject = Mage::getModel('bs_subject/subject')->load($subjectId);


        if ($subject) {
            $name = $subject->getSubjectName();
            if ($subject->getSubjectShortcode() != '') {
                $name = $subject->getSubjectShortcode();
            }

            return $name;
        }

        return false;
    }

    public function getTrainees(){
        $resource = Mage::getSingleton('core/resource');
        $writeConnection = $resource->getConnection('core_write');
        $readConnection = $resource->getConnection('core_read');

        $traineeIds = $readConnection->fetchCol("SELECT DISTINCT trainee_id FROM bs_exam_examresult WHERE course_id = ".$this->getProduct()->getId());
        if(count($traineeIds)){
            $result = array();
            foreach ($traineeIds as $traineeId) {
                $trainee = Mage::getModel('bs_trainee/trainee')->load($traineeId);

                $name = $trainee->getTraineeName();
                $vaecoId = $trainee->getVaecoId();
                if($vaecoId == ''){
                    $vaecoId = $trainee->getTraineeCode();
                }

                $name = $name . ' ('.$vaecoId.')';

                $marks = $this->getTraineeMark($traineeId);




                $result[] = array(
                    'id'    => $traineeId,
                    'name'  => $name,
                    'marks'  => $marks
                );
            }

            return $result;

        }
        return false;
    }

    public function getTraineeMark($traineeId){
        $resource = Mage::getSingleton('core/resource');
        $writeConnection = $resource->getConnection('core_write');
        $readConnection = $resource->getConnection('core_read');

        $subjects = $this->getSubjects();

        $time = $this->getMarkTime();
        $result = array();
        $marks = $readConnection->fetchAll("SELECT * FROM bs_exam_examresult WHERE course_id = ".$this->getProduct()->getId()." AND trainee_id = ".$traineeId);
        if(count($marks)){
            if($time == 1){
                foreach ($marks as $mark) {
                    $subjectId = $mark['subject_id'];
                    $result[$subjectId] = $mark['first_mark'];

                }
            }else {
                foreach ($marks as $mark) {
                    $subjectId = $mark['subject_id'];
                    $result[$subjectId] = $mark['second_mark'];

                }
            }

            return $result;
        }


        return false;
    }

    public function getMarkTime(){
        $times = $this->getRequest()->getParam('tm', false);
        if($times){
            return $times;
        }
        return 1;
    }

}
