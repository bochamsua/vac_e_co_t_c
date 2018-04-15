<?php
/**
 * BS_WorksheetDoc extension
 * 
 * @category       BS
 * @package        BS_WorksheetDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Worksheet Document admin block
 *
 * @category    BS
 * @package     BS_WorksheetDoc
 * @author      Bui Phong
 */
class BS_WorksheetDoc_Block_Adminhtml_Worksheetdoc extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_worksheetdoc';
        $this->_blockGroup         = 'bs_worksheetdoc';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_worksheetdoc')->__('Worksheet Document');
        $this->_updateButton('add', 'label', Mage::helper('bs_worksheetdoc')->__('Add Worksheet Document'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_material/worksheetdoc/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
