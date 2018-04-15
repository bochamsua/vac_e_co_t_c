<?php
/**
 * BS_CourseCost extension
 * 
 * @category       BS
 * @package        BS_CourseCost
 * @copyright      Copyright (c) 2016
 */
/**
 * Course Cost admin block
 *
 * @category    BS
 * @package     BS_CourseCost
 * @author Bui Phong
 */
class BS_CourseCost_Block_Adminhtml_Coursecost extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_coursecost';
        $this->_blockGroup         = 'bs_coursecost';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_coursecost')->__('Course Cost');
        $this->_updateButton('add', 'label', Mage::helper('bs_coursecost')->__('Add Course Cost'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("catalog/bs_coursecost/coursecost/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
