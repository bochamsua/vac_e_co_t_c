<?php
/**
 * BS_TraineeCert extension
 * 
 * @category       BS
 * @package        BS_TraineeCert
 * @copyright      Copyright (c) 2015
 */
/**
 * Trainee Certificate admin block
 *
 * @category    BS
 * @package     BS_TraineeCert
 * @author Bui Phong
 */
class BS_TraineeCert_Block_Adminhtml_Traineecert extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_traineecert';
        $this->_blockGroup         = 'bs_traineecert';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_traineecert')->__('Trainee Certificate');
        $this->_updateButton('add', 'label', Mage::helper('bs_traineecert')->__('Add Trainee Certificate'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/traineecert/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
