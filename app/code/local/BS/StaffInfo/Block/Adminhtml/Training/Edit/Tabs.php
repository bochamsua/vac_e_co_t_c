<?php
/**
 * BS_StaffInfo extension
 * 
 * @category       BS
 * @package        BS_StaffInfo
 * @copyright      Copyright (c) 2015
 */
/**
 * Related Training admin edit tabs
 *
 * @category    BS
 * @package     BS_StaffInfo
 * @author Bui Phong
 */
class BS_StaffInfo_Block_Adminhtml_Training_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('training_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_staffinfo')->__('Related Training'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_StaffInfo_Block_Adminhtml_Training_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_training',
            array(
                'label'   => Mage::helper('bs_staffinfo')->__('Related Training'),
                'title'   => Mage::helper('bs_staffinfo')->__('Related Training'),
                'content' => $this->getLayout()->createBlock(
                    'bs_staffinfo/adminhtml_training_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve related training entity
     *
     * @access public
     * @return BS_StaffInfo_Model_Training
     * @author Bui Phong
     */
    public function getTraining()
    {
        return Mage::registry('current_training');
    }
}
