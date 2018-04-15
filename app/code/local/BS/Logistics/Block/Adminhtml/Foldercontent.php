<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Folder Content admin block
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Foldercontent extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_foldercontent';
        $this->_blockGroup         = 'bs_logistics';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_logistics')->__('Folder Content');
        $this->_updateButton('add', 'label', Mage::helper('bs_logistics')->__('Add Folder Content'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_logistics/foldercontent/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
