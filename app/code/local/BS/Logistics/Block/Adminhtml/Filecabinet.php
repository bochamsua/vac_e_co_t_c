<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * File Cabinet admin block
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Filecabinet extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_filecabinet';
        $this->_blockGroup         = 'bs_logistics';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_logistics')->__('File Cabinet');
        $this->_updateButton('add', 'label', Mage::helper('bs_logistics')->__('Add File Cabinet'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_logistics/filecabinet/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
