<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Import Trainee admin block
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Block_Adminhtml_Importtrainee extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_importtrainee';
        $this->_blockGroup         = 'bs_docwise';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_docwise')->__('Import Trainee');
        $this->_updateButton('add', 'label', Mage::helper('bs_docwise')->__('Add Import Trainee'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_exam/bs_docwise/importtrainee/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
