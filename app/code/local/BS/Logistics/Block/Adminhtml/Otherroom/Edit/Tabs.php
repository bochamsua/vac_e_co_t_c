<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Other room admin edit tabs
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Otherroom_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('otherroom_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_logistics')->__('Other room'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Otherroom_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_otherroom',
            array(
                'label'   => Mage::helper('bs_logistics')->__('Other room'),
                'title'   => Mage::helper('bs_logistics')->__('Other room'),
                'content' => $this->getLayout()->createBlock(
                    'bs_logistics/adminhtml_otherroom_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve other room entity
     *
     * @access public
     * @return BS_Logistics_Model_Otherroom
     * @author Bui Phong
     */
    public function getOtherroom()
    {
        return Mage::registry('current_otherroom');
    }
}
