<?php
/**
 * BS_Handover extension
 * 
 * @category       BS
 * @package        BS_Handover
 * @copyright      Copyright (c) 2015
 */
/**
 * Minutes of Handover V2 admin edit tabs
 *
 * @category    BS
 * @package     BS_Handover
 * @author Bui Phong
 */
class BS_Handover_Block_Adminhtml_Handovertwo_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('handovertwo_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_handover')->__('Minutes of Handover V2'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Handover_Block_Adminhtml_Handovertwo_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_handovertwo',
            array(
                'label'   => Mage::helper('bs_handover')->__('Minutes of Handover V2'),
                'title'   => Mage::helper('bs_handover')->__('Minutes of Handover V2'),
                'content' => $this->getLayout()->createBlock(
                    'bs_handover/adminhtml_handovertwo_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve minutes of handover v2 entity
     *
     * @access public
     * @return BS_Handover_Model_Handovertwo
     * @author Bui Phong
     */
    public function getHandovertwo()
    {
        return Mage::registry('current_handovertwo');
    }
}
