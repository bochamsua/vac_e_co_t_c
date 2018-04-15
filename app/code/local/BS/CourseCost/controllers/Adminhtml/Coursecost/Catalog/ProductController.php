<?php
/**
 * BS_Register extension
 * 
 * @category       BS
 * @package        BS_Register
 * @copyright      Copyright (c) 2015
 */
/**
 * Course Schedule - product controller
 * @category    BS
 * @package     BS_Register
 * @author Bui Phong
 */
require_once ("Mage/Adminhtml/controllers/Catalog/ProductController.php");
class BS_CourseCost_Adminhtml_Coursecost_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
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
        $this->setUsedModuleName('BS_CourseCost');
    }

    /**
     * course schedule in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function coursecostsAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.coursecost');
        $this->renderLayout();
    }

    /**
     * course schedule grid in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function coursecostsGridAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.coursecost');
        $this->renderLayout();
    }



}
