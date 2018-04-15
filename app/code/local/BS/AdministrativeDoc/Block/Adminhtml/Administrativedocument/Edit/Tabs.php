<?php
/**
 * BS_AdministrativeDoc extension
 * 
 * @category       BS
 * @package        BS_AdministrativeDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Administrative Document admin edit tabs
 *
 * @category    BS
 * @package     BS_AdministrativeDoc
 * @author Bui Phong
 */
class BS_AdministrativeDoc_Block_Adminhtml_Administrativedocument_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('administrativedocument_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_administrativedoc')->__('Administrative Document'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_AdministrativeDoc_Block_Adminhtml_Administrativedocument_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_administrativedocument',
            array(
                'label'   => Mage::helper('bs_administrativedoc')->__('Administrative Document'),
                'title'   => Mage::helper('bs_administrativedoc')->__('Administrative Document'),
                'content' => $this->getLayout()->createBlock(
                    'bs_administrativedoc/adminhtml_administrativedocument_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        $this->addTab(
            'products',
            array(
                'label' => Mage::helper('bs_administrativedoc')->__('Associated courses'),
                'url'   => $this->getUrl('*/*/products', array('_current' => true)),
                'class' => 'ajax'
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve administrative document entity
     *
     * @access public
     * @return BS_AdministrativeDoc_Model_Administrativedocument
     * @author Bui Phong
     */
    public function getAdministrativedocument()
    {
        return Mage::registry('current_administrativedocument');
    }
}
