<?php
/**
 * BS_Tasktraining extension
 * 
 * @category       BS
 * @package        BS_Tasktraining
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Function admin block
 *
 * @category    BS
 * @package     BS_Tasktraining
 * @author Bui Phong
 */
class BS_Tasktraining_Block_Adminhtml_Taskfunction extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_taskfunction';
        $this->_blockGroup         = 'bs_tasktraining';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_tasktraining')->__('Instructor Function');
        $this->_updateButton('add', 'label', Mage::helper('bs_tasktraining')->__('Add Instructor Function'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/bs_tasktraining/taskfunction/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
