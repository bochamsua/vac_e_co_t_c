<?php
/**
 * BS_Exam extension
 * 
 * @category       BS
 * @package        BS_Exam
 * @copyright      Copyright (c) 2015
 */
/**
 * Answer admin edit tabs
 *
 * @category    BS
 * @package     BS_Exam
 * @author Bui Phong
 */
class BS_Exam_Block_Adminhtml_Answer_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('answer_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_exam')->__('Answer'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Exam_Block_Adminhtml_Answer_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_answer',
            array(
                'label'   => Mage::helper('bs_exam')->__('Answer'),
                'title'   => Mage::helper('bs_exam')->__('Answer'),
                'content' => $this->getLayout()->createBlock(
                    'bs_exam/adminhtml_answer_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve answer entity
     *
     * @access public
     * @return BS_Exam_Model_Answer
     * @author Bui Phong
     */
    public function getAnswer()
    {
        return Mage::registry('current_answer');
    }
}
