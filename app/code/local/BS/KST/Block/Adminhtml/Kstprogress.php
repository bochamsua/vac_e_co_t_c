<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * Progress admin block
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Adminhtml_Kstprogress extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_kstprogress';
        $this->_blockGroup         = 'bs_kst';
        parent::__construct();

        $this->_headerText         = Mage::helper('bs_kst')->__('Please update <span style="color: green;">A/C REG, INSTRUCTOR, COMPLETE DATE</span> first before updating STATUS! <br>Updating an item\'s status as Complete is an IRREVERSIBLE action! <br>So take your own responsibility...');
        $this->_updateButton('add', 'label', Mage::helper('bs_kst')->__('Fill Out Items'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_kst/kstprogress/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
