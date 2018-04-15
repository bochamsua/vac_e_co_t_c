<?php
/**
 * BS_WorksheetDoc extension
 * 
 * @category       BS
 * @package        BS_WorksheetDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Adminhtml observer
 *
 * @category    BS
 * @package     BS_WorksheetDoc
 * @author      Bui Phong
 */
class BS_WorksheetDoc_Model_Adminhtml_Observer
{
    /**
     * check if tab can be added
     *
     * @access protected
     * @param BS_Worksheet_Model_Worksheet $worksheet
     * @return bool
     * @author Bui Phong
     */
    protected function _canAddTab($worksheet)
    {
        return true;
    }

    /**
     * add the worksheet doc tab to worksheets
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_WorksheetDoc_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function addWorksheetWorksheetdocBlock($observer)
    {
        $block = $observer->getEvent()->getBlock();
        $worksheet = Mage::registry('current_worksheet');
        if ($block instanceof BS_Worksheet_Block_Adminhtml_Worksheet_Edit_Tabs && $this->_canAddTab($worksheet)) {
            $block->addTab(
                'worksheetdocs',
                array(
                    'label' => Mage::helper('bs_worksheetdoc')->__('Worksheet Documents'),
                    'url'   => Mage::helper('adminhtml')->getUrl(
                        'adminhtml/worksheetdoc_worksheetdoc_worksheet_worksheet/worksheetdocs',
                        array('_current' => true)
                    ),
                    'class' => 'ajax',
                )
            );
        }
        return $this;
    }

    /**
     * save worksheet doc - worksheet relation
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_WorksheetDoc_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function saveWorksheetWorksheetdocData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('worksheetdocs', -1);
        if ($post != '-1') {
            $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $worksheet = Mage::registry('current_worksheet');
            $worksheetdocWorksheet = Mage::getResourceSingleton('bs_worksheetdoc/worksheetdoc_worksheet')
                ->saveWorksheetRelation($worksheet, $post);
        }
        return $this;
    }}
