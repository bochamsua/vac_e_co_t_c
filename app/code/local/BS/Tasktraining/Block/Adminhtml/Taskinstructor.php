<?php
/**
 * BS_Tasktraining extension
 * 
 * @category       BS
 * @package        BS_Tasktraining
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor admin block
 *
 * @category    BS
 * @package     BS_Tasktraining
 * @author Bui Phong
 */
class BS_Tasktraining_Block_Adminhtml_Taskinstructor extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_taskinstructor';
        $this->_blockGroup         = 'bs_tasktraining';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_tasktraining')->__('Instructor');
        $this->_updateButton('add', 'label', Mage::helper('bs_tasktraining')->__('Add Instructor'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/bs_tasktraining/taskinstructor/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
