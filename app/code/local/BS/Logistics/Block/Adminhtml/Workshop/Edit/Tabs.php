<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Workshop admin edit tabs
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Workshop_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('workshop_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_logistics')->__('Workshop'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Workshop_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_workshop',
            array(
                'label'   => Mage::helper('bs_logistics')->__('Workshop'),
                'title'   => Mage::helper('bs_logistics')->__('Workshop'),
                'content' => $this->getLayout()->createBlock(
                    'bs_logistics/adminhtml_workshop_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        $this->addTab(
            'products',
            array(
                'label' => Mage::helper('bs_logistics')->__('Conducted Courses'),
                'url'   => $this->getUrl('*/*/products', array('_current' => true)),
                'class' => 'ajax'
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve workshop entity
     *
     * @access public
     * @return BS_Logistics_Model_Workshop
     * @author Bui Phong
     */
    public function getWorkshop()
    {
        return Mage::registry('current_workshop');
    }
}
