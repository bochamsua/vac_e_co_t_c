<?php
/**
 * BS_Worksheet extension
 * 
 * 
 * @category       BS
 * @package        BS_Worksheet
 * @copyright      Copyright (c) 2015
 */
/**
 * Adminhtml observer
 *
 * @category    BS
 * @package     BS_Worksheet
 * @author      Bui Phong
 */
class BS_Worksheet_Model_Adminhtml_Observer
{
    /**
     * check if tab can be added
     *
     * @access protected
     * @param BS_Traininglist_Model_Curriculum $curriculum
     * @return bool
     * @author Bui Phong
     */
    protected function _canAddTab($curriculum)
    {
        if ($curriculum->getId()) {
            return true;
        }
        if (!$curriculum->getAttributeSetId()) {
            return false;
        }
        $request = Mage::app()->getRequest();
        if ($request->getParam('type') == 'configurable') {
            if ($request->getParam('attributes')) {
                return true;
            }
        }
        return false;
    }

    /**
     * add the worksheet tab to curriculums
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_Worksheet_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function addCurriculumWorksheetBlock($observer)
    {
        $block = $observer->getEvent()->getBlock();
        $curriculum = Mage::registry('current_curriculum');
        if ($block instanceof BS_Traininglist_Block_Adminhtml_Curriculum_Edit_Tabs && $this->_canAddTab($curriculum)) {
            $block->addTab(
                'worksheets',
                array(
                    'label' => Mage::helper('bs_worksheet')->__('Worksheets'),
                    'url'   => Mage::helper('adminhtml')->getUrl(
                        'adminhtml/worksheet_worksheet_traininglist_curriculum/worksheets',
                        array('_current' => true)
                    ),
                    'class' => 'ajax',
                )
            );
        }
        return $this;
    }

    /**
     * save worksheet - curriculum relation
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_Worksheet_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function saveCurriculumWorksheetData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('worksheets', -1);
        if ($post != '-1') {
            $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $curriculum = Mage::registry('current_curriculum');
            $worksheetCurriculum = Mage::getResourceSingleton('bs_worksheet/worksheet_curriculum')
                ->saveCurriculumRelation($curriculum, $post);
        }
        return $this;
    }}
