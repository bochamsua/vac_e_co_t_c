<?php
/**
 * BS_Handover extension
 * 
 * @category       BS
 * @package        BS_Handover
 * @copyright      Copyright (c) 2015
 */
/**
 * Minutes of Handover V2 admin block
 *
 * @category    BS
 * @package     BS_Handover
 * @author Bui Phong
 */
class BS_Handover_Block_Adminhtml_Handovertwo extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_handovertwo';
        $this->_blockGroup         = 'bs_handover';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_handover')->__('Minutes of Handover V2');
        $this->_updateButton('add', 'label', Mage::helper('bs_handover')->__('Add Minutes of Handover V2'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_material/bs_handover/handovertwo/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
