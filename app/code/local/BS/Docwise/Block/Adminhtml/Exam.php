<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Exam admin block
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Block_Adminhtml_Exam extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_exam';
        $this->_blockGroup         = 'bs_docwise';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_docwise')->__('Exam');
        $this->_updateButton('add', 'label', Mage::helper('bs_docwise')->__('Add Exam'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_exam/bs_docwise/exam/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
