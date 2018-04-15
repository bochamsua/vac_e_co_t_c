<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * File Cabinet admin edit tabs
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Filecabinet_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('filecabinet_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_logistics')->__('File Cabinet'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Filecabinet_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_filecabinet',
            array(
                'label'   => Mage::helper('bs_logistics')->__('File Cabinet'),
                'title'   => Mage::helper('bs_logistics')->__('File Cabinet'),
                'content' => $this->getLayout()->createBlock(
                    'bs_logistics/adminhtml_filecabinet_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve file cabinet entity
     *
     * @access public
     * @return BS_Logistics_Model_Filecabinet
     * @author Bui Phong
     */
    public function getFilecabinet()
    {
        return Mage::registry('current_filecabinet');
    }
}
