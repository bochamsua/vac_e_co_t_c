<?php
/**
 * BS_InstructorApproval extension
 * 
 * @category       BS
 * @package        BS_InstructorApproval
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Approval admin block
 *
 * @category    BS
 * @package     BS_InstructorApproval
 * @author Bui Phong
 */
class BS_InstructorApproval_Block_Adminhtml_Iapproval extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_iapproval';
        $this->_blockGroup         = 'bs_instructorapproval';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_instructorapproval')->__('Instructor Approval');
        $this->_updateButton('add', 'label', Mage::helper('bs_instructorapproval')->__('Add Instructor Approval'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/bs_instructor/iapproval/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
