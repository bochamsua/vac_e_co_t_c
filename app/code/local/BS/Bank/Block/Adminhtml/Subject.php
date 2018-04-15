<?php
/**
 * BS_Bank extension
 * 
 * @category       BS
 * @package        BS_Bank
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject admin block
 *
 * @category    BS
 * @package     BS_Bank
 * @author      Bui Phong
 */
class BS_Bank_Block_Adminhtml_Subject extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_subject';
        $this->_blockGroup         = 'bs_bank';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_bank')->__('Subject');
        $this->_updateButton('add', 'label', Mage::helper('bs_bank')->__('Add Subject'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_bank/subject/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
