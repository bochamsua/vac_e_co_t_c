<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Function admin edit tabs
 *
 * @category    BS
 * @package     BS_Instructor
 * @author Bui Phong
 */
class BS_Instructor_Block_Adminhtml_Instructorfunction_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('instructorfunction_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_instructor')->__('Instructor Function'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Instructor_Block_Adminhtml_Instructorfunction_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_instructorfunction',
            array(
                'label'   => Mage::helper('bs_instructor')->__('Instructor Function'),
                'title'   => Mage::helper('bs_instructor')->__('Instructor Function'),
                'content' => $this->getLayout()->createBlock(
                    'bs_instructor/adminhtml_instructorfunction_edit_tab_form'
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
     * @return BS_Instructor_Model_Instructorfunction
     * @author Bui Phong
     */
    public function getInstructorfunction()
    {
        return Mage::registry('current_instructorfunction');
    }
}
