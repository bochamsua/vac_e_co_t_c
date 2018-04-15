<?php
/**
 * BS_Handover extension
 * 
 * @category       BS
 * @package        BS_Handover
 * @copyright      Copyright (c) 2015
 */
/**
 * Minutes of Handover V1 admin edit tabs
 *
 * @category    BS
 * @package     BS_Handover
 * @author Bui Phong
 */
class BS_Handover_Block_Adminhtml_Handoverone_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('handoverone_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_handover')->__('Minutes of Handover V1'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Handover_Block_Adminhtml_Handoverone_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_handoverone',
            array(
                'label'   => Mage::helper('bs_handover')->__('Minutes of Handover V1'),
                'title'   => Mage::helper('bs_handover')->__('Minutes of Handover V1'),
                'content' => $this->getLayout()->createBlock(
                    'bs_handover/adminhtml_handoverone_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve minutes of handover v1 entity
     *
     * @access public
     * @return BS_Handover_Model_Handoverone
     * @author Bui Phong
     */
    public function getHandoverone()
    {
        return Mage::registry('current_handoverone');
    }
}
