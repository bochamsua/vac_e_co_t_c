<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2016
 */
/**
 * Instructor Feedback admin edit tabs
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Adminhtml_Ifeedback_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('ifeedback_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_kst')->__('Instructor Feedback'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_KST_Block_Adminhtml_Ifeedback_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_ifeedback',
            array(
                'label'   => Mage::helper('bs_kst')->__('Instructor Feedback'),
                'title'   => Mage::helper('bs_kst')->__('Instructor Feedback'),
                'content' => $this->getLayout()->createBlock(
                    'bs_kst/adminhtml_ifeedback_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve instructor feedback entity
     *
     * @access public
     * @return BS_KST_Model_Ifeedback
     * @author Bui Phong
     */
    public function getIfeedback()
    {
        return Mage::registry('current_ifeedback');
    }
}
