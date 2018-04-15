<?php
/**
 * BS_CourseCost extension
 * 
 * @category       BS
 * @package        BS_CourseCost
 * @copyright      Copyright (c) 2016
 */
/**
 * Manage Group Items admin block
 *
 * @category    BS
 * @package     BS_CourseCost
 * @author Bui Phong
 */
class BS_CourseCost_Block_Adminhtml_Costitem extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_costitem';
        $this->_blockGroup         = 'bs_coursecost';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_coursecost')->__('Manage Group Items');
        $this->_updateButton('add', 'label', Mage::helper('bs_coursecost')->__('Add Manage Group Items'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("catalog/bs_coursecost/costitem/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
