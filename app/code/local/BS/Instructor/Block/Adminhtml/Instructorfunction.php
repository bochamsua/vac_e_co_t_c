<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Function admin block
 *
 * @category    BS
 * @package     BS_Instructor
 * @author Bui Phong
 */
class BS_Instructor_Block_Adminhtml_Instructorfunction extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_instructorfunction';
        $this->_blockGroup         = 'bs_instructor';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_instructor')->__('Instructor Function');
        $this->_updateButton('add', 'label', Mage::helper('bs_instructor')->__('Add Instructor Function'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/bs_instructor/instructorfunction/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
