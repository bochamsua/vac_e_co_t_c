<?php
/**
 * BS_Register extension
 * 
 * @category       BS
 * @package        BS_Register
 * @copyright      Copyright (c) 2015
 */
/**
 * Course Schedule admin block
 *
 * @category    BS
 * @package     BS_Register
 * @author Bui Phong
 */
class BS_Register_Block_Adminhtml_Schedule extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_schedule';
        $this->_blockGroup         = 'bs_register';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_register')->__('Course Schedule');
        $this->_updateButton('add', 'label', Mage::helper('bs_register')->__('Add Course Schedule'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("catalog/bs_register/schedule/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
