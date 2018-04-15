<?php
/**
 * BS_Worksheet extension
 * 
 * 
 * @category       BS
 * @package        BS_Worksheet
 * @copyright      Copyright (c) 2015
 */
/**
 * Worksheet admin block
 *
 * @category    BS
 * @package     BS_Worksheet
 * @author      Bui Phong
 */
class BS_Worksheet_Block_Adminhtml_Worksheet extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_worksheet';
        $this->_blockGroup         = 'bs_worksheet';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_worksheet')->__('Worksheet');
        $this->_updateButton('add', 'label', Mage::helper('bs_worksheet')->__('Add Worksheet'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/worksheet/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
