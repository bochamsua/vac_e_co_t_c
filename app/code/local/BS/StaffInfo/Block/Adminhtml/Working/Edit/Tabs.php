<?php
/**
 * BS_StaffInfo extension
 * 
 * @category       BS
 * @package        BS_StaffInfo
 * @copyright      Copyright (c) 2015
 */
/**
 * Related Working admin edit tabs
 *
 * @category    BS
 * @package     BS_StaffInfo
 * @author Bui Phong
 */
class BS_StaffInfo_Block_Adminhtml_Working_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('working_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_staffinfo')->__('Related Working'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_StaffInfo_Block_Adminhtml_Working_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_working',
            array(
                'label'   => Mage::helper('bs_staffinfo')->__('Related Working'),
                'title'   => Mage::helper('bs_staffinfo')->__('Related Working'),
                'content' => $this->getLayout()->createBlock(
                    'bs_staffinfo/adminhtml_working_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve related working entity
     *
     * @access public
     * @return BS_StaffInfo_Model_Working
     * @author Bui Phong
     */
    public function getWorking()
    {
        return Mage::registry('current_working');
    }
}
