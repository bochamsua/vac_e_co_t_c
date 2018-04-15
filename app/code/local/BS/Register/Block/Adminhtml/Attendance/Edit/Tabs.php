<?php
/**
 * BS_Register extension
 * 
 * @category       BS
 * @package        BS_Register
 * @copyright      Copyright (c) 2015
 */
/**
 * Attendance admin edit tabs
 *
 * @category    BS
 * @package     BS_Register
 * @author Bui Phong
 */
class BS_Register_Block_Adminhtml_Attendance_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('attendance_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_register')->__('Absence Record'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Register_Block_Adminhtml_Attendance_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_attendance',
            array(
                'label'   => Mage::helper('bs_register')->__('Absence Record'),
                'title'   => Mage::helper('bs_register')->__('Absence Record'),
                'content' => $this->getLayout()->createBlock(
                    'bs_register/adminhtml_attendance_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve attendance entity
     *
     * @access public
     * @return BS_Register_Model_Attendance
     * @author Bui Phong
     */
    public function getAttendance()
    {
        return Mage::registry('current_attendance');
    }
}
