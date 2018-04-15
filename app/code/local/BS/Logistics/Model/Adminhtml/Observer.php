<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Adminhtml observer
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Model_Adminhtml_Observer
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
     * add the classroom/examroom tab to products
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_Logistics_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function addProductClassroomBlock($observer)
    {
        $block = $observer->getEvent()->getBlock();
        $product = Mage::registry('current_product');
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs && $this->_canAddTab($product)) {
            $block->addTab(
                'classrooms',
                array(
                    'label' => Mage::helper('bs_logistics')->__('Classroom/Examrooms'),
                    'url'   => Mage::helper('adminhtml')->getUrl(
                        'adminhtml/logistics_classroom_catalog_product/classrooms',
                        array('_current' => true)
                    ),
                    'class' => 'ajax',
                )
            );
        }
        return $this;
    }

    /**
     * add the workshop tab to products
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_Logistics_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function addProductWorkshopBlock($observer)
    {
        $block = $observer->getEvent()->getBlock();
        $product = Mage::registry('current_product');
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs && $this->_canAddTab($product)) {
            $block->addTab(
                'workshops',
                array(
                    'label' => Mage::helper('bs_logistics')->__('Workshops'),
                    'url'   => Mage::helper('adminhtml')->getUrl(
                        'adminhtml/logistics_workshop_catalog_product/workshops',
                        array('_current' => true)
                    ),
                    'class' => 'ajax',
                )
            );
        }
        return $this;
    }

    /**
     * add the file folder tab to products
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_Logistics_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function addProductFilefolderBlock($observer)
    {
        $block = $observer->getEvent()->getBlock();
        $product = Mage::registry('current_product');
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs && $this->_canAddTab($product)) {
            $block->addTab(
                'filefolders',
                array(
                    'label' => Mage::helper('bs_logistics')->__('File Folders'),
                    'url'   => Mage::helper('adminhtml')->getUrl(
                        'adminhtml/logistics_filefolder_catalog_product/filefolders',
                        array('_current' => true)
                    ),
                    'class' => 'ajax',
                )
            );
        }
        return $this;
    }

    /**
     * save classroom/examroom - product relation
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_Logistics_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function saveProductClassroomData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('classrooms', -1);
        if ($post != '-1') {
            $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $product = Mage::registry('current_product');
            $classroomProduct = Mage::getResourceSingleton('bs_logistics/classroom_product')
                ->saveProductRelation($product, $post);
        }
        return $this;
    }
    /**
     * save workshop - product relation
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_Logistics_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function saveProductWorkshopData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('workshops', -1);
        if ($post != '-1') {
            $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $product = Mage::registry('current_product');
            $workshopProduct = Mage::getResourceSingleton('bs_logistics/workshop_product')
                ->saveProductRelation($product, $post);
        }
        return $this;
    }
    /**
     * save file folder - product relation
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_Logistics_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function saveProductFilefolderData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('filefolders', -1);
        if ($post != '-1') {
            $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $product = Mage::registry('current_product');
            $filefolderProduct = Mage::getResourceSingleton('bs_logistics/filefolder_product')
                ->saveProductRelation($product, $post);
        }
        return $this;
    }}
