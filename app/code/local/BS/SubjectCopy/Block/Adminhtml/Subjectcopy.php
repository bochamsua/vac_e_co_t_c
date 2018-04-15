<?php
/**
 * BS_SubjectCopy extension
 * 
 * @category       BS
 * @package        BS_SubjectCopy
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject Copy admin block
 *
 * @category    BS
 * @package     BS_SubjectCopy
 * @author Bui Phong
 */
class BS_SubjectCopy_Block_Adminhtml_Subjectcopy extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_subjectcopy';
        $this->_blockGroup         = 'bs_subjectcopy';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_subjectcopy')->__('Subject Copy');
        $this->_updateButton('add', 'label', Mage::helper('bs_subjectcopy')->__('Add Subject Copy'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/subject/subjectcopy/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
