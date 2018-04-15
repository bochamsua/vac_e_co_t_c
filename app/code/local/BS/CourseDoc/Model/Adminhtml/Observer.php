<?php
/**
 * BS_CourseDoc extension
 * 
 * @category       BS
 * @package        BS_CourseDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Adminhtml observer
 *
 * @category    BS
 * @package     BS_CourseDoc
 * @author      Bui Phong
 */
class BS_CourseDoc_Model_Adminhtml_Observer
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
     * add the course doc tab to products
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_CourseDoc_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function addProductCoursedocBlock($observer)
    {
        $block = $observer->getEvent()->getBlock();
        $product = Mage::registry('current_product');
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs && $this->_canAddTab($product)) {
            $block->addTab(
                'coursedocs',
                array(
                    'label' => Mage::helper('bs_coursedoc')->__('Course Documents'),
                    'url'   => Mage::helper('adminhtml')->getUrl(
                        'adminhtml/coursedoc_coursedoc_catalog_product/coursedocs',
                        array('_current' => true)
                    ),
                    'class' => 'ajax',
                )
            );
        }
        return $this;
    }

    /**
     * save course doc - product relation
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_CourseDoc_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function saveProductCoursedocData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('coursedocs', -1);
        if ($post != '-1') {
            $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $product = Mage::registry('current_product');
            $coursedocProduct = Mage::getResourceSingleton('bs_coursedoc/coursedoc_product')
                ->saveProductRelation($product, $post);
        }
        return $this;
    }}
