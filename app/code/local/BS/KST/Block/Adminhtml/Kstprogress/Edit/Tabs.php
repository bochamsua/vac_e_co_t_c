<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * Progress admin edit tabs
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Adminhtml_Kstprogress_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('kstprogress_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_kst')->__('Progress'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_KST_Block_Adminhtml_Kstprogress_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_kstprogress',
            array(
                'label'   => Mage::helper('bs_kst')->__('Progress'),
                'title'   => Mage::helper('bs_kst')->__('Progress'),
                'content' => $this->getLayout()->createBlock(
                    'bs_kst/adminhtml_kstprogress_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve progress entity
     *
     * @access public
     * @return BS_KST_Model_Kstprogress
     * @author Bui Phong
     */
    public function getKstprogress()
    {
        return Mage::registry('current_kstprogress');
    }
}
