<?php
/**
 * BS_InstructorInfo extension
 * 
 * @category       BS
 * @package        BS_InstructorInfo
 * @copyright      Copyright (c) 2015
 */
/**
 * Other Info admin block
 *
 * @category    BS
 * @package     BS_InstructorInfo
 * @author Bui Phong
 */
class BS_InstructorInfo_Block_Adminhtml_Otherinfo extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_otherinfo';
        $this->_blockGroup         = 'bs_instructorinfo';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_instructorinfo')->__('Instructor Other Record');
        $this->_updateButton('add', 'label', Mage::helper('bs_instructorinfo')->__('Add Record'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/bs_instructor/otherinfo/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
