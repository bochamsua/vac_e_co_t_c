<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2016
 */
/**
 * Tool admin block
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Wtool extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_wtool';
        $this->_blockGroup         = 'bs_logistics';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_logistics')->__('Tool');
        $this->_updateButton('add', 'label', Mage::helper('bs_logistics')->__('Add Tool'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_logistics/workshop/wtool/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
