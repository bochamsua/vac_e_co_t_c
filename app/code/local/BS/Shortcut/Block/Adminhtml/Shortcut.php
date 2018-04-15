<?php
/**
 * BS_Shortcut extension
 * 
 * @category       BS
 * @package        BS_Shortcut
 * @copyright      Copyright (c) 2015
 */
/**
 * Shortcut admin block
 *
 * @category    BS
 * @package     BS_Shortcut
 * @author Bui Phong
 */
class BS_Shortcut_Block_Adminhtml_Shortcut extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_shortcut';
        $this->_blockGroup         = 'bs_shortcut';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_shortcut')->__('Shortcut');
        $this->_updateButton('add', 'label', Mage::helper('bs_shortcut')->__('Add Shortcut'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("cms/shortcut/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
