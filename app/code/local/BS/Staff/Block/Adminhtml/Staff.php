<?php
/**
 * BS_Staff extension
 * 
 * @category       BS
 * @package        BS_Staff
 * @copyright      Copyright (c) 2015
 */
/**
 * Staff admin block
 *
 * @category    BS
 * @package     BS_Staff
 * @author Bui Phong
 */
class BS_Staff_Block_Adminhtml_Staff extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_staff';
        $this->_blockGroup         = 'bs_staff';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_staff')->__('Staff');

        $this->_removeButton('add');




    }
}
