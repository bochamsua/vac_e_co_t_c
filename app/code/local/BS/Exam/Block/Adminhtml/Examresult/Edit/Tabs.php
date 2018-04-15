<?php
/**
 * BS_Exam extension
 * 
 * @category       BS
 * @package        BS_Exam
 * @copyright      Copyright (c) 2015
 */
/**
 * Exam Result admin edit tabs
 *
 * @category    BS
 * @package     BS_Exam
 * @author Bui Phong
 */
class BS_Exam_Block_Adminhtml_Examresult_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('examresult_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_exam')->__('Exam Result'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Exam_Block_Adminhtml_Examresult_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_examresult',
            array(
                'label'   => Mage::helper('bs_exam')->__('Exam Result'),
                'title'   => Mage::helper('bs_exam')->__('Exam Result'),
                'content' => $this->getLayout()->createBlock(
                    'bs_exam/adminhtml_examresult_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve exam result entity
     *
     * @access public
     * @return BS_Exam_Model_Examresult
     * @author Bui Phong
     */
    public function getExamresult()
    {
        return Mage::registry('current_examresult');
    }
}
