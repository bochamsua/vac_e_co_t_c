<?php
/**
 * BS_Formtemplate extension
 * 
 * @category       BS
 * @package        BS_Formtemplate
 * @copyright      Copyright (c) 2015
 */
/**
 * Form Template admin edit tabs
 *
 * @category    BS
 * @package     BS_Formtemplate
 * @author Bui Phong
 */
class BS_Formtemplate_Block_Adminhtml_Formtemplate_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('formtemplate_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_formtemplate')->__('Form Template'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Formtemplate_Block_Adminhtml_Formtemplate_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_formtemplate',
            array(
                'label'   => Mage::helper('bs_formtemplate')->__('Form Template'),
                'title'   => Mage::helper('bs_formtemplate')->__('Form Template'),
                'content' => $this->getLayout()->createBlock(
                    'bs_formtemplate/adminhtml_formtemplate_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve form template entity
     *
     * @access public
     * @return BS_Formtemplate_Model_Formtemplate
     * @author Bui Phong
     */
    public function getFormtemplate()
    {
        return Mage::registry('current_formtemplate');
    }
}
