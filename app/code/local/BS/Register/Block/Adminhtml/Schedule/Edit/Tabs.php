<?php
/**
 * BS_Register extension
 * 
 * @category       BS
 * @package        BS_Register
 * @copyright      Copyright (c) 2015
 */
/**
 * Course Schedule admin edit tabs
 *
 * @category    BS
 * @package     BS_Register
 * @author Bui Phong
 */
class BS_Register_Block_Adminhtml_Schedule_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('schedule_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_register')->__('Course Schedule'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Register_Block_Adminhtml_Schedule_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_schedule',
            array(
                'label'   => Mage::helper('bs_register')->__('Course Schedule'),
                'title'   => Mage::helper('bs_register')->__('Course Schedule'),
                'content' => $this->getLayout()->createBlock(
                    'bs_register/adminhtml_schedule_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve course schedule entity
     *
     * @access public
     * @return BS_Register_Model_Schedule
     * @author Bui Phong
     */
    public function getSchedule()
    {
        return Mage::registry('current_schedule');
    }
}
