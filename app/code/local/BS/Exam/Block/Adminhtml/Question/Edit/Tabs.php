<?php
/**
 * BS_Exam extension
 * 
 * @category       BS
 * @package        BS_Exam
 * @copyright      Copyright (c) 2015
 */
/**
 * Question admin edit tabs
 *
 * @category    BS
 * @package     BS_Exam
 * @author Bui Phong
 */
class BS_Exam_Block_Adminhtml_Question_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('question_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_exam')->__('Question'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Exam_Block_Adminhtml_Question_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_question',
            array(
                'label'   => Mage::helper('bs_exam')->__('Question'),
                'title'   => Mage::helper('bs_exam')->__('Question'),
                'content' => $this->getLayout()->createBlock(
                    'bs_exam/adminhtml_question_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve question entity
     *
     * @access public
     * @return BS_Exam_Model_Question
     * @author Bui Phong
     */
    public function getQuestion()
    {
        return Mage::registry('current_question');
    }
}
