<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2016
 */
/**
 * Group admin block
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Adminhtml_Kstgroup extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_kstgroup';
        $this->_blockGroup         = 'bs_kst';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_kst')->__('Group');
        $this->_updateButton('add', 'label', Mage::helper('bs_kst')->__('Add Group'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_kst/kstgroup/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
