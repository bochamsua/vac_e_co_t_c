<?php
/**
 * BS_Staff extension
 * 
 * @category       BS
 * @package        BS_Staff
 * @copyright      Copyright (c) 2015
 */
/**
 * Staff admin edit tabs
 *
 * @category    BS
 * @package     BS_Staff
 * @author Bui Phong
 */
class BS_Staff_Block_Adminhtml_Staff_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('staff_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_staff')->__('Staff'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Staff_Block_Adminhtml_Staff_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_staff',
            array(
                'label'   => Mage::helper('bs_staff')->__('Staff'),
                'title'   => Mage::helper('bs_staff')->__('Staff'),
                'content' => $this->getLayout()->createBlock(
                    'bs_staff/adminhtml_staff_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve staff entity
     *
     * @access public
     * @return BS_Staff_Model_Staff
     * @author Bui Phong
     */
    public function getStaff()
    {
        return Mage::registry('current_staff');
    }
}
