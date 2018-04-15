<?php
/**
 * BS_Material extension
 * 
 * 
 * @category       BS
 * @package        BS_Material
 * @copyright      Copyright (c) 2015
 */
/**
 * Adminhtml observer
 *
 * @category    BS
 * @package     BS_Material
 * @author      Bui Phong
 */
class BS_Material_Model_Adminhtml_Observer
{
    /**
     * check if tab can be added
     *
     * @access protected
     * @param BS_Instructor_Model_Instructor $instructor
     * @return bool
     * @author Bui Phong
     */
    protected function _canAddTab($instructor)
    {
        return true;
    }

    /**
     * add the instructor doc tab to instructors
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_Material_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function addInstructorInstructordocBlock($observer)
    {
        $block = $observer->getEvent()->getBlock();
        $instructor = Mage::registry('instructor');
        if ($block instanceof BS_Instructor_Block_Instructor_Instructor_Edit_Tabs && $this->_canAddTab($instructor)) {
            $block->addTab(
                'instructordocs',
                array(
                    'label' => Mage::helper('bs_material')->__('Instructor Documents'),
                    'url'   => Mage::helper('adminhtml')->getUrl(
                        'adminhtml/material_instructordoc_instructor_instructor/instructordocs',
                        array('_current' => true)
                    ),
                    'class' => 'ajax',
                )
            );
        }
        return $this;
    }

    /**
     * save instructor doc - instructor relation
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_Material_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function saveInstructorInstructordocData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('instructordocs', -1);
        if ($post != '-1') {
            $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $instructor = Mage::registry('instructor');
            $instructordocInstructor = Mage::getResourceSingleton('bs_material/instructordoc_instructor')
                ->saveInstructorRelation($instructor, $post);
        }
        return $this;
    }}
