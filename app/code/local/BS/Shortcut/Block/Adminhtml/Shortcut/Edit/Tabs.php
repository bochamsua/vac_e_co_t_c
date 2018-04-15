<?php
/**
 * BS_Shortcut extension
 * 
 * @category       BS
 * @package        BS_Shortcut
 * @copyright      Copyright (c) 2015
 */
/**
 * Shortcut admin edit tabs
 *
 * @category    BS
 * @package     BS_Shortcut
 * @author Bui Phong
 */
class BS_Shortcut_Block_Adminhtml_Shortcut_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('shortcut_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_shortcut')->__('Shortcut'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Shortcut_Block_Adminhtml_Shortcut_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_shortcut',
            array(
                'label'   => Mage::helper('bs_shortcut')->__('Shortcut'),
                'title'   => Mage::helper('bs_shortcut')->__('Shortcut'),
                'content' => $this->getLayout()->createBlock(
                    'bs_shortcut/adminhtml_shortcut_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve shortcut entity
     *
     * @access public
     * @return BS_Shortcut_Model_Shortcut
     * @author Bui Phong
     */
    public function getShortcut()
    {
        return Mage::registry('current_shortcut');
    }
}
