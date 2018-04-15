<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2016
 */
/**
 * Tool admin edit tabs
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Wtool_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('wtool_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_logistics')->__('Tool'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Wtool_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_wtool',
            array(
                'label'   => Mage::helper('bs_logistics')->__('Tool'),
                'title'   => Mage::helper('bs_logistics')->__('Tool'),
                'content' => $this->getLayout()->createBlock(
                    'bs_logistics/adminhtml_wtool_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve tool entity
     *
     * @access public
     * @return BS_Logistics_Model_Wtool
     * @author Bui Phong
     */
    public function getWtool()
    {
        return Mage::registry('current_wtool');
    }
}
