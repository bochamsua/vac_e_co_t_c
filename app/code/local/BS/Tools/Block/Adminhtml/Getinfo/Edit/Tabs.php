<?php
/**
 * BS_Tools extension
 * 
 * @category       BS
 * @package        BS_Tools
 * @copyright      Copyright (c) 2015
 */
/**
 * Get Info admin edit tabs
 *
 * @category    BS
 * @package     BS_Tools
 * @author Bui Phong
 */
class BS_Tools_Block_Adminhtml_Getinfo_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('getinfo_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_tools')->__('Get Info'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Tools_Block_Adminhtml_Getinfo_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_getinfo',
            array(
                'label'   => Mage::helper('bs_tools')->__('Get Info'),
                'title'   => Mage::helper('bs_tools')->__('Get Info'),
                'content' => $this->getLayout()->createBlock(
                    'bs_tools/adminhtml_getinfo_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve get info entity
     *
     * @access public
     * @return BS_Tools_Model_Getinfo
     * @author Bui Phong
     */
    public function getGetinfo()
    {
        return Mage::registry('current_getinfo');
    }
}
