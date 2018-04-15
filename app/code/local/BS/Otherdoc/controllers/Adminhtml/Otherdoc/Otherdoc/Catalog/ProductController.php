<?php
/**
 * BS_Otherdoc extension
 * 
 * @category       BS
 * @package        BS_Otherdoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Other\'s Course Document - product controller
 * @category    BS
 * @package     BS_Otherdoc
 * @author Bui Phong
 */
require_once ("Mage/Adminhtml/controllers/Catalog/ProductController.php");
class BS_Otherdoc_Adminhtml_Otherdoc_Otherdoc_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
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
        $this->setUsedModuleName('BS_Otherdoc');
    }

    /**
     * other\'s course documents in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function otherdocsAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.otherdoc')
            ->setProductOtherdocs($this->getRequest()->getPost('product_otherdocs', null));
        $this->renderLayout();
    }

    /**
     * other\'s course documents grid in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function otherdocsGridAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.otherdoc')
            ->setProductOtherdocs($this->getRequest()->getPost('product_otherdocs', null));
        $this->renderLayout();
    }
}
