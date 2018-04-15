<?php
/**
 * BS_Trainee extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       BS
 * @package        BS_Trainee
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Trainee admin block
 *
 * @category    BS
 * @package     BS_Trainee
 * @author      Bui Phong
 */
class BS_Trainee_Block_Adminhtml_Trainee extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_trainee';
        $this->_blockGroup         = 'bs_trainee';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_trainee')->__('Trainee');
        $this->_updateButton('add', 'label', Mage::helper('bs_trainee')->__('Add Trainee'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/trainee/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

        $this->setTemplate('bs_trainee/grid.phtml');
    }
}
