<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2016
 */
/**
 * Group Item admin edit tabs
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Wgroupitem_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('wgroupitem_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_logistics')->__('Equipment'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Wgroupitem_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_wgroupitem',
            array(
                'label'   => Mage::helper('bs_logistics')->__('Equipment'),
                'title'   => Mage::helper('bs_logistics')->__('Equipment'),
                'content' => $this->getLayout()->createBlock(
                    'bs_logistics/adminhtml_wgroupitem_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve group item entity
     *
     * @access public
     * @return BS_Logistics_Model_Wgroupitem
     * @author Bui Phong
     */
    public function getWgroupitem()
    {
        return Mage::registry('current_wgroupitem');
    }
}
