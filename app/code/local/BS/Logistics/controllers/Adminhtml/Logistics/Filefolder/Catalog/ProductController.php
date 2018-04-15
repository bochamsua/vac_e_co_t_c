<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * File Folder - product controller
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
require_once ("Mage/Adminhtml/controllers/Catalog/ProductController.php");
class BS_Logistics_Adminhtml_Logistics_Filefolder_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
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
     * file folders in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function filefoldersAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.filefolder')
            ->setProductFilefolders($this->getRequest()->getPost('product_filefolders', null));
        $this->renderLayout();
    }

    /**
     * file folders grid in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function filefoldersGridAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.filefolder')
            ->setProductFilefolders($this->getRequest()->getPost('product_filefolders', null));
        $this->renderLayout();
    }
}
