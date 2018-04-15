<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2016
 */
/**
 * Type admin edit tabs
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Grouptype_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('grouptype_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_logistics')->__('Type'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Grouptype_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_grouptype',
            array(
                'label'   => Mage::helper('bs_logistics')->__('Type'),
                'title'   => Mage::helper('bs_logistics')->__('Type'),
                'content' => $this->getLayout()->createBlock(
                    'bs_logistics/adminhtml_grouptype_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve type entity
     *
     * @access public
     * @return BS_Logistics_Model_Grouptype
     * @author Bui Phong
     */
    public function getGrouptype()
    {
        return Mage::registry('current_grouptype');
    }
}
