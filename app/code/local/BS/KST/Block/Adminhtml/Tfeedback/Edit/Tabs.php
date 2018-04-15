<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2016
 */
/**
 * Trainee Feedback admin edit tabs
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Adminhtml_Tfeedback_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('tfeedback_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_kst')->__('Trainee Feedback'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_KST_Block_Adminhtml_Tfeedback_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_tfeedback',
            array(
                'label'   => Mage::helper('bs_kst')->__('Trainee Feedback'),
                'title'   => Mage::helper('bs_kst')->__('Trainee Feedback'),
                'content' => $this->getLayout()->createBlock(
                    'bs_kst/adminhtml_tfeedback_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve trainee feedback entity
     *
     * @access public
     * @return BS_KST_Model_Tfeedback
     * @author Bui Phong
     */
    public function getTfeedback()
    {
        return Mage::registry('current_tfeedback');
    }
}
