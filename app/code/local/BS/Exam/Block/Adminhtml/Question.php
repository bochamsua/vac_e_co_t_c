<?php
/**
 * BS_Exam extension
 * 
 * @category       BS
 * @package        BS_Exam
 * @copyright      Copyright (c) 2015
 */
/**
 * Question admin block
 *
 * @category    BS
 * @package     BS_Exam
 * @author Bui Phong
 */
class BS_Exam_Block_Adminhtml_Question extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_question';
        $this->_blockGroup         = 'bs_exam';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_exam')->__('Question');
        $this->_updateButton('add', 'label', Mage::helper('bs_exam')->__('Add Question'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_exam/question/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
