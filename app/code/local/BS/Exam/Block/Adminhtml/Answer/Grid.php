<?php
/**
 * BS_Exam extension
 * 
 * @category       BS
 * @package        BS_Exam
 * @copyright      Copyright (c) 2015
 */
/**
 * Answer admin grid block
 *
 * @category    BS
 * @package     BS_Exam
 * @author Bui Phong
 */
class BS_Exam_Block_Adminhtml_Answer_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('answerGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Exam_Block_Adminhtml_Answer_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_exam/answer')
            ->getCollection();

        $collection->getSelect()->joinLeft(array('question'=>'bs_exam_question'),'question_id = question.entity_id','question_question');
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Exam_Block_Adminhtml_Answer_Grid
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
            'question_id',
            array(
                'header'    => Mage::helper('bs_exam')->__('Question'),
                'type'      => 'text',
                'renderer'  => 'bs_exam/adminhtml_helper_column_renderer_question',
                'filter_condition_callback' => array($this, '_questionFilter'),

            )
        );

        /*$this->addColumn(
            'question_id',
            array(
                'header'    => Mage::helper('bs_exam')->__('Question'),
                'index'     => 'question_id',
                'type'      => 'options',
                'options'   => Mage::getResourceModel('bs_exam/question_collection')
                    ->toOptionHash(),
                'renderer'  => 'bs_exam/adminhtml_helper_column_renderer_parent',
                'params'    => array(
                    'id'    => 'getQuestionId'
                ),
                'base_link' => 'adminhtml/exam_question/edit'
            )
        );*/
        $this->addColumn(
            'answer_answer',
            array(
                'header'    => Mage::helper('bs_exam')->__('Answer'),
                'align'     => 'left',
                'index'     => 'answer_answer',
            )
        );
        

        $this->addColumn(
            'answer_correct',
            array(
                'header' => Mage::helper('bs_exam')->__('Correct'),
                'index'  => 'answer_correct',
                'type'    => 'options',
                    'options'    => array(
                    '1' => Mage::helper('bs_exam')->__('Yes'),
                    '0' => Mage::helper('bs_exam')->__('No'),
                )

            )
        );
        $this->addColumn(
            'answer_position',
            array(
                'header' => Mage::helper('bs_exam')->__('Position'),
                'index'  => 'answer_position',
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


    protected function _questionFilter($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }

        $this->getCollection()->getSelect()->where(
            "question_question LIKE ?"
            , "%$value%");


        return $this;
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_Exam_Block_Adminhtml_Answer_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('answer');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_exam/answer/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_exam/answer/delete");

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




        $this->getMassactionBlock()->addItem(
            'answer_correct',
            array(
                'label'      => Mage::helper('bs_exam')->__('Change Correct'),
                'url'        => $this->getUrl('*/*/massAnswerCorrect', array('_current'=>true)),
                'additional' => array(
                    'flag_answer_correct' => array(
                        'name'   => 'flag_answer_correct',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_exam')->__('Correct'),
                        'values' => array(
                                '1' => Mage::helper('bs_exam')->__('Yes'),
                                '0' => Mage::helper('bs_exam')->__('No'),
                            )

                    )
                )
            )
        );
        $values = Mage::getResourceModel('bs_exam/question_collection')->toOptionHash();
        $values = array_reverse($values, true);
        $values[''] = '';
        $values = array_reverse($values, true);
        $this->getMassactionBlock()->addItem(
            'question_id',
            array(
                'label'      => Mage::helper('bs_exam')->__('Change Question'),
                'url'        => $this->getUrl('*/*/massQuestionId', array('_current'=>true)),
                'additional' => array(
                    'flag_question_id' => array(
                        'name'   => 'flag_question_id',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_exam')->__('Question'),
                        'values' => $values
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
     * @param BS_Exam_Model_Answer
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
     * @return BS_Exam_Block_Adminhtml_Answer_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
