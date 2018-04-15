<?php
/**
 * BS_Questionnaire extension
 * 
 * @category       BS
 * @package        BS_Questionnaire
 * @copyright      Copyright (c) 2015
 */
/**
 * Questionnaire admin block
 *
 * @category    BS
 * @package     BS_Questionnaire
 * @author Bui Phong
 */
class BS_Questionnaire_Block_Adminhtml_Questionnaire extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_questionnaire';
        $this->_blockGroup         = 'bs_questionnaire';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_questionnaire')->__('Questionnaire');
        $this->_updateButton('add', 'label', Mage::helper('bs_questionnaire')->__('Add Questionnaire'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_exam/questionnaire/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
