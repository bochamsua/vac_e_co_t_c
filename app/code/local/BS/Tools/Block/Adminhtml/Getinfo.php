<?php
/**
 * BS_Tools extension
 * 
 * @category       BS
 * @package        BS_Tools
 * @copyright      Copyright (c) 2015
 */
/**
 * Get Info admin block
 *
 * @category    BS
 * @package     BS_Tools
 * @author Bui Phong
 */
class BS_Tools_Block_Adminhtml_Getinfo extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_getinfo';
        $this->_blockGroup         = 'bs_tools';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_tools')->__('Get Info');
        $this->_updateButton('add', 'label', Mage::helper('bs_tools')->__('Add Get Info'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_tools/getinfo/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
