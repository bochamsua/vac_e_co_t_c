<?php
/**
 * BS_Tc extension
 * 
 * @category       BS
 * @package        BS_Tc
 * @copyright      Copyright (c) 2015
 */
/**
 * Employee admin block
 *
 * @category    BS
 * @package     BS_Tc
 * @author Bui Phong
 */
class BS_Tc_Block_Adminhtml_Employee extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function __construct()
    {
        $this->_controller         = 'adminhtml_employee';
        $this->_blockGroup         = 'bs_tc';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_tc')->__('Employee');
        $this->_updateButton('add', 'label', Mage::helper('bs_tc')->__('Add Employee'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_tc/employee/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

        $this->setTemplate('bs_tc/grid.phtml');
    }
}
