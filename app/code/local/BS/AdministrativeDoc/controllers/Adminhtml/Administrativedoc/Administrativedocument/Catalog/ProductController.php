<?php
/**
 * BS_AdministrativeDoc extension
 * 
 * @category       BS
 * @package        BS_AdministrativeDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Administrative Document - product controller
 * @category    BS
 * @package     BS_AdministrativeDoc
 * @author Bui Phong
 */
require_once ("Mage/Adminhtml/controllers/Catalog/ProductController.php");
class BS_AdministrativeDoc_Adminhtml_Administrativedoc_Administrativedocument_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
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
        $this->setUsedModuleName('BS_AdministrativeDoc');
    }

    /**
     * administrative documents in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function administrativedocumentsAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.administrativedocument')
            ->setProductAdministrativedocuments($this->getRequest()->getPost('product_administrativedocuments', null));
        $this->renderLayout();
    }

    /**
     * administrative documents grid in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function administrativedocumentsGridAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.administrativedocument')
            ->setProductAdministrativedocuments($this->getRequest()->getPost('product_administrativedocuments', null));
        $this->renderLayout();
    }
}
