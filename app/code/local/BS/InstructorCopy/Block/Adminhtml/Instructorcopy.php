<?php
/**
 * BS_InstructorCopy extension
 * 
 * @category       BS
 * @package        BS_InstructorCopy
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Copy admin block
 *
 * @category    BS
 * @package     BS_InstructorCopy
 * @author Bui Phong
 */
class BS_InstructorCopy_Block_Adminhtml_Instructorcopy extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_instructorcopy';
        $this->_blockGroup         = 'bs_instructorcopy';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_instructorcopy')->__('Instructor Copy');
        $this->_updateButton('add', 'label', Mage::helper('bs_instructorcopy')->__('Add Instructor Copy'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/bs_instructor/instructorcopy/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
