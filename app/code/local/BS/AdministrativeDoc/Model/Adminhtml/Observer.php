<?php
/**
 * BS_AdministrativeDoc extension
 * 
 * @category       BS
 * @package        BS_AdministrativeDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Adminhtml observer
 *
 * @category    BS
 * @package     BS_AdministrativeDoc
 * @author Bui Phong
 */
class BS_AdministrativeDoc_Model_Adminhtml_Observer
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
     * add the administrative document tab to products
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_AdministrativeDoc_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function addProductAdministrativedocumentBlock($observer)
    {
        $block = $observer->getEvent()->getBlock();
        $product = Mage::registry('current_product');
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs && $this->_canAddTab($product)) {
            $block->addTab(
                'administrativedocuments',
                array(
                    'label' => Mage::helper('bs_administrativedoc')->__('Administrative Documents'),
                    'url'   => Mage::helper('adminhtml')->getUrl(
                        'adminhtml/administrativedoc_administrativedocument_catalog_product/administrativedocuments',
                        array('_current' => true)
                    ),
                    'class' => 'ajax',
                )
            );
        }
        return $this;
    }

    /**
     * save administrative document - product relation
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_AdministrativeDoc_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function saveProductAdministrativedocumentData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('administrativedocuments', -1);
        if ($post != '-1') {
            $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $product = Mage::registry('current_product');
            $administrativedocumentProduct = Mage::getResourceSingleton('bs_administrativedoc/administrativedocument_product')
                ->saveProductRelation($product, $post);
        }
        return $this;
    }}
