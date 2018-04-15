<?php
/**
 * BS_AdministrativeDoc extension
 * 
 * @category       BS
 * @package        BS_AdministrativeDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Administrative Document admin block
 *
 * @category    BS
 * @package     BS_AdministrativeDoc
 * @author Bui Phong
 */
class BS_AdministrativeDoc_Block_Adminhtml_Administrativedocument extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_administrativedocument';
        $this->_blockGroup         = 'bs_administrativedoc';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_administrativedoc')->__('Administrative Document');
        $this->_updateButton('add', 'label', Mage::helper('bs_administrativedoc')->__('Add Document'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_material/administrativedocument/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
