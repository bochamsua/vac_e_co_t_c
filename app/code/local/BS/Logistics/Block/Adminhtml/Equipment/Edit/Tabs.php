<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2016
 */
/**
 * Equipment admin edit tabs
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Equipment_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('equipment_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_logistics')->__('Equipment'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Equipment_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_equipment',
            array(
                'label'   => Mage::helper('bs_logistics')->__('Equipment'),
                'title'   => Mage::helper('bs_logistics')->__('Equipment'),
                'content' => $this->getLayout()->createBlock(
                    'bs_logistics/adminhtml_equipment_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve equipment entity
     *
     * @access public
     * @return BS_Logistics_Model_Equipment
     * @author Bui Phong
     */
    public function getEquipment()
    {
        return Mage::registry('current_equipment');
    }
}
