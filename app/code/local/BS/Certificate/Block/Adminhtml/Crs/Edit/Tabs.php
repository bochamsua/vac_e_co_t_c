<?php
/**
 * BS_Certificate extension
 * 
 * @category       BS
 * @package        BS_Certificate
 * @copyright      Copyright (c) 2015
 */
/**
 * CRS admin edit tabs
 *
 * @category    BS
 * @package     BS_Certificate
 * @author Bui Phong
 */
class BS_Certificate_Block_Adminhtml_Crs_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('crs_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_certificate')->__('CRS'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Certificate_Block_Adminhtml_Crs_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_crs',
            array(
                'label'   => Mage::helper('bs_certificate')->__('CRS'),
                'title'   => Mage::helper('bs_certificate')->__('CRS'),
                'content' => $this->getLayout()->createBlock(
                    'bs_certificate/adminhtml_crs_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve crs entity
     *
     * @access public
     * @return BS_Certificate_Model_Crs
     * @author Bui Phong
     */
    public function getCrs()
    {
        return Mage::registry('current_crs');
    }
}
