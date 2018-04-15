<?php
/**
 * BS_Bank extension
 * 
 * @category       BS
 * @package        BS_Bank
 * @copyright      Copyright (c) 2015
 */
/**
 * Answer admin block
 *
 * @category    BS
 * @package     BS_Bank
 * @author      Bui Phong
 */
class BS_Bank_Block_Adminhtml_Answer extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_answer';
        $this->_blockGroup         = 'bs_bank';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_bank')->__('Answer');
        $this->_updateButton('add', 'label', Mage::helper('bs_bank')->__('Add Answer'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_bank/answer/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
