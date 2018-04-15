<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Inquiry admin block
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Block_Adminhtml_Inquiry extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_inquiry';
        $this->_blockGroup         = 'bs_docwise';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_docwise')->__('Inquiry');
        $this->_updateButton('add', 'label', Mage::helper('bs_docwise')->__('Add Inquiry'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_docwise/inquiry/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
