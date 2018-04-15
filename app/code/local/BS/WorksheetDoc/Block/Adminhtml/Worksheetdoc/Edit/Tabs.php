<?php
/**
 * BS_WorksheetDoc extension
 * 
 * @category       BS
 * @package        BS_WorksheetDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Worksheet Document admin edit tabs
 *
 * @category    BS
 * @package     BS_WorksheetDoc
 * @author      Bui Phong
 */
class BS_WorksheetDoc_Block_Adminhtml_Worksheetdoc_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('worksheetdoc_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_worksheetdoc')->__('Worksheet Document'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_WorksheetDoc_Block_Adminhtml_Worksheetdoc_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_worksheetdoc',
            array(
                'label'   => Mage::helper('bs_worksheetdoc')->__('Document Info'),
                'title'   => Mage::helper('bs_worksheetdoc')->__('Document Info'),
                'content' => $this->getLayout()->createBlock(
                    'bs_worksheetdoc/adminhtml_worksheetdoc_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        $this->addTab(
            'worksheets',
            array(
                'label' => Mage::helper('bs_worksheetdoc')->__('Related worksheets'),
                'url'   => $this->getUrl('*/*/worksheets', array('_current' => true)),
                'class' => 'ajax'
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve worksheet doc entity
     *
     * @access public
     * @return BS_WorksheetDoc_Model_Worksheetdoc
     * @author Bui Phong
     */
    public function getWorksheetdoc()
    {
        return Mage::registry('current_worksheetdoc');
    }
}
