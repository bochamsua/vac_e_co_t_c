<?php
/**
 * BS_Tasktraining extension
 * 
 * @category       BS
 * @package        BS_Tasktraining
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor admin edit tabs
 *
 * @category    BS
 * @package     BS_Tasktraining
 * @author Bui Phong
 */
class BS_Tasktraining_Block_Adminhtml_Taskinstructor_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('taskinstructor_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_tasktraining')->__('Instructor'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Tasktraining_Block_Adminhtml_Taskinstructor_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_taskinstructor',
            array(
                'label'   => Mage::helper('bs_tasktraining')->__('Instructor'),
                'title'   => Mage::helper('bs_tasktraining')->__('Instructor'),
                'content' => $this->getLayout()->createBlock(
                    'bs_tasktraining/adminhtml_taskinstructor_edit_tab_form'
                )
                ->toHtml(),
            )
        );
//        $this->addTab(
//            'categories',
//            array(
//                'label' => Mage::helper('bs_tasktraining')->__('Associated categories'),
//                'url'   => $this->getUrl('*/*/categories', array('_current' => true)),
//                'class' => 'ajax'
//            )
//        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve instructor entity
     *
     * @access public
     * @return BS_Tasktraining_Model_Taskinstructor
     * @author Bui Phong
     */
    public function getTaskinstructor()
    {
        return Mage::registry('current_taskinstructor');
    }
}
