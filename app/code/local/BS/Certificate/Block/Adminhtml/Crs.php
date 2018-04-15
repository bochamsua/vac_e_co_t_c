<?php
/**
 * BS_Certificate extension
 * 
 * @category       BS
 * @package        BS_Certificate
 * @copyright      Copyright (c) 2015
 */
/**
 * CRS admin block
 *
 * @category    BS
 * @package     BS_Certificate
 * @author Bui Phong
 */
class BS_Certificate_Block_Adminhtml_Crs extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_crs';
        $this->_blockGroup         = 'bs_certificate';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_certificate')->__('CRS');
        $this->_updateButton('add', 'label', Mage::helper('bs_certificate')->__('Add CRS'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("customer/crs/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
