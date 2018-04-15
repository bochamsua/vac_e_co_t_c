<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Classroom/Examroom - product controller
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
require_once ("Mage/Adminhtml/controllers/Catalog/ProductController.php");
class BS_Logistics_Adminhtml_Logistics_Classroom_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
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
     * classroom/examrooms in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function classroomsAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.classroom')
            ->setProductClassrooms($this->getRequest()->getPost('product_classrooms', null));
        $this->renderLayout();
    }

    /**
     * classroom/examrooms grid in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function classroomsGridAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.classroom')
            ->setProductClassrooms($this->getRequest()->getPost('product_classrooms', null));
        $this->renderLayout();
    }
}
