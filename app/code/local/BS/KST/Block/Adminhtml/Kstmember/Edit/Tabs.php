<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2016
 */
/**
 * Member admin edit tabs
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Adminhtml_Kstmember_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('kstmember_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_kst')->__('Member'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_KST_Block_Adminhtml_Kstmember_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_kstmember',
            array(
                'label'   => Mage::helper('bs_kst')->__('Member'),
                'title'   => Mage::helper('bs_kst')->__('Member'),
                'content' => $this->getLayout()->createBlock(
                    'bs_kst/adminhtml_kstmember_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve member entity
     *
     * @access public
     * @return BS_KST_Model_Kstmember
     * @author Bui Phong
     */
    public function getKstmember()
    {
        return Mage::registry('current_kstmember');
    }
}
