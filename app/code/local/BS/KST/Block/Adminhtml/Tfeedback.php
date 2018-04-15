<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2016
 */
/**
 * Trainee Feedback admin block
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Adminhtml_Tfeedback extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_tfeedback';
        $this->_blockGroup         = 'bs_kst';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_kst')->__('Trainee Feedback');
        //$this->_updateButton('add', 'label', Mage::helper('bs_kst')->__('Add Trainee Feedback'));


        $this->_removeButton('add');

    }
}
