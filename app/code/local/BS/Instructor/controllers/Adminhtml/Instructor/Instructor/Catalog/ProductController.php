<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor - product controller
 * @category    BS
 * @package     BS_Instructor
 * @author      Bui Phong
 */
require_once ("Mage/Adminhtml/controllers/Catalog/ProductController.php");
class BS_Instructor_Adminhtml_Instructor_Instructor_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
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
        $this->setUsedModuleName('BS_Instructor');
    }

    /**
     * instructors in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function instructorsAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.instructor')
            ->setProductInstructors($this->getRequest()->getPost('product_instructors', null));
        $this->renderLayout();
    }

    /**
     * instructors grid in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function instructorsGridAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.instructor')
            ->setProductInstructors($this->getRequest()->getPost('product_instructors', null));
        $this->renderLayout();
    }
}
