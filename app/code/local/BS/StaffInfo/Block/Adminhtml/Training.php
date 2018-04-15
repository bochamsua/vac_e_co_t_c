<?php
/**
 * BS_StaffInfo extension
 * 
 * @category       BS
 * @package        BS_StaffInfo
 * @copyright      Copyright (c) 2015
 */
/**
 * Related Training admin block
 *
 * @category    BS
 * @package     BS_StaffInfo
 * @author Bui Phong
 */
class BS_StaffInfo_Block_Adminhtml_Training extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_training';
        $this->_blockGroup         = 'bs_staffinfo';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_staffinfo')->__('Related Training');
        $this->_updateButton('add', 'label', Mage::helper('bs_staffinfo')->__('Add Related Training'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("customer/training/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
