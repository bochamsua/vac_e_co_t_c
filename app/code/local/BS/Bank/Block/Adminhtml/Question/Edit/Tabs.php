<?php
/**
 * BS_Bank extension
 * 
 * @category       BS
 * @package        BS_Bank
 * @copyright      Copyright (c) 2015
 */
/**
 * Question admin edit tabs
 *
 * @category    BS
 * @package     BS_Bank
 * @author      Bui Phong
 */
class BS_Bank_Block_Adminhtml_Question_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setTitle(Mage::helper('bs_bank')->__('Question'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Bank_Block_Adminhtml_Question_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_question',
            array(
                'label'   => Mage::helper('bs_bank')->__('Question'),
                'title'   => Mage::helper('bs_bank')->__('Question'),
                'content' => $this->getLayout()->createBlock(
                    'bs_bank/adminhtml_question_edit_tab_form'
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
     * @return BS_Bank_Model_Question
     * @author Bui Phong
     */
    public function getQuestion()
    {
        return Mage::registry('current_question');
    }
}
