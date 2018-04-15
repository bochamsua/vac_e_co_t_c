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
class BS_Register_Adminhtml_Register_Schedule_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
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
        $this->setUsedModuleName('BS_Register');
    }

    /**
     * course schedule in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function schedulesAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.schedule');
            //->setProductSchedules($this->getRequest()->getPost('product_schedules', null));
        $this->renderLayout();
    }

    /**
     * course schedule grid in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function schedulesGridAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.schedule');
            //->setProductSchedules($this->getRequest()->getPost('product_schedules', null));
        $this->renderLayout();
    }


    public function absencesAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.absence');
        //->setProductSchedules($this->getRequest()->getPost('product_schedules', null));
        $this->renderLayout();
    }

    /**
     * course schedule grid in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function absencesGridAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.absence');
        //->setProductSchedules($this->getRequest()->getPost('product_schedules', null));
        $this->renderLayout();
    }
}
