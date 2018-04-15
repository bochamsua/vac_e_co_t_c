<?php
/**
 * BS_Tc extension
 * 
 * @category       BS
 * @package        BS_Tc
 * @copyright      Copyright (c) 2015
 */
/**
 * Adminhtml family attribute edit page tabs
 *
 * @category    BS
 * @package     BS_Tc
 * @author Bui Phong
 */
class BS_Tc_Block_Adminhtml_Family_Attribute_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('family_attribute_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_tc')->__('Attribute Information'));
    }

    /**
     * add attribute tabs
     *
     * @access protected
     * @return BS_Tc_Adminhtml_Family_Attribute_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'main',
            array(
                'label'     => Mage::helper('bs_tc')->__('Properties'),
                'title'     => Mage::helper('bs_tc')->__('Properties'),
                'content'   => $this->getLayout()->createBlock(
                    'bs_tc/adminhtml_family_attribute_edit_tab_main'
                )
                ->toHtml(),
                'active'    => true
            )
        );
        $this->addTab(
            'labels',
            array(
                'label'     => Mage::helper('bs_tc')->__('Manage Label / Options'),
                'title'     => Mage::helper('bs_tc')->__('Manage Label / Options'),
                'content'   => $this->getLayout()->createBlock(
                    'bs_tc/adminhtml_family_attribute_edit_tab_options'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }
}
