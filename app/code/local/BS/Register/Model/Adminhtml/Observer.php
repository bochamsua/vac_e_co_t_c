<?php
/**
 * BS_Trainee extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       BS
 * @package        BS_Trainee
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Adminhtml observer
 *
 * @category    BS
 * @package     BS_Trainee
 * @author      Bui Phong
 */
class BS_Register_Model_Adminhtml_Observer
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
     * add the trainee tab to products
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_Trainee_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function addProductTraineeBlock($observer)
    {
        $block = $observer->getEvent()->getBlock();
        $product = Mage::registry('current_product');
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs && $this->_canAddTab($product)) {
            $block->addTab(
                'trainees',
                array(
                    'label' => Mage::helper('bs_trainee')->__('Trainees'),
                    'url'   => Mage::helper('adminhtml')->getUrl(
                        'adminhtml/trainee_trainee_catalog_product/trainees',
                        array('_current' => true)
                    ),
                    'class' => 'ajax',
                )
            );
        }
        return $this;
    }

    /**
     * save trainee - product relation
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_Trainee_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function saveSchedulePosition($observer)
    {
        $post = Mage::app()->getRequest()->getPost('scheduleposition', -1);
        if ($post != '-1') {
            foreach ($post as $key => $value) {
                $schedule = Mage::getModel('bs_register/schedule')->load($key);
                $schedule->setScheduleOrder($value)->save();
            }
        }
        return $this;
    }}
