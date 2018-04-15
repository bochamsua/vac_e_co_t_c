<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Workshop admin block
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Workshop extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_workshop';
        $this->_blockGroup         = 'bs_logistics';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_logistics')->__('Workshop');
        $this->_updateButton('add', 'label', Mage::helper('bs_logistics')->__('Add Workshop'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_logistics/workshop/workshop/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
