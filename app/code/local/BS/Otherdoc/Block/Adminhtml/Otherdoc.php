<?php
/**
 * BS_Otherdoc extension
 * 
 * @category       BS
 * @package        BS_Otherdoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Other\'s Course Document admin block
 *
 * @category    BS
 * @package     BS_Otherdoc
 * @author Bui Phong
 */
class BS_Otherdoc_Block_Adminhtml_Otherdoc extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_otherdoc';
        $this->_blockGroup         = 'bs_otherdoc';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_otherdoc')->__('Other\'s Course Document');
        $this->_updateButton('add', 'label', Mage::helper('bs_otherdoc')->__('Add Other\'s Course Document'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_material/otherdoc/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
