<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2016
 */
/**
 * Group admin edit tabs
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Adminhtml_Kstgroup_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('kstgroup_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_kst')->__('Group'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_KST_Block_Adminhtml_Kstgroup_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_kstgroup',
            array(
                'label'   => Mage::helper('bs_kst')->__('Group'),
                'title'   => Mage::helper('bs_kst')->__('Group'),
                'content' => $this->getLayout()->createBlock(
                    'bs_kst/adminhtml_kstgroup_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve group entity
     *
     * @access public
     * @return BS_KST_Model_Kstgroup
     * @author Bui Phong
     */
    public function getKstgroup()
    {
        return Mage::registry('current_kstgroup');
    }
}
