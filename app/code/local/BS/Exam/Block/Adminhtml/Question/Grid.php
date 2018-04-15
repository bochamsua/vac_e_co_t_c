<?php
/**
 * BS_Exam extension
 * 
 * @category       BS
 * @package        BS_Exam
 * @copyright      Copyright (c) 2015
 */
/**
 * Question admin grid block
 *
 * @category    BS
 * @package     BS_Exam
 * @author Bui Phong
 */
class BS_Exam_Block_Adminhtml_Question_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('questionGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Exam_Block_Adminhtml_Question_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_exam/question')
            ->getCollection();

        $collection->getSelect()->joinLeft(array('sub'=>'bs_subject_subject'),'subject_id = sub.entity_id','subject_name');//351 -- name, 352 -- code
        $collection->getSelect()->joinLeft(array('cu'=>'bs_traininglist_curriculum_varchar'),'main_table.curriculum_id = cu.entity_id AND attribute_id = 225','cu.value');
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Exam_Block_Adminhtml_Question_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        /*$this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_exam')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );*/
        $this->addColumn(
            'curriculum_id',
            array(
                'header'    => Mage::helper('bs_subject')->__('Curriculum'),
                'type'      => 'text',
                'renderer'  => 'bs_subject/adminhtml_helper_column_renderer_curriculum',
                'filter_condition_callback' => array($this, '_curriculumFilter'),

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
            'question_question',
            array(
                'header'    => Mage::helper('bs_exam')->__('Question'),
                'align'     => 'left',
                'index'     => 'question_question',
            )
        );




        $this->addColumn(
            'question_level',
            array(
                'header' => Mage::helper('bs_exam')->__('Level'),
                'index'  => 'question_level',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'question_order',
            array(
                'header' => Mage::helper('bs_exam')->__('Sort Order'),
                'index'  => 'question_order',
                'type'=> 'number',

            )
        );
        $this->addColumn(
            'question_usage',
            array(
                'header' => Mage::helper('bs_exam')->__('Usage'),
                'index'  => 'question_usage',
                'type'=> 'number',

            )
        );
        /*$this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_exam')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_exam')->__('Enabled'),
                    '0' => Mage::helper('bs_exam')->__('Disabled'),
                )
            )
        );*/

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_exam')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_exam')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_exam')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_exam')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_exam')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * subject callback filter
     *
     * @access protected
     * @return BS_Register_Block_Adminhtml_Schedule_Grid
     * @author Bui Phong
     */

    protected function _curriculumFilter($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }

        $this->getCollection()->getSelect()->where(
            "cu.value LIKE ?"
            , "%$value%");


        return $this;
    }

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

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_Exam_Block_Adminhtml_Question_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('question');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_exam/question/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_exam/question/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_exam')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_exam')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_exam')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_exam')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_exam')->__('Enabled'),
                                '0' => Mage::helper('bs_exam')->__('Disabled'),
                            )
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
     * @param BS_Exam_Model_Question
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
     * @return BS_Exam_Block_Adminhtml_Question_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
