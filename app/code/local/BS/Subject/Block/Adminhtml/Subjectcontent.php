<?php
/**
 * BS_Subject extension
 * 
 * @category       BS
 * @package        BS_Subject
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject Content admin block
 *
 * @category    BS
 * @package     BS_Subject
 * @author Bui Phong
 */
class BS_Subject_Block_Adminhtml_Subjectcontent extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_subjectcontent';
        $this->_blockGroup         = 'bs_subject';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_subject')->__('Subject Content');
        $this->_updateButton('add', 'label', Mage::helper('bs_subject')->__('Add Subject Content'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/subject/subjectcontent/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
