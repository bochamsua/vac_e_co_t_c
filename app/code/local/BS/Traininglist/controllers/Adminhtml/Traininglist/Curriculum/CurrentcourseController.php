<?php
/**
 * BS_Traininglist extension
 * 
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * Training Curriculum admin attribute controller
 *
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */
class BS_Traininglist_Adminhtml_Traininglist_Curriculum_CurrentcourseController extends Mage_Adminhtml_Controller_Action
{


    protected function _construct()
    {
        $this->setUsedModuleName('BS_Traininglist');
    }

    /**
     * init action
     *
     * @accees protected
     * @return BS_Traininglist_Adminhtml_Curriculum_AttributeController
     * @author Bui Phong
     */
    protected function _initAction()
    {
        $this->_title(Mage::helper('bs_traininglist')->__('Current Course'))
             ->_title(Mage::helper('bs_traininglist')->__('New'))
             ->_title(Mage::helper('bs_traininglist')->__('Current Course'));

        $this->loadLayout()
            ->_setActiveMenu('catalog/products_currentcourse')
            ->_addBreadcrumb(
                Mage::helper('bs_traininglist')->__('Current Course'),
                Mage::helper('bs_traininglist')->__('Current Course')
            )
            ->_addBreadcrumb(
                Mage::helper('bs_traininglist')->__('Manage Current Course'),
                Mage::helper('bs_traininglist')->__('Manage Current Course')
            );
        return $this;
    }

    public function massDeleteAction()
    {
        $productIds = $this->getRequest()->getParam('product');
        if (!is_array($productIds)) {
            $this->_getSession()->addError($this->__('Please select product(s).'));
        } else {
            if (!empty($productIds)) {
                try {
                    foreach ($productIds as $productId) {
                        $product = Mage::getSingleton('catalog/product')->load($productId);
                        Mage::dispatchEvent('catalog_controller_product_delete', array('product' => $product));
                        $product->delete();
                    }
                    $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) have been deleted.', count($productIds))
                    );
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            }
        }
        $this->_redirect('*/traininglist_curriculum_currentcourse/index');
    }

    /**
     * default action
     *
     * @accees public
     * @return void
     * @author Bui Phong
     */
    public function indexAction()
    {
        $this->_initAction()->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }


    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('catalog/currentcourse');
    }
}
