<?php
/**
 * BS_Otherdoc extension
 * 
 * @category       BS
 * @package        BS_Otherdoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Other\'s Course Document admin edit tabs
 *
 * @category    BS
 * @package     BS_Otherdoc
 * @author Bui Phong
 */
class BS_Otherdoc_Block_Adminhtml_Otherdoc_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('otherdoc_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_otherdoc')->__('Other\'s Course Document'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Otherdoc_Block_Adminhtml_Otherdoc_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_otherdoc',
            array(
                'label'   => Mage::helper('bs_otherdoc')->__('Other\'s Course Document'),
                'title'   => Mage::helper('bs_otherdoc')->__('Other\'s Course Document'),
                'content' => $this->getLayout()->createBlock(
                    'bs_otherdoc/adminhtml_otherdoc_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        $this->addTab(
            'products',
            array(
                'label' => Mage::helper('bs_otherdoc')->__('Associated products'),
                'url'   => $this->getUrl('*/*/products', array('_current' => true)),
                'class' => 'ajax'
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve other\'s course document entity
     *
     * @access public
     * @return BS_Otherdoc_Model_Otherdoc
     * @author Bui Phong
     */
    public function getOtherdoc()
    {
        return Mage::registry('current_otherdoc');
    }
}
