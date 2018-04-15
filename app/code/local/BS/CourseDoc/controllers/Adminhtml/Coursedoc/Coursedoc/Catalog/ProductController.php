<?php
/**
 * BS_CourseDoc extension
 * 
 * @category       BS
 * @package        BS_CourseDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Course Document - product controller
 * @category    BS
 * @package     BS_CourseDoc
 * @author      Bui Phong
 */
require_once ("Mage/Adminhtml/controllers/Catalog/ProductController.php");
class BS_CourseDoc_Adminhtml_Coursedoc_Coursedoc_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
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
        $this->setUsedModuleName('BS_CourseDoc');
    }

    /**
     * course docs in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function coursedocsAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.coursedoc')
            ->setProductCoursedocs($this->getRequest()->getPost('product_coursedocs', null));
        $this->renderLayout();
    }

    /**
     * course docs grid in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function coursedocsGridAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.coursedoc')
            ->setProductCoursedocs($this->getRequest()->getPost('product_coursedocs', null));
        $this->renderLayout();
    }
}
