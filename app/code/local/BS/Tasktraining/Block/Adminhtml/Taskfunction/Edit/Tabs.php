<?php
/**
 * BS_Tasktraining extension
 * 
 * @category       BS
 * @package        BS_Tasktraining
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Function admin edit tabs
 *
 * @category    BS
 * @package     BS_Tasktraining
 * @author Bui Phong
 */
class BS_Tasktraining_Block_Adminhtml_Taskfunction_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('taskfunction_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_tasktraining')->__('Instructor Function'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Tasktraining_Block_Adminhtml_Taskfunction_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_taskfunction',
            array(
                'label'   => Mage::helper('bs_tasktraining')->__('Instructor Function'),
                'title'   => Mage::helper('bs_tasktraining')->__('Instructor Function'),
                'content' => $this->getLayout()->createBlock(
                    'bs_tasktraining/adminhtml_taskfunction_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve instructor function entity
     *
     * @access public
     * @return BS_Tasktraining_Model_Taskfunction
     * @author Bui Phong
     */
    public function getTaskfunction()
    {
        return Mage::registry('current_taskfunction');
    }
}
