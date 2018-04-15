<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Adminhtml observer
 *
 * @category    BS
 * @package     BS_Instructor
 * @author      Bui Phong
 */
class BS_Instructor_Model_Adminhtml_Observer
{
    /**
     * check if tab can be added
     *
     * @access protected
     * @param Mage_Catalog_Model_Product $product
     * @return bool
     * @author Bui Phong
     */
    protected function _canAddTab($product)
    {
        if ($product->getId()) {
            return true;
        }
        if (!$product->getAttributeSetId()) {
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
     * add the instructor tab to products
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_Instructor_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function addProductInstructorBlock($observer)
    {
        $block = $observer->getEvent()->getBlock();
        $product = Mage::registry('current_product');
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs && $this->_canAddTab($product)) {
            $block->addTab(
                'instructors',
                array(
                    'label' => Mage::helper('bs_instructor')->__('Instructors'),
                    'url'   => Mage::helper('adminhtml')->getUrl(
                        'adminhtml/instructor_instructor_catalog_product/instructors',
                        array('_current' => true)
                    ),
                    'class' => 'ajax',
                )
            );
        }
        return $this;
    }

    /**
     * save instructor - product relation
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_Instructor_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function saveProductInstructorData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('instructors', -1);
        if ($post != '-1') {
            $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $product = Mage::registry('current_product');
            $instructorProduct = Mage::getResourceSingleton('bs_instructor/instructor_product')
                ->saveProductRelation($product, $post);
        }
        return $this;
    }


    public function saveCurriculumInstructorData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('instructors', -1);
        if ($post != '-1') {
            $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $curriculum = Mage::registry('current_curriculum');
            $instructorCurriculum = Mage::getResourceSingleton('bs_instructor/instructor_curriculum')
                ->saveCurriculumRelation($curriculum, $post);
        }
        return $this;
    }
    /**
     * add the instructor tab to categories
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_Instructor_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function addCategoryInstructorBlock($observer)
    {
        $tabs = $observer->getEvent()->getTabs();
        $content = $tabs->getLayout()->createBlock(
            'bs_instructor/adminhtml_catalog_category_tab_instructor',
            'category.instructor.grid'
        )->toHtml();
        $serializer = $tabs->getLayout()->createBlock(
            'adminhtml/widget_grid_serializer',
            'category.instructor.grid.serializer'
        );
        $serializer->initSerializerBlock(
            'category.instructor.grid',
            'getSelectedInstructors',
            'instructors',
            'category_instructors'
        );
        $serializer->addColumnInputName('position');
        $content .= $serializer->toHtml();
        $tabs->addTab(
            'instructor',
            array(
                'label'   => Mage::helper('bs_instructor')->__('Instructors'),
                'content' => $content,
            )
        );
        return $this;
    }

    /**
     * save instructor - category relation
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_Instructor_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function saveCategoryInstructorData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('instructors', -1);
        if ($post != '-1') {
            $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $category = Mage::registry('category');
            $instructorCategory = Mage::getResourceSingleton('bs_instructor/instructor_category')
                ->saveCategoryRelation($category, $post);
        }
        return $this;
    }
}
