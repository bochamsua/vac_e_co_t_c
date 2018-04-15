<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject admin edit tabs
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Adminhtml_Kstsubject_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('kstsubject_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_kst')->__('Subject'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_KST_Block_Adminhtml_Kstsubject_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_kstsubject',
            array(
                'label'   => Mage::helper('bs_kst')->__('Subject'),
                'title'   => Mage::helper('bs_kst')->__('Subject'),
                'content' => $this->getLayout()->createBlock(
                    'bs_kst/adminhtml_kstsubject_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve subject entity
     *
     * @access public
     * @return BS_KST_Model_Kstsubject
     * @author Bui Phong
     */
    public function getKstsubject()
    {
        return Mage::registry('current_kstsubject');
    }
}
