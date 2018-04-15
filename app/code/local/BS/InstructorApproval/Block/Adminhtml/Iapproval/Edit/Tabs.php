<?php
/**
 * BS_InstructorApproval extension
 * 
 * @category       BS
 * @package        BS_InstructorApproval
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Approval admin edit tabs
 *
 * @category    BS
 * @package     BS_InstructorApproval
 * @author Bui Phong
 */
class BS_InstructorApproval_Block_Adminhtml_Iapproval_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('iapproval_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_instructorapproval')->__('Instructor Approval'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_InstructorApproval_Block_Adminhtml_Iapproval_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_iapproval',
            array(
                'label'   => Mage::helper('bs_instructorapproval')->__('Instructor Approval'),
                'title'   => Mage::helper('bs_instructorapproval')->__('Instructor Approval'),
                'content' => $this->getLayout()->createBlock(
                    'bs_instructorapproval/adminhtml_iapproval_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve instructor approval entity
     *
     * @access public
     * @return BS_InstructorApproval_Model_Iapproval
     * @author Bui Phong
     */
    public function getIapproval()
    {
        return Mage::registry('current_iapproval');
    }
}
