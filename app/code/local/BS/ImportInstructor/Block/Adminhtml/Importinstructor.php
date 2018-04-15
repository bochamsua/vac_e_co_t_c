<?php
/**
 * BS_ImportInstructor extension
 * 
 * @category       BS
 * @package        BS_ImportInstructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Import Instructor admin block
 *
 * @category    BS
 * @package     BS_ImportInstructor
 * @author Bui Phong
 */
class BS_ImportInstructor_Block_Adminhtml_Importinstructor extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_importinstructor';
        $this->_blockGroup         = 'bs_importinstructor';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_importinstructor')->__('Import Instructor');
        $this->_updateButton('add', 'label', Mage::helper('bs_importinstructor')->__('Add Import Instructor'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/importinstructor/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
