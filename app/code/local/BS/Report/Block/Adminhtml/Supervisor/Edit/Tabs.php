<?php
/**
 * BS_Report extension
 * 
 * @category       BS
 * @package        BS_Report
 * @copyright      Copyright (c) 2015
 */
/**
 * Manage admin edit tabs
 *
 * @category    BS
 * @package     BS_Report
 * @author Bui Phong
 */
class BS_Report_Block_Adminhtml_Supervisor_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('supervisor_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_report')->__('Rating'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Report_Block_Adminhtml_Manage_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_manage',
            array(
                'label'   => Mage::helper('bs_report')->__('Rating'),
                'title'   => Mage::helper('bs_report')->__('Rating'),
                'content' => $this->getLayout()->createBlock(
                    'bs_report/adminhtml_supervisor_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve manage entity
     *
     * @access public
     * @return BS_Report_Model_Manage
     * @author Bui Phong
     */
    public function getManage()
    {
        return Mage::registry('current_supervisor');
    }
}
