<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2016
 */
/**
 * Member admin block
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Adminhtml_Kstmember extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_kstmember';
        $this->_blockGroup         = 'bs_kst';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_kst')->__('Member');
        $this->_updateButton('add', 'label', Mage::helper('bs_kst')->__('Add Member'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_kst/kstmember/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
