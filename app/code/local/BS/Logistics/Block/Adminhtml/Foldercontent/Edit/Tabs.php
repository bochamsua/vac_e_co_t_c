<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Folder Content admin edit tabs
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Foldercontent_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('foldercontent_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_logistics')->__('Folder Content'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Foldercontent_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_foldercontent',
            array(
                'label'   => Mage::helper('bs_logistics')->__('Folder Content'),
                'title'   => Mage::helper('bs_logistics')->__('Folder Content'),
                'content' => $this->getLayout()->createBlock(
                    'bs_logistics/adminhtml_foldercontent_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve folder content entity
     *
     * @access public
     * @return BS_Logistics_Model_Foldercontent
     * @author Bui Phong
     */
    public function getFoldercontent()
    {
        return Mage::registry('current_foldercontent');
    }
}
