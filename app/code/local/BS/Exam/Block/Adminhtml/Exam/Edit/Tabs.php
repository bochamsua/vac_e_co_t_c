<?php
/**
 * BS_Exam extension
 * 
 * @category       BS
 * @package        BS_Exam
 * @copyright      Copyright (c) 2015
 */
/**
 * Exam admin edit tabs
 *
 * @category    BS
 * @package     BS_Exam
 * @author Bui Phong
 */
class BS_Exam_Block_Adminhtml_Exam_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('exam_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_exam')->__('Exam'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Exam_Block_Adminhtml_Exam_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_exam',
            array(
                'label'   => Mage::helper('bs_exam')->__('Exam'),
                'title'   => Mage::helper('bs_exam')->__('Exam'),
                'content' => $this->getLayout()->createBlock(
                    'bs_exam/adminhtml_exam_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve exam entity
     *
     * @access public
     * @return BS_Exam_Model_Exam
     * @author Bui Phong
     */
    public function getExam()
    {
        return Mage::registry('current_exam');
    }
}
