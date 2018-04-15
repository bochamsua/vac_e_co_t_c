<?php
/**
 * BS_Traininglist extension
 * 
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * Training Curriculum admin block
 *
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */
class BS_Traininglist_Block_Adminhtml_Curriculum extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_curriculum';
        $this->_blockGroup         = 'bs_traininglist';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_traininglist')->__('Training Curriculum');
        $this->_updateButton('add', 'label', Mage::helper('bs_traininglist')->__('Add Curriculum'));
//        $this->_addButton('generate', array(
//            'label'     => Mage::helper('bs_traininglist')->__('Generate Training List'),
//            'onclick'   => 'setLocation(\'' . $this->getUrl('*/*/generateSeventeen') .'\')',
//            'class'     => 'reset',
//        ));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/curriculum/curriculum/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

        $this->setTemplate('bs_traininglist/grid.phtml');
    }
}
