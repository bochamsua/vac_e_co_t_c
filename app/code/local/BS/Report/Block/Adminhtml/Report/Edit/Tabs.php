<?php
/**
 * BS_Report extension
 * 
 * @category       BS
 * @package        BS_Report
 * @copyright      Copyright (c) 2015
 */
/**
 * Individual Report admin edit tabs
 *
 * @category    BS
 * @package     BS_Report
 * @author Bui Phong
 */
class BS_Report_Block_Adminhtml_Report_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('report_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_report')->__('Individual Report'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Report_Block_Adminhtml_Report_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_report',
            array(
                'label'   => Mage::helper('bs_report')->__('Individual Report'),
                'title'   => Mage::helper('bs_report')->__('Individual Report'),
                'content' => $this->getLayout()->createBlock(
                    'bs_report/adminhtml_report_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve individual report entity
     *
     * @access public
     * @return BS_Report_Model_Report
     * @author Bui Phong
     */
    public function getReport()
    {
        return Mage::registry('current_report');
    }
}
