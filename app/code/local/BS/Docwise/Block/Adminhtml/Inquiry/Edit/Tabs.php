<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Inquiry admin edit tabs
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Block_Adminhtml_Inquiry_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('inquiry_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_docwise')->__('Inquiry'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Docwise_Block_Adminhtml_Inquiry_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_inquiry',
            array(
                'label'   => Mage::helper('bs_docwise')->__('Inquiry'),
                'title'   => Mage::helper('bs_docwise')->__('Inquiry'),
                'content' => $this->getLayout()->createBlock(
                    'bs_docwise/adminhtml_inquiry_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve inquiry entity
     *
     * @access public
     * @return BS_Docwise_Model_Inquiry
     * @author Bui Phong
     */
    public function getInquiry()
    {
        return Mage::registry('current_inquiry');
    }
}
