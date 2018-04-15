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
require_once ("Mage/Adminhtml/controllers/CustomerController.php");
class BS_StaffInfo_Adminhtml_Staffinfo_CustomerController extends Mage_Adminhtml_CustomerController
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
        $this->setUsedModuleName('BS_StaffInfo');
    }



    public function trainingsAction()
    {
        $this->_initCustomer();
        $this->loadLayout();
        $this->getLayout()->getBlock('customer.edit.tab.training');
        $this->renderLayout();
    }

    public function trainingsGridAction()
    {
        $this->_initCustomer();
        $this->loadLayout();
        $this->getLayout()->getBlock('customer.edit.tab.training');
        $this->renderLayout();
    }

    public function workingsAction()
    {
        $this->_initCustomer();
        $this->loadLayout();
        $this->getLayout()->getBlock('customer.edit.tab.working');
        $this->renderLayout();
    }

    public function workingsGridAction()
    {
        $this->_initCustomer();
        $this->loadLayout();
        $this->getLayout()->getBlock('customer.edit.tab.working');
        $this->renderLayout();
    }
}
