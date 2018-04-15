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
class BS_Exam_Adminhtml_Exam_Examresult_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
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
        $this->setUsedModuleName('BS_Exam');
    }

    /**
     * course schedule in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function examresultsAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.examresult');
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
    public function examresultsGridAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.examresult');
            //->setProductSchedules($this->getRequest()->getPost('product_schedules', null));
        $this->renderLayout();
    }



}
