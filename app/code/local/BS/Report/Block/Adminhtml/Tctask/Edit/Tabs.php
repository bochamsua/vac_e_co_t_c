<?php
/**
 * BS_Report extension
 * 
 * @category       BS
 * @package        BS_Report
 * @copyright      Copyright (c) 2015
 */
/**
 * TC Task admin edit tabs
 *
 * @category    BS
 * @package     BS_Report
 * @author Bui Phong
 */
class BS_Report_Block_Adminhtml_Tctask_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('tctask_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_report')->__('TC Task'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Report_Block_Adminhtml_Tctask_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_tctask',
            array(
                'label'   => Mage::helper('bs_report')->__('TC Task'),
                'title'   => Mage::helper('bs_report')->__('TC Task'),
                'content' => $this->getLayout()->createBlock(
                    'bs_report/adminhtml_tctask_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve tc task entity
     *
     * @access public
     * @return BS_Report_Model_Tctask
     * @author Bui Phong
     */
    public function getTctask()
    {
        return Mage::registry('current_tctask');
    }
}
