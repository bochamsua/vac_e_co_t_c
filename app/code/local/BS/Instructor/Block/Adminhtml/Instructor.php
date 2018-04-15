<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor admin block
 *
 * @category    BS
 * @package     BS_Instructor
 * @author      Bui Phong
 */
class BS_Instructor_Block_Adminhtml_Instructor extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_instructor';
        $this->_blockGroup         = 'bs_instructor';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_instructor')->__('Instructor');
        $this->_updateButton('add', 'label', Mage::helper('bs_instructor')->__('Add Instructor'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/bs_instructor/instructor/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

        $this->setTemplate('bs_instructor/grid.phtml');
    }
}
