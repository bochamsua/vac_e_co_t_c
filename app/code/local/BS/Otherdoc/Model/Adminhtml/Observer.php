<?php
/**
 * BS_Otherdoc extension
 * 
 * @category       BS
 * @package        BS_Otherdoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Adminhtml observer
 *
 * @category    BS
 * @package     BS_Otherdoc
 * @author Bui Phong
 */
class BS_Otherdoc_Model_Adminhtml_Observer
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
     * add the other\'s course document tab to products
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_Otherdoc_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function addProductOtherdocBlock($observer)
    {
        $block = $observer->getEvent()->getBlock();
        $product = Mage::registry('current_product');
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs && $this->_canAddTab($product)) {
            $block->addTab(
                'otherdocs',
                array(
                    'label' => Mage::helper('bs_otherdoc')->__('Other\'s Course Documents'),
                    'url'   => Mage::helper('adminhtml')->getUrl(
                        'adminhtml/otherdoc_otherdoc_catalog_product/otherdocs',
                        array('_current' => true)
                    ),
                    'class' => 'ajax',
                )
            );
        }
        return $this;
    }

    /**
     * save other\'s course document - product relation
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_Otherdoc_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function saveProductOtherdocData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('otherdocs', -1);
        if ($post != '-1') {
            $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $product = Mage::registry('current_product');
            $otherdocProduct = Mage::getResourceSingleton('bs_otherdoc/otherdoc_product')
                ->saveProductRelation($product, $post);
        }
        return $this;
    }}
