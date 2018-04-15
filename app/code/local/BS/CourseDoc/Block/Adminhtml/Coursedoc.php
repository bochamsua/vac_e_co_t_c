<?php
/**
 * BS_CourseDoc extension
 * 
 * @category       BS
 * @package        BS_CourseDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Course Document admin block
 *
 * @category    BS
 * @package     BS_CourseDoc
 * @author      Bui Phong
 */
class BS_CourseDoc_Block_Adminhtml_Coursedoc extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_coursedoc';
        $this->_blockGroup         = 'bs_coursedoc';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_coursedoc')->__('Course Document');
        $this->_updateButton('add', 'label', Mage::helper('bs_coursedoc')->__('Add Course Document'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_material/coursedoc/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
