<?php
/**
 * BS_Material extension
 * 
 * 
 * @category       BS
 * @package        BS_Material
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Document admin block
 *
 * @category    BS
 * @package     BS_Material
 * @author      Bui Phong
 */
class BS_Material_Block_Adminhtml_Instructordoc extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_instructordoc';
        $this->_blockGroup         = 'bs_material';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_material')->__('Instructor Document');
        $this->_updateButton('add', 'label', Mage::helper('bs_material')->__('Add Instructor Document'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_material/instructordoc/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
