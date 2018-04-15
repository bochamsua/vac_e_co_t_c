<?php
/**
 * BS_TraineeDoc extension
 * 
 * @category       BS
 * @package        BS_TraineeDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Trainee Document admin block
 *
 * @category    BS
 * @package     BS_TraineeDoc
 * @author      Bui Phong
 */
class BS_TraineeDoc_Block_Adminhtml_Traineedoc extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_traineedoc';
        $this->_blockGroup         = 'bs_traineedoc';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_traineedoc')->__('Trainee Document');
        $this->_updateButton('add', 'label', Mage::helper('bs_traineedoc')->__('Add Trainee Document'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_material/traineedoc/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
