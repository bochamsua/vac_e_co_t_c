<?php
/**
 * BS_Car extension
 * 
 * @category       BS
 * @package        BS_Car
 * @copyright      Copyright (c) 2016
 */
/**
 * CAR Document admin edit tabs
 *
 * @category    BS
 * @package     BS_Car
 * @author Bui Phong
 */
class BS_Car_Block_Adminhtml_Cardoc_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('cardoc_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_car')->__('CAR Document'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Car_Block_Adminhtml_Cardoc_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_cardoc',
            array(
                'label'   => Mage::helper('bs_car')->__('CAR Document'),
                'title'   => Mage::helper('bs_car')->__('CAR Document'),
                'content' => $this->getLayout()->createBlock(
                    'bs_car/adminhtml_cardoc_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve car document entity
     *
     * @access public
     * @return BS_Car_Model_Cardoc
     * @author Bui Phong
     */
    public function getCardoc()
    {
        return Mage::registry('current_cardoc');
    }
}
