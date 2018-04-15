<?php
/**
 * BS_ImportTrainee extension
 * 
 * @category       BS
 * @package        BS_ImportTrainee
 * @copyright      Copyright (c) 2015
 */
/**
 * Import Trainee admin block
 *
 * @category    BS
 * @package     BS_ImportTrainee
 * @author Bui Phong
 */
class BS_ImportTrainee_Block_Adminhtml_Importtrainee extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_importtrainee';
        $this->_blockGroup         = 'bs_importtrainee';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_importtrainee')->__('Import Trainee');
        $this->_updateButton('add', 'label', Mage::helper('bs_importtrainee')->__('Add Import Trainee'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/importtrainee/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
