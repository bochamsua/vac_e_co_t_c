<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Other room admin block
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Otherroom extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_otherroom';
        $this->_blockGroup         = 'bs_logistics';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_logistics')->__('Other room');
        $this->_updateButton('add', 'label', Mage::helper('bs_logistics')->__('Add Other room'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_logistics/otherroom/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
