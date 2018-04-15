<?php
/**
 * BS_Handover extension
 * 
 * @category       BS
 * @package        BS_Handover
 * @copyright      Copyright (c) 2015
 */
/**
 * Minutes of Handover V1 admin block
 *
 * @category    BS
 * @package     BS_Handover
 * @author Bui Phong
 */
class BS_Handover_Block_Adminhtml_Handoverone extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_handoverone';
        $this->_blockGroup         = 'bs_handover';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_handover')->__('Minutes of Handover V1');
        $this->_updateButton('add', 'label', Mage::helper('bs_handover')->__('Add Minutes of Handover V1'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_material/bs_handover/handoverone/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
