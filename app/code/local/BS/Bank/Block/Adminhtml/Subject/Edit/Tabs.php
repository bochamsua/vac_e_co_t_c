<?php
/**
 * BS_Bank extension
 * 
 * @category       BS
 * @package        BS_Bank
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject admin edit tabs
 *
 * @category    BS
 * @package     BS_Bank
 * @author      Bui Phong
 */
class BS_Bank_Block_Adminhtml_Subject_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('subject_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_bank')->__('Subject'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Bank_Block_Adminhtml_Subject_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_subject',
            array(
                'label'   => Mage::helper('bs_bank')->__('Subject'),
                'title'   => Mage::helper('bs_bank')->__('Subject'),
                'content' => $this->getLayout()->createBlock(
                    'bs_bank/adminhtml_subject_edit_tab_form'
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
     * @return BS_Bank_Model_Subject
     * @author Bui Phong
     */
    public function getSubject()
    {
        return Mage::registry('current_subject');
    }
}
