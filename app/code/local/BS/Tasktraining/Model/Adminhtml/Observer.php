<?php
/**
 * BS_Tasktraining extension
 * 
 * @category       BS
 * @package        BS_Tasktraining
 * @copyright      Copyright (c) 2015
 */
/**
 * Adminhtml observer
 *
 * @category    BS
 * @package     BS_Tasktraining
 * @author Bui Phong
 */
class BS_Tasktraining_Model_Adminhtml_Observer
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
     * add the taskinstructor tab to categories
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_Tasktraining_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function addCategoryTaskinstructorBlock($observer)
    {
        $tabs = $observer->getEvent()->getTabs();
        $content = $tabs->getLayout()->createBlock(
            'bs_tasktraining/adminhtml_catalog_category_tab_taskinstructor',
            'category.taskinstructor.grid'
        )->toHtml();
        $serializer = $tabs->getLayout()->createBlock(
            'adminhtml/widget_grid_serializer',
            'category.taskinstructor.grid.serializer'
        );
        $serializer->initSerializerBlock(
            'category.taskinstructor.grid',
            'getSelectedTaskinstructors',
            'taskinstructors',
            'category_taskinstructors'
        );
        $serializer->addColumnInputName('position');
        $content .= $serializer->toHtml();
        $tabs->addTab(
            'taskinstructor',
            array(
                'label'   => Mage::helper('bs_tasktraining')->__('Task Training Instructor'),
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
     * @return BS_Tasktraining_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function saveCategoryTaskinstructorData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('taskinstructors', -1);
        if ($post != '-1') {
            $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $category = Mage::registry('category');
            $taskinstructorCategory = Mage::getResourceSingleton('bs_tasktraining/taskinstructor_category')
                ->saveCategoryRelation($category, $post);
        }
        return $this;
    }
}
