<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Workshop - product controller
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
require_once ("Mage/Adminhtml/controllers/Catalog/ProductController.php");
class BS_Logistics_Adminhtml_Logistics_Workshop_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
{
    /**
     * construct
     *
     * @access protected
     * @return void
     * @author Bui Phong
     */
    protected function _construct()
    {
        // Define module dependent translate
        $this->setUsedModuleName('BS_Logistics');
    }

    /**
     * workshops in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function workshopsAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.workshop')
            ->setProductWorkshops($this->getRequest()->getPost('product_workshops', null));
        $this->renderLayout();
    }

    /**
     * workshops grid in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function workshopsGridAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.workshop')
            ->setProductWorkshops($this->getRequest()->getPost('product_workshops', null));
        $this->renderLayout();
    }
}
