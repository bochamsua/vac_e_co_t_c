<?php
/**
 * BS_Bank extension
 * 
 * @category       BS
 * @package        BS_Bank
 * @copyright      Copyright (c) 2015
 */
/**
 * Question admin block
 *
 * @category    BS
 * @package     BS_Bank
 * @author      Bui Phong
 */
class BS_Bank_Block_Adminhtml_Question extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_question';
        $this->_blockGroup         = 'bs_bank';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_bank')->__('Question');
        $this->_updateButton('add', 'label', Mage::helper('bs_bank')->__('Add Question'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_bank/question/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
