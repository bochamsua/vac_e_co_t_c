<?php
/**
 * BS_CurriculumDoc extension
 * 
 * 
 * @category       BS
 * @package        BS_CurriculumDoc
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Adminhtml observer
 *
 * @category    BS
 * @package     BS_CurriculumDoc
 * @author      Bui Phong
 */
class BS_CurriculumDoc_Model_Adminhtml_Observer
{
    /**
     * check if tab can be added
     *
     * @access protected
     * @param Mage_Catalog_Model_Curriculum $curriculum
     * @return bool
     * @author Bui Phong
     */
    protected function _canAddTab($curriculum)
    {


        return true;
    }

    /**
     * add the curriculum doc tab to curriculums
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_CurriculumDoc_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function addCurriculumCurriculumdocBlock($observer)
    {
        $block = $observer->getEvent()->getBlock();
        $curriculum = Mage::registry('current_curriculum');
        if ($block instanceof BS_Traininglist_Block_Adminhtml_Curriculum_Edit_Tabs && $this->_canAddTab($curriculum)) {
            $block->addTab(
                'curriculumdocs',
                array(
                    'label' => Mage::helper('bs_curriculumdoc')->__('Curriculum Files'),
                    'url'   => Mage::helper('adminhtml')->getUrl(
                        'adminhtml/curriculumdoc_curriculumdoc_traininglist_curriculum/curriculumdocs',
                        array('_current' => true)
                    ),
                    'class' => 'ajax',
                )
            );
        }
        return $this;
    }

    /**
     * save curriculum doc - curriculum relation
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_CurriculumDoc_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function saveCurriculumCurriculumdocData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('curriculumdocs', -1);
        if ($post != '-1') {
            $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $curriculum = Mage::registry('current_curriculum');
            $curriculumdocCurriculum = Mage::getResourceSingleton('bs_curriculumdoc/curriculumdoc_curriculum')
                ->saveCurriculumRelation($curriculum, $post);
        }
        return $this;
    }}
