<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * Item admin edit tabs
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Adminhtml_Kstitem_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('kstitem_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_kst')->__('Item'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_KST_Block_Adminhtml_Kstitem_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_kstitem',
            array(
                'label'   => Mage::helper('bs_kst')->__('Item'),
                'title'   => Mage::helper('bs_kst')->__('Item'),
                'content' => $this->getLayout()->createBlock(
                    'bs_kst/adminhtml_kstitem_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve item entity
     *
     * @access public
     * @return BS_KST_Model_Kstitem
     * @author Bui Phong
     */
    public function getKstitem()
    {
        return Mage::registry('current_kstitem');
    }
}
