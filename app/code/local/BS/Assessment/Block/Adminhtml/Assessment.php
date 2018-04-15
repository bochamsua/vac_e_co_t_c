<?php
/**
 * BS_Assessment extension
 * 
 * @category       BS
 * @package        BS_Assessment
 * @copyright      Copyright (c) 2015
 */
/**
 * Assessment admin block
 *
 * @category    BS
 * @package     BS_Assessment
 * @author Bui Phong
 */
class BS_Assessment_Block_Adminhtml_Assessment extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_assessment';
        $this->_blockGroup         = 'bs_assessment';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_assessment')->__('Assessment');
        $this->_updateButton('add', 'label', Mage::helper('bs_assessment')->__('Add Assessment'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_exam/assessment/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
