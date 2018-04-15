<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Exam Trainee admin edit tabs
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Block_Adminhtml_Examtrainee_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('examtrainee_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_docwise')->__('Exam Trainee'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Docwise_Block_Adminhtml_Examtrainee_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_examtrainee',
            array(
                'label'   => Mage::helper('bs_docwise')->__('Exam Trainee'),
                'title'   => Mage::helper('bs_docwise')->__('Exam Trainee'),
                'content' => $this->getLayout()->createBlock(
                    'bs_docwise/adminhtml_examtrainee_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve exam trainee entity
     *
     * @access public
     * @return BS_Docwise_Model_Examtrainee
     * @author Bui Phong
     */
    public function getExamtrainee()
    {
        return Mage::registry('current_examtrainee');
    }
}
