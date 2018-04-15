<?php
/**
 * BS_Formtemplate extension
 * 
 * @category       BS
 * @package        BS_Formtemplate
 * @copyright      Copyright (c) 2015
 */
/**
 * Form Template admin block
 *
 * @category    BS
 * @package     BS_Formtemplate
 * @author Bui Phong
 */
class BS_Formtemplate_Block_Adminhtml_Formtemplate extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_formtemplate';
        $this->_blockGroup         = 'bs_formtemplate';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_formtemplate')->__('Form Template');
        $this->_updateButton('add', 'label', Mage::helper('bs_formtemplate')->__('Add Form Template'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("system/formtemplate/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
