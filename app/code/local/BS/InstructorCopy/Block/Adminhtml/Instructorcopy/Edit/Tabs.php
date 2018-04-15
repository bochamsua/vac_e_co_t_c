<?php
/**
 * BS_InstructorCopy extension
 * 
 * @category       BS
 * @package        BS_InstructorCopy
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Copy admin edit tabs
 *
 * @category    BS
 * @package     BS_InstructorCopy
 * @author Bui Phong
 */
class BS_InstructorCopy_Block_Adminhtml_Instructorcopy_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('instructorcopy_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_instructorcopy')->__('Instructor Copy'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_InstructorCopy_Block_Adminhtml_Instructorcopy_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_instructorcopy',
            array(
                'label'   => Mage::helper('bs_instructorcopy')->__('Instructor Copy'),
                'title'   => Mage::helper('bs_instructorcopy')->__('Instructor Copy'),
                'content' => $this->getLayout()->createBlock(
                    'bs_instructorcopy/adminhtml_instructorcopy_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve instructor copy entity
     *
     * @access public
     * @return BS_InstructorCopy_Model_Instructorcopy
     * @author Bui Phong
     */
    public function getInstructorcopy()
    {
        return Mage::registry('current_instructorcopy');
    }
}
